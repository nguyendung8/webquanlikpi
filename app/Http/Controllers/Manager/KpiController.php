<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Kpi;
use App\Models\PhancongKpi;
use App\Models\User;
use App\Models\Phongban;
use App\Models\LoaiKpi;
use App\Models\DanhgiaKpi;
use App\Models\DulieuKpi;
use App\Models\Nhatky;
use Illuminate\Support\Facades\Storage;
use App\Models\Thongbao;

class KpiController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->ID_quyen != 2) {
                abort(403, 'Bạn không có quyền truy cập trang này.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $perPage = $request->get('per_page', 10);
        $managerId = Auth::id();

        // Chỉ lấy KPI mà manager này phân công
        $query = PhancongKpi::with(['kpi', 'user', 'phongban', 'danhgiaKpi'])
            ->where('ID_nguoi_phan_cong', $managerId)
            ->orderBy('ID_Phancong', 'desc');

        if ($search) {
            $query->whereHas('kpi', function($q) use ($search) {
                $q->where('Ten_kpi', 'like', "%{$search}%");
            });
        }

        $phancongKpis = $query->paginate($perPage);

        // Thống kê cho biểu đồ - chỉ KPI mà manager này phân công
        $kpiStats = [
            'byStatus' => PhancongKpi::where('ID_nguoi_phan_cong', $managerId)
                ->select('Trang_thai', DB::raw('count(*) as count'))
                ->groupBy('Trang_thai')
                ->pluck('count', 'Trang_thai'),
            'byMonth' => PhancongKpi::where('ID_nguoi_phan_cong', $managerId)
                ->selectRaw('MONTH(Ngay_batdau) as month, count(*) as count')
                ->whereYear('Ngay_batdau', date('Y'))
                ->groupBy('month')
                ->pluck('count', 'month')
        ];

        return view('manager.kpi.index', compact('phancongKpis', 'kpiStats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Ten_kpi' => 'required|string|max:100',
            'Chi_tieu' => 'required|numeric',
            'Donvi_tinh' => 'required|string|max:50',
            'Do_uu_tien' => 'required|in:Rất gấp,Gấp,Trung Bình,Không',
            'Mo_ta' => 'nullable|string',
            'ID_loai_kpi' => 'required|exists:loai_kpi,ID_loai_kpi',
            'assignment_type' => 'required|in:user,department',
            'ID_user' => 'required_if:assignment_type,user|nullable|exists:users,ID_user',
            'ID_phongban' => 'required_if:assignment_type,department|nullable|exists:phongban,ID_phongban',
            'Ngay_batdau' => 'required|date',
            'Ngay_ketthuc' => 'required|date|after:Ngay_batdau'
        ]);

        DB::beginTransaction();
        try {
            // Tạo KPI
            $kpi = Kpi::create([
                'Ten_kpi' => $request->Ten_kpi,
                'Chi_tieu' => $request->Chi_tieu,
                'Donvi_tinh' => $request->Donvi_tinh,
                'Do_uu_tien' => $request->Do_uu_tien,
                'Mo_ta' => $request->Mo_ta,
                'ID_loai_kpi' => $request->ID_loai_kpi
            ]);

            if ($request->assignment_type === 'user') {
                // Phân công cho người dùng cụ thể
                $phancong = PhancongKpi::create([
                    'ID_kpi' => $kpi->ID_kpi,
                    'ID_phongban' => $request->ID_phongban ?? User::find($request->ID_user)->ID_phongban,
                    'ID_user' => $request->ID_user,
                    'ID_nguoi_phan_cong' => Auth::id(),
                    'Ngay_batdau' => $request->Ngay_batdau,
                    'Ngay_ketthuc' => $request->Ngay_ketthuc,
                    'ID_loai_kpi' => $request->ID_loai_kpi
                ]);

                // Thông báo cho user được phân công
                Thongbao::create([
                    'ID_nguoigui' => Auth::id(),
                    'ID_nguoinhan' => $request->ID_user,
                    'Tieu_de' => 'Phân công KPI mới',
                    'Noi_dung' => "Bạn đã được phân công KPI: {$request->Ten_kpi}. Deadline: {$request->Ngay_ketthuc}",
                    'Loai_thongbao' => 'phancong_kpi',
                    'Da_xem' => 0
                ]);

                // Ghi nhật ký
                Nhatky::create([
                    'ID_nguoithuchien' => Auth::id(),
                    'Doi_tuong' => 'user',
                    'ID_doi_tuong' => $phancong->user->ID_user,
                    'Hanh_dong' => 'phan cong kpi'
                ]);

            } else {
                // Phân công cho cả phòng ban - chỉ lấy user có role = 3
                $usersInDept = User::where('ID_phongban', $request->ID_phongban)
                    ->where('Trang_thai', 'hoat_dong')
                    ->where('ID_quyen', 3)
                    ->get();

                foreach ($usersInDept as $user) {
                    $phancong = PhancongKpi::create([
                        'ID_kpi' => $kpi->ID_kpi,
                        'ID_user' => $user->ID_user,
                        'ID_phongban' => $request->ID_phongban,
                        'ID_nguoi_phan_cong' => Auth::id(),
                        'Ngay_batdau' => $request->Ngay_batdau,
                        'Ngay_ketthuc' => $request->Ngay_ketthuc,
                        'Trang_thai' => 'chua_thuc_hien',
                        'ID_loai_kpi' => $request->ID_loai_kpi
                    ]);

                    // Thông báo cho từng user trong phòng ban
                    Thongbao::create([
                        'ID_nguoigui' => Auth::id(),
                        'ID_nguoinhan' => $user->ID_user,
                        'Tieu_de' => 'Phân công KPI mới',
                        'Noi_dung' => "Bạn đã được phân công KPI: {$request->Ten_kpi}. Deadline: {$request->Ngay_ketthuc}",
                        'Loai_thongbao' => 'phancong_kpi',
                        'Da_xem' => 0
                    ]);

                    // Ghi nhật ký cho mỗi phân công
                    Nhatky::create([
                        'ID_nguoithuchien' => Auth::id(),
                        'Doi_tuong' => 'user',
                        'ID_doi_tuong' => $user->ID_user,
                        'Hanh_dong' => 'phan cong kpi'
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Tạo KPI và phân công thành công!']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $managerId = Auth::id();
        $phancongKpi = PhancongKpi::where('ID_nguoi_phan_cong', $managerId)->findOrFail($id);
        $kpi = Kpi::find($phancongKpi->kpi->ID_kpi);

        DB::beginTransaction();
        try {
            // Ghi nhật ký trước khi xóa
            Nhatky::create([
                'ID_nguoithuchien' => Auth::id(),
                'Doi_tuong' => 'phancong_kpi',
                'ID_doi_tuong' => $phancongKpi->ID_Phancong,
                'Hanh_dong' => 'xoa'
            ]);

            // Xóa phân công
            $phancongKpi->delete();

            // Xóa KPI
            $kpi->delete();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Xóa KPI thành công!']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    // Xem bài nộp của user
    public function viewSubmissions($id)
    {
        $managerId = Auth::id();
        $phancongKpi = PhancongKpi::with(['kpi', 'user', 'phongban', 'danhgiaKpi'])
            ->where('ID_nguoi_phan_cong', $managerId)
            ->findOrFail($id);

        $submissions = DulieuKpi::where('ID_phancong', $id)
            ->with('nguoigui')
            ->orderBy('Ngay_gui', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'phancong' => $phancongKpi,
            'submissions' => $submissions
        ]);
    }

    // Thêm method downloadFile
    public function downloadFile($id)
    {
        $managerId = Auth::id();
        $dulieuKpi = DulieuKpi::whereHas('phancongKpi', function($query) use ($managerId) {
            $query->where('ID_nguoi_phan_cong', $managerId);
        })->findOrFail($id);

        if (!$dulieuKpi->File_path || !Storage::disk('public')->exists($dulieuKpi->File_path)) {
            abort(404, 'File không tồn tại');
        }

        return Storage::disk('public')->download($dulieuKpi->File_path, $dulieuKpi->File_name);
    }

    // Cập nhật đánh giá KPI
    public function updateEvaluation(Request $request, $id)
    {
        $request->validate([
            'Ketqua_thuchien' => 'required|numeric|min:0',
            'Ty_le_hoanthanh' => 'required|numeric|min:0|max:100',
            'Trang_thai' => 'required|in:cho_duyet,dat,khong_dat',
            'Nhan_xet' => 'nullable|string'
        ]);

        $managerId = Auth::id();
        $phancongKpi = PhancongKpi::where('ID_nguoi_phan_cong', $managerId)->findOrFail($id);

        DB::beginTransaction();
        try {
            // Sử dụng updateOrCreate để đảm bảo chỉ có 1 đánh giá
            $danhgia = DanhgiaKpi::updateOrCreate(
                ['ID_phancong' => $id], // Điều kiện tìm kiếm
                [
                    'Ketqua_thuchien' => $request->Ketqua_thuchien,
                    'Ty_le_hoanthanh' => $request->Ty_le_hoanthanh,
                    'Trang_thai' => $request->Trang_thai,
                    'ID_nguoithamdinh' => Auth::id(),
                    'Ngay_thamdinh' => now(),
                    'Nhan_xet' => $request->Nhan_xet
                ]
            );

            // Kiểm tra và cập nhật trạng thái phancong_kpi
            if ($request->Ty_le_hoanthanh >= 100) {
                $phancongKpi->update(['Trang_thai' => 'hoan_thanh']);
            } else if ($request->Ty_le_hoanthanh > 0) {
                // Nếu có tiến độ nhưng chưa đạt 100% thì chuyển thành đang thực hiện
                $phancongKpi->update(['Trang_thai' => 'dang_thuc_hien']);
            }

            // Thông báo cho user về đánh giá
            Thongbao::create([
                'ID_nguoigui' => Auth::id(),
                'ID_nguoinhan' => $phancongKpi->ID_user,
                'Tieu_de' => 'Đánh giá KPI',
                'Noi_dung' => "KPI '{$phancongKpi->kpi->Ten_kpi}' đã được đánh giá. Tỷ lệ hoàn thành: {$request->Ty_le_hoanthanh}%. Trạng thái: " . ucfirst(str_replace('_', ' ', $request->Trang_thai)),
                'Loai_thongbao' => 'review_kpi',
                'Da_xem' => 0
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $danhgia->wasRecentlyCreated ? 'Tạo đánh giá thành công!' : 'Cập nhật đánh giá thành công!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    // Cập nhật tiến độ
    public function updateProgress(Request $request, $id)
    {
        $request->validate([
            'Trang_thai' => 'required|in:chua_thuc_hien,dang_thuc_hien,hoan_thanh,qua_han'
        ]);

        $managerId = Auth::id();
        $phancongKpi = PhancongKpi::where('ID_nguoi_phan_cong', $managerId)->findOrFail($id);
        $phancongKpi->update(['Trang_thai' => $request->Trang_thai]);

        return response()->json(['success' => true, 'message' => 'Cập nhật tiến độ thành công!']);
    }
}

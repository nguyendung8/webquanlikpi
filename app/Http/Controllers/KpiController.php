<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kpi;
use App\Models\PhancongKpi;
use App\Models\LoaiKpi;
use App\Models\User;
use App\Models\Phongban;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KpiController extends Controller
{
    public function __construct()
    {
        // Check quyền admin cho tất cả method
        $this->middleware(function ($request, $next) {
            if (Auth::user()->ID_quyen != 1) {
                abort(403, 'Bạn không có quyền truy cập trang này.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $perPage = $request->get('per_page', 10);

        // Lấy dữ liệu cho biểu đồ KPI tạo mới theo tháng
        $kpiByMonth = Kpi::select(
                DB::raw('MONTH(Ngay_tao) as month'),
                DB::raw('YEAR(Ngay_tao) as year'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('Ngay_tao', date('Y'))
            ->groupBy('month', 'year')
            ->orderBy('month')
            ->get();

        // Lấy dữ liệu cho biểu đồ KPI theo loại
        $kpiByType = DB::table('kpi')
            ->join('loai_kpi', 'kpi.ID_loai_kpi', '=', 'loai_kpi.ID_loai_kpi')
            ->select('loai_kpi.Ten_loai_kpi', DB::raw('COUNT(*) as count'))
            ->groupBy('loai_kpi.ID_loai_kpi', 'loai_kpi.Ten_loai_kpi')
            ->get();

        // Lấy danh sách phân công KPI với tìm kiếm và phân trang
        $query = PhancongKpi::with(['kpi', 'user', 'phongban', 'loaiKpi']);

        if ($search) {
            $query->whereHas('kpi', function($q) use ($search) {
                $q->where('Ten_kpi', 'like', "%{$search}%");
            })->orWhereHas('user', function($q) use ($search) {
                $q->where('Ho_ten', 'like', "%{$search}%");
            })->orWhereHas('phongban', function($q) use ($search) {
                $q->where('Ten_phongban', 'like', "%{$search}%");
            });
        }

        $phancongKpis = $query->orderBy('ID_Phancong', 'desc')->paginate($perPage);

        return view('admin.kpi.index', compact('kpiByMonth', 'kpiByType', 'phancongKpis', 'search', 'perPage'));
    }

    public function create()
    {
        $loaiKpis = LoaiKpi::all();
        $users = User::where('Trang_thai', 'hoat_dong')->get();
        $phongbans = Phongban::all();

        return view('admin.kpi.create', compact('loaiKpis', 'users', 'phongbans'));
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
            'ID_user' => 'nullable|exists:users,ID_user',
            'ID_phongban' => 'nullable|exists:phongban,ID_phongban',
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

            // Tạo phân công KPI
            PhancongKpi::create([
                'ID_kpi' => $kpi->ID_kpi,
                'ID_user' => $request->ID_user,
                'ID_phongban' => $request->ID_phongban,
                'Ngay_batdau' => $request->Ngay_batdau,
                'Ngay_ketthuc' => $request->Ngay_ketthuc,
                'Trang_thai' => 'chua_thuc_hien',
                'ID_loai_kpi' => $request->ID_loai_kpi
            ]);

            DB::commit();
            return redirect()->route('kpi.index')->with('success', 'Tạo KPI thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra khi tạo KPI!');
        }
    }

    public function edit($id)
    {
        $phancongKpi = PhancongKpi::with(['kpi', 'user', 'phongban'])->findOrFail($id);
        $loaiKpis = LoaiKpi::all();
        $users = User::where('Trang_thai', 'hoat_dong')->get();
        $phongbans = Phongban::all();

        return view('admin.kpi.edit', compact('phancongKpi', 'loaiKpis', 'users', 'phongbans'));
    }

    public function update(Request $request, $id)
    {
        $phancongKpi = PhancongKpi::findOrFail($id);

        $request->validate([
            'Ten_kpi' => 'required|string|max:100',
            'Chi_tieu' => 'required|numeric',
            'Donvi_tinh' => 'required|string|max:50',
            'Do_uu_tien' => 'required|in:Rất gấp,Gấp,Trung Bình,Không',
            'Mo_ta' => 'nullable|string',
            'ID_user' => 'nullable|exists:users,ID_user',
            'ID_phongban' => 'nullable|exists:phongban,ID_phongban',
            'Ngay_batdau' => 'required|date',
            'Ngay_ketthuc' => 'required|date|after:Ngay_batdau',
            'Trang_thai' => 'required|in:chua_thuc_hien,dang_thuc_hien,hoan_thanh,qua_han'
        ]);

        DB::beginTransaction();
        try {
            // Cập nhật KPI
            $phancongKpi->kpi->update([
                'Ten_kpi' => $request->Ten_kpi,
                'Chi_tieu' => $request->Chi_tieu,
                'Donvi_tinh' => $request->Donvi_tinh,
                'Do_uu_tien' => $request->Do_uu_tien,
                'Mo_ta' => $request->Mo_ta
            ]);

            // Cập nhật phân công KPI
            $phancongKpi->update([
                'ID_user' => $request->ID_user,
                'ID_phongban' => $request->ID_phongban,
                'Ngay_batdau' => $request->Ngay_batdau,
                'Ngay_ketthuc' => $request->Ngay_ketthuc,
                'Trang_thai' => $request->Trang_thai
            ]);

            DB::commit();
            return redirect()->route('kpi.index')->with('success', 'Cập nhật KPI thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật KPI!');
        }
    }

    public function destroy($id)
    {
        $phancongKpi = PhancongKpi::findOrFail($id);

        DB::beginTransaction();
        try {
            // Xóa phân công KPI
            $phancongKpi->delete();

            // Xóa KPI
            $phancongKpi->kpi->delete();

            DB::commit();
            return redirect()->route('kpi.index')->with('success', 'Xóa KPI thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra khi xóa KPI!');
        }
    }
}

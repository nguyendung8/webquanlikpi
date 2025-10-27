<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\PhancongKpi;
use App\Models\DulieuKpi;
use App\Models\Thongbao;

class KpiController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->ID_quyen != 3) {
                abort(403, 'Bạn không có quyền truy cập trang này.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $userId = Auth::id();
        $search = $request->get('search');
        $status = $request->get('status');
        $perPage = $request->get('per_page', 10);

        $query = PhancongKpi::with(['kpi', 'danhgiaKpi'])
            ->where('ID_user', $userId);

        if ($search) {
            $query->whereHas('kpi', function($q) use ($search) {
                $q->where('Ten_kpi', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('Trang_thai', $status);
        }

        $kpis = $query->orderBy('Ngay_ketthuc', 'desc')->paginate($perPage);

        return view('user.kpi.index', compact('kpis', 'search', 'status', 'perPage'));
    }

    public function submit(Request $request, $id)
    {
        $request->validate([
            'Minh_chung' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240'
        ]);

        $userId = Auth::id();
        $phancongKpi = PhancongKpi::where('ID_user', $userId)->findOrFail($id);

        $filePath = null;
        $fileName = null;

        // Upload file nếu có
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('kpi-submissions/' . $userId, 'public');
        }

        // Tạo bản ghi dữ liệu KPI
        DulieuKpi::create([
            'ID_phancong' => $id,
            'ID_nguoigui' => $userId,
            'Ketqua_thuchien' => 0, // Để trống hoặc 0, manager sẽ đánh giá sau
            'Minh_chung' => $request->Minh_chung,
            'File_path' => $filePath,
            'File_name' => $fileName
        ]);

        // Thông báo cho manager về bài nộp
        Thongbao::create([
            'ID_nguoigui' => $userId,
            'ID_nguoinhan' => $phancongKpi->ID_nguoi_phan_cong,
            'Tieu_de' => 'Nộp bài KPI',
            'Noi_dung' => "Nhân viên đã nộp bài cho KPI: '{$phancongKpi->kpi->Ten_kpi}'. Vui lòng kiểm tra và đánh giá.",
            'Loai_thongbao' => 'submit_kpi',
            'Da_xem' => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nộp bài thành công! Quản lý sẽ đánh giá kết quả.'
        ]);
    }

    public function downloadFile($id)
    {
        $userId = Auth::id();
        $dulieuKpi = DulieuKpi::where('ID_nguoigui', $userId)->findOrFail($id);

        if (!$dulieuKpi->File_path) {
            abort(404, 'File không tồn tại');
        }

        return Storage::disk('public')->download($dulieuKpi->File_path, $dulieuKpi->File_name);
    }

    public function viewSubmissions($id)
    {
        $userId = Auth::id();
        $phancongKpi = PhancongKpi::with(['kpi', 'danhgiaKpi', 'loaiKpi', 'nguoiPhanCong', 'phongban'])
            ->where('ID_user', $userId)
            ->findOrFail($id);

        $submissions = DulieuKpi::where('ID_phancong', $id)
            ->where('ID_nguoigui', $userId)
            ->orderBy('Ngay_gui', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'phancong' => $phancongKpi,
            'submissions' => $submissions
        ]);
    }
}

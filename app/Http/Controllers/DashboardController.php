<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Kpi;
use App\Models\User;
use App\Models\Phongban;
use App\Models\PhancongKpi;
use App\Models\DanhgiaKpi;
use App\Models\Tasks;
use App\Models\Thongbao;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->ID_quyen != 1) {
                abort(403, 'Bạn không có quyền truy cập trang này.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        // Thống kê tổng quan
        $stats = [
            'active_kpis' => PhancongKpi::whereIn('Trang_thai', ['dang_thuc_hien', 'hoan_thanh'])->count(),
            'total_users' => User::count(),
            'attention_kpis' => PhancongKpi::where('Trang_thai', 'qua_han')->count(),
            'average_progress' => $this->calculateAverageProgress()
        ];

        // Biểu đồ số lượng KPI theo phòng ban
        $kpiByDepartment = DB::table('phancong_kpi')
            ->join('phongban', 'phancong_kpi.ID_phongban', '=', 'phongban.ID_phongban')
            ->select('phongban.Ten_phongban', DB::raw('COUNT(*) as count'))
            ->groupBy('phongban.ID_phongban', 'phongban.Ten_phongban')
            ->get();

        // Biểu đồ tỷ lệ hoàn thành theo phòng ban
        $completionByDepartment = DB::table('phancong_kpi')
            ->join('phongban', 'phancong_kpi.ID_phongban', '=', 'phongban.ID_phongban')
            ->join('danhgia_kpi', 'phancong_kpi.ID_Phancong', '=', 'danhgia_kpi.ID_phancong')
            ->select(
                'phongban.Ten_phongban',
                DB::raw('AVG(danhgia_kpi.Ty_le_hoanthanh) as avg_completion')
            )
            ->groupBy('phongban.ID_phongban', 'phongban.Ten_phongban')
            ->get();

        // Biểu đồ mức độ hoàn thành đạt tiến độ
        $progressStatus = DB::table('danhgia_kpi')
            ->select('Trang_thai', DB::raw('COUNT(*) as count'))
            ->groupBy('Trang_thai')
            ->get();

        return view('admin.dashboard.index', compact('stats', 'kpiByDepartment', 'completionByDepartment', 'progressStatus'));
    }

    private function calculateAverageProgress()
    {
        $avgProgress = DB::table('danhgia_kpi')
            ->join('phancong_kpi', 'danhgia_kpi.ID_Phancong', '=', 'phancong_kpi.ID_Phancong')
            ->whereIn('phancong_kpi.Trang_thai', ['dang_thuc_hien', 'chua_thuc_hien'])
            ->whereNotNull('danhgia_kpi.Ty_le_hoanthanh')
            ->avg('danhgia_kpi.Ty_le_hoanthanh');

        return round($avgProgress ?? 0, 1);
    }

    public function getNotifications(Request $request)
    {
        $userId = Auth::id();
        $page = $request->get('page', 1);
        $perPage = 10;

        $notifications = Thongbao::with('nguoigui')
            ->where('ID_nguoinhan', $userId)
            ->orderBy('Ngay_gui', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $notifications->items(),
            'current_page' => $notifications->currentPage(),
            'last_page' => $notifications->lastPage(),
            'has_more' => $notifications->hasMorePages(),
            'total' => $notifications->total()
        ]);
    }

    public function markAsRead($id)
    {
        $userId = Auth::id();
        Thongbao::where('ID_thongbao', $id)
            ->where('ID_nguoinhan', $userId)
            ->update(['Da_xem' => 1]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $userId = Auth::id();
        Thongbao::where('ID_nguoinhan', $userId)
            ->where('Da_xem', false)
            ->update(['Da_xem' => true]);

        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $userId = Auth::id();
        $count = Thongbao::where('ID_nguoinhan', $userId)
            ->where('Da_xem', 0)
            ->count();

        return response()->json(['count' => $count]);
    }
}

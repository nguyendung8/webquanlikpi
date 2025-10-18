<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PhancongKpi;
use App\Models\User;
use App\Models\Phongban;

class DashboardController extends Controller
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

    public function index()
    {
        $managerId = Auth::id();

        // Thống kê tổng quan - chỉ KPI mà manager này phân công
        $stats = [
            'total_kpis' => PhancongKpi::where('ID_nguoi_phan_cong', $managerId)->count(),
            'active_kpis' => PhancongKpi::where('ID_nguoi_phan_cong', $managerId)
                ->whereIn('Trang_thai', ['dang_thuc_hien', 'hoan_thanh'])->count(),
            'completed_kpis' => PhancongKpi::where('ID_nguoi_phan_cong', $managerId)
                ->where('Trang_thai', 'hoan_thanh')->count(),
            'overdue_kpis' => PhancongKpi::where('ID_nguoi_phan_cong', $managerId)
                ->where('Trang_thai', 'qua_han')->count()
        ];

        // KPI theo trạng thái - chỉ KPI mà manager này phân công
        $kpiByStatus = DB::table('phancong_kpi')
            ->where('ID_nguoi_phan_cong', $managerId)
            ->select('Trang_thai', DB::raw('COUNT(*) as count'))
            ->groupBy('Trang_thai')
            ->get();

        // KPI theo user - chỉ KPI mà manager này phân công
        $kpiByUser = DB::table('phancong_kpi')
            ->join('users', 'phancong_kpi.ID_user', '=', 'users.ID_user')
            ->where('phancong_kpi.ID_nguoi_phan_cong', $managerId)
            ->select('users.Ho_ten', DB::raw('COUNT(*) as count'))
            ->groupBy('users.ID_user', 'users.Ho_ten')
            ->get();

        // Hoạt động gần đây - chỉ KPI mà manager này phân công
        $recentActivities = DB::table('phancong_kpi')
            ->join('kpi', 'phancong_kpi.ID_kpi', '=', 'kpi.ID_kpi')
            ->join('users', 'phancong_kpi.ID_user', '=', 'users.ID_user')
            ->where('phancong_kpi.ID_nguoi_phan_cong', $managerId)
            ->select('kpi.Ten_kpi', 'users.Ho_ten', 'phancong_kpi.Ngay_batdau', 'phancong_kpi.Trang_thai')
            ->orderBy('phancong_kpi.Ngay_batdau', 'desc')
            ->limit(5)
            ->get();

        return view('manager.dashboard.index', compact('stats', 'kpiByStatus', 'kpiByUser', 'recentActivities'));
    }
}

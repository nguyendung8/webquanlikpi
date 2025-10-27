<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PhancongKpi;
use App\Models\Tasks;

class DashboardController extends Controller
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

    public function index()
    {
        $userId = Auth::id();

        // Thống kê KPI của user
        $kpiStats = [
            'total' => PhancongKpi::where('ID_user', $userId)->count(),
            'completed' => PhancongKpi::where('ID_user', $userId)->where('Trang_thai', 'hoan_thanh')->count(),
            'in_progress' => PhancongKpi::where('ID_user', $userId)->where('Trang_thai', 'dang_thuc_hien')->count(),
            'overdue' => PhancongKpi::where('ID_user', $userId)->where('Trang_thai', 'qua_han')->count(),
            'not_started' => PhancongKpi::where('ID_user', $userId)->where('Trang_thai', 'chua_thuc_hien')->count(),
        ];

        // Thống kê theo trạng thái cho biểu đồ
        $kpiByStatus = PhancongKpi::where('ID_user', $userId)
            ->select('Trang_thai', DB::raw('count(*) as count'))
            ->groupBy('Trang_thai')
            ->pluck('count', 'Trang_thai');

        // Thống kê theo tháng cho biểu đồ
        $kpiByMonth = PhancongKpi::where('ID_user', $userId)
            ->selectRaw('MONTH(Ngay_batdau) as month, count(*) as count')
            ->whereYear('Ngay_batdau', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month');

        // Thống kê tasks theo trạng thái của user
        $taskStats = [
            'total' => Tasks::whereHas('users', function($q) use ($userId) {
                $q->where('ID_user', $userId);
            })->count(),
            'completed' => Tasks::whereHas('users', function($q) use ($userId) {
                $q->where('ID_user', $userId)
                  ->where('task_user.trang_thai', 'hoan_thanh');
            })->count(),
            'in_progress' => Tasks::whereHas('users', function($q) use ($userId) {
                $q->where('ID_user', $userId)
                  ->where('task_user.trang_thai', 'dang_thuc_hien');
            })->count(),
            'not_started' => Tasks::whereHas('users', function($q) use ($userId) {
                $q->where('ID_user', $userId)
                  ->where('task_user.trang_thai', 'chua_bat_dau');
            })->count(),
        ];

        // KPI gần deadline (trong 7 ngày tới)
        $upcomingKpis = PhancongKpi::with(['kpi', 'danhgiaKpi'])
            ->where('ID_user', $userId)
            ->where('Ngay_ketthuc', '>=', now())
            ->where('Ngay_ketthuc', '<=', now()->addDays(7))
            ->orderBy('Ngay_ketthuc', 'asc')
            ->limit(5)
            ->get();

        // Tasks gần deadline (trong 7 ngày tới)
        $upcomingTasks = Tasks::whereHas('users', function($q) use ($userId) {
                $q->where('ID_user', $userId);
            })
            ->where('Ngay_het_han', '>=', now())
            ->where('Ngay_het_han', '<=', now()->addDays(7))
            ->orderBy('Ngay_het_han', 'asc')
            ->limit(5)
            ->get();

        return view('user.dashboard.index', compact(
            'kpiStats',
            'kpiByStatus',
            'kpiByMonth',
            'taskStats',
            'upcomingKpis',
            'upcomingTasks'
        ));
    }
}

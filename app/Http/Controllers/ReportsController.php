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

class ReportsController extends Controller
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
        // 1. Thống kê KPI theo trạng thái
        $kpiByStatus = DB::table('phancong_kpi')
            ->select('Trang_thai', DB::raw('COUNT(*) as count'))
            ->groupBy('Trang_thai')
            ->get();

        // 2. Thống kê KPI theo độ ưu tiên
        $kpiByPriority = DB::table('kpi')
            ->join('phancong_kpi', 'kpi.ID_kpi', '=', 'phancong_kpi.ID_kpi')
            ->select('kpi.Do_uu_tien', DB::raw('COUNT(*) as count'))
            ->groupBy('kpi.Do_uu_tien')
            ->get();

        // 3. Thống kê đánh giá KPI theo kết quả
        $danhgiaByResult = DB::table('danhgia_kpi')
            ->select('Trang_thai', DB::raw('COUNT(*) as count'))
            ->groupBy('Trang_thai')
            ->get();

        // 4. Thống kê Tasks theo trạng thái (từ pivot table vì mỗi user có trạng thái riêng)
        $tasksByStatus = DB::table('task_user')
            ->select('trang_thai', DB::raw('COUNT(*) as count'))
            ->groupBy('trang_thai')
            ->get();

        // 5. Thống kê người dùng theo phòng ban
        $usersByDepartment = DB::table('users')
            ->join('phongban', 'users.ID_phongban', '=', 'phongban.ID_phongban')
            ->select('phongban.Ten_phongban', DB::raw('COUNT(*) as count'))
            ->groupBy('phongban.ID_phongban', 'phongban.Ten_phongban')
            ->get();

        // 6. Thống kê KPI được tạo theo tháng (6 tháng gần nhất)
        $kpiByMonth = DB::table('kpi')
            ->select(
                DB::raw('DATE_FORMAT(Ngay_tao, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('Ngay_tao', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 6 MONTH)'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.reports.index', compact(
            'kpiByStatus',
            'kpiByPriority',
            'danhgiaByResult',
            'tasksByStatus',
            'usersByDepartment',
            'kpiByMonth'
        ));
    }
}

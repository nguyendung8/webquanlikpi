<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PhancongKpi;
use App\Models\Tasks;

class CalendarController extends Controller
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

        // Lấy KPI của user
        $kpis = PhancongKpi::with(['kpi'])
            ->where('ID_user', $userId)
            ->get();

        // Lấy tasks của user với trạng thái từ pivot
        $tasks = Tasks::with(['users' => function($q) use ($userId) {
                $q->where('ID_user', $userId);
            }])
            ->whereHas('users', function($q) use ($userId) {
                $q->where('ID_user', $userId);
            })
            ->whereNotNull('Ngay_het_han')
            ->get();

        // Chuẩn bị dữ liệu cho calendar
        $events = [];

        // Thêm KPI events
        foreach ($kpis as $phancong) {
            $events[] = [
                'id' => 'kpi_' . $phancong->ID_Phancong,
                'title' => $phancong->kpi->Ten_kpi,
                'start' => $phancong->Ngay_batdau,
                'end' => $phancong->Ngay_ketthuc,
                'type' => 'kpi',
                'color' => $this->getKpiColor($phancong->Trang_thai),
                'description' => $phancong->kpi->Mo_ta ?? '',
                'status' => $phancong->Trang_thai
            ];
        }

        // Thêm task events với trạng thái từ pivot
        foreach ($tasks as $task) {
            $user = $task->users->first();
            $taskStatus = $user ? $user->pivot->trang_thai : 'chua_bat_dau';

            $events[] = [
                'id' => 'task_' . $task->ID_task,
                'title' => $task->Ten_task,
                'start' => $task->Ngay_het_han,
                'end' => $task->Ngay_het_han,
                'type' => 'task',
                'color' => $this->getTaskColor($taskStatus),
                'description' => $task->Mo_ta ?? '',
                'status' => $taskStatus
            ];
        }

        return view('user.calendar.index', compact('events'));
    }

    private function getKpiColor($status)
    {
        switch ($status) {
            case 'hoan_thanh':
                return '#28a745'; // Green
            case 'dang_thuc_hien':
                return '#ffc107'; // Yellow
            case 'qua_han':
                return '#dc3545'; // Red
            case 'chua_thuc_hien':
            default:
                return '#6c757d'; // Gray
        }
    }

    private function getTaskColor($status)
    {
        switch ($status) {
            case 'hoan_thanh':
                return '#28a745'; // Green
            case 'dang_thuc_hien':
                return '#17a2b8'; // Blue
            case 'chua_bat_dau':
            default:
                return '#6c757d'; // Gray
        }
    }
}

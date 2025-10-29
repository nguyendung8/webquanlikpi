<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tasks;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
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

        // Lấy dữ liệu cho biểu đồ tasks tạo mới theo tháng
        $taskByMonth = Tasks::select(
                DB::raw('MONTH(Ngay_giao) as month'),
                DB::raw('YEAR(Ngay_giao) as year'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('Ngay_giao', date('Y'))
            ->groupBy('month', 'year')
            ->orderBy('month')
            ->get();

        // Lấy dữ liệu cho biểu đồ tasks theo trạng thái
        $taskByStatus = DB::table('task_user')
            ->select('trang_thai', DB::raw('COUNT(*) as count'))
            ->groupBy('trang_thai')
            ->get();

        // Lấy danh sách tasks với tìm kiếm và phân trang
        $query = Tasks::with(['users']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('Ten_task', 'like', "%{$search}%")
                  ->orWhere('Mo_ta', 'like', "%{$search}%");
            })->orWhereHas('users', function($q) use ($search) {
                $q->where('Ho_ten', 'like', "%{$search}%");
            });
        }

        $tasks = $query->orderBy('Ngay_giao', 'desc')->paginate($perPage);

        return view('admin.task.index', compact('taskByMonth', 'taskByStatus', 'tasks', 'search', 'perPage'));
    }
}


<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tasks;
use App\Models\Thongbao;

class TasksController extends Controller
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
        $perPage = $request->get('per_page', 10);

        $query = Tasks::where('ID_user_duocgiao', $userId);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('Ten_task', 'like', "%{$search}%")
                  ->orWhere('Mo_ta', 'like', "%{$search}%");
            });
        }

        // Lấy tất cả tasks cho Kanban board
        $tasks = $query->orderBy('Ngay_giao', 'desc')->get();

        // Thống kê tasks
        $taskStats = [
            'total' => Tasks::where('ID_user_duocgiao', $userId)->count(),
            'completed' => Tasks::where('ID_user_duocgiao', $userId)->where('Trang_thai', 'hoan_thanh')->count(),
            'in_progress' => Tasks::where('ID_user_duocgiao', $userId)->where('Trang_thai', 'dang_thuc_hien')->count(),
            'not_started' => Tasks::where('ID_user_duocgiao', $userId)->where('Trang_thai', 'chua_bat_dau')->count(),
        ];

        return view('user.tasks.index', compact('tasks', 'taskStats', 'search', 'perPage'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'Trang_thai' => 'required|in:chua_bat_dau,dang_thuc_hien,hoan_thanh'
        ]);

        $userId = Auth::id();
        $task = Tasks::where('ID_user_duocgiao', $userId)->findOrFail($id);

        $oldStatus = $task->Trang_thai;
        $newStatus = $request->Trang_thai;

        $task->update(['Trang_thai' => $newStatus]);
        
        return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công!']);
    }
}

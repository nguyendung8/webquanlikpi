<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tasks;
use App\Models\User;
use App\Models\Thongbao; // Thêm import

class TasksController extends Controller
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
        $status = $request->get('status');

        $query = Tasks::with('nguoiDuocGiao');

        // Lọc theo trạng thái
        if ($status) {
            $query->where('Trang_thai', $status);
        }

        // Tìm kiếm theo tên task hoặc người được giao
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('Ten_task', 'like', "%{$search}%")
                  ->orWhere('Mo_ta', 'like', "%{$search}%")
                  ->orWhereHas('nguoiDuocGiao', function($subQ) use ($search) {
                      $subQ->where('Ho_ten', 'like', "%{$search}%");
                  });
            });
        }

        $tasks = $query->orderBy('ID_task', 'desc')->paginate($perPage);

        // Chỉ lấy user có role = 3 (Nhân viên)
        $users = User::where('Trang_thai', 'hoat_dong')
            ->where('ID_quyen', 3)
            ->get();

        return view('manager.tasks.index', compact('tasks', 'search', 'perPage', 'status', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Ten_task' => 'required|string|max:255',
            'Mo_ta' => 'nullable|string',
            'ID_user_duocgiao' => 'required|exists:users,ID_user',
            'Ngay_het_han' => 'nullable|date|after:today'
        ]);

        // Tạo task
        $task = Tasks::create([
            'Ten_task' => $request->Ten_task,
            'Mo_ta' => $request->Mo_ta,
            'ID_user_duocgiao' => $request->ID_user_duocgiao,
            'Ngay_het_han' => $request->Ngay_het_han,
            'Trang_thai' => 'chua_bat_dau'
        ]);

        // Lấy thông tin user được giao task
        $userDuocGiao = User::find($request->ID_user_duocgiao);

        // Thông báo cho user được giao task
        Thongbao::create([
            'ID_nguoigui' => Auth::id(),
            'ID_nguoinhan' => $request->ID_user_duocgiao,
            'Tieu_de' => 'Phân công nhiệm vụ mới',
            'Noi_dung' => "Bạn đã được phân công nhiệm vụ: '{$request->Ten_task}'" .
                         ($request->Ngay_het_han ? ". Hạn hoàn thành: {$request->Ngay_het_han}" : ""),
            'Loai_thongbao' => 'phancong_task',
            'Da_xem' => 0
        ]);

        return response()->json(['success' => true, 'message' => '🎉 Thêm nhiệm vụ thành công!']);
    }

    public function show($id)
    {
        $task = Tasks::with('nguoiDuocGiao')->findOrFail($id);
        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        $task = Tasks::findOrFail($id);

        $request->validate([
            'Ten_task' => 'required|string|max:255',
            'Mo_ta' => 'nullable|string',
            'ID_user_duocgiao' => 'required|exists:users,ID_user',
            'Trang_thai' => 'required|in:chua_bat_dau,dang_thuc_hien,hoan_thanh',
            'Ngay_het_han' => 'nullable|date'
        ]);

        $oldUserId = $task->ID_user_duocgiao;
        $newUserId = $request->ID_user_duocgiao;

        $task->update([
            'Ten_task' => $request->Ten_task,
            'Mo_ta' => $request->Mo_ta,
            'ID_user_duocgiao' => $request->ID_user_duocgiao,
            'Trang_thai' => $request->Trang_thai,
            'Ngay_het_han' => $request->Ngay_het_han
        ]);

        // Nếu thay đổi người được giao, gửi thông báo cho cả 2 người
        if ($oldUserId != $newUserId) {
            // Thông báo cho người cũ (nếu có)
            if ($oldUserId) {
                Thongbao::create([
                    'ID_nguoigui' => Auth::id(),
                    'ID_nguoinhan' => $oldUserId,
                    'Tieu_de' => 'Thay đổi phân công nhiệm vụ',
                    'Noi_dung' => "Nhiệm vụ '{$request->Ten_task}' đã được chuyển cho người khác.",
                    'Loai_thongbao' => 'phancong_task',
                    'Da_xem' => 0
                ]);
            }

            // Thông báo cho người mới
            Thongbao::create([
                'ID_nguoigui' => Auth::id(),
                'ID_nguoinhan' => $newUserId,
                'Tieu_de' => 'Được phân công nhiệm vụ',
                'Noi_dung' => "Bạn đã được phân công nhiệm vụ: '{$request->Ten_task}'" .
                             ($request->Ngay_het_han ? ". Hạn hoàn thành: {$request->Ngay_het_han}" : ""),
                'Loai_thongbao' => 'phancong_task',
                'Da_xem' => 0
            ]);
        }

        return response()->json(['success' => true, 'message' => '✨ Cập nhật nhiệm vụ thành công!']);
    }

    public function destroy($id)
    {
        $task = Tasks::findOrFail($id);
        $task->delete();

        return response()->json(['success' => true, 'message' => '🗑️ Xóa nhiệm vụ thành công!']);
    }
}

<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Tasks;
use App\Models\User;
use App\Models\DulieuTask;
use App\Models\Thongbao;

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

        $query = Tasks::with('users');

        // Lọc theo trạng thái - bây giờ không thể lọc vì trạng thái theo từng user
        // if ($status) {
        //     $query->where('Trang_thai', $status);
        // }

        // Tìm kiếm theo tên task hoặc người được giao
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('Ten_task', 'like', "%{$search}%")
                  ->orWhere('Mo_ta', 'like', "%{$search}%")
                  ->orWhereHas('users', function($subQ) use ($search) {
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
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,ID_user',
            'Ngay_het_han' => 'nullable|date|after:today'
        ], [
            'user_ids.required' => 'Vui lòng chọn ít nhất 1 người được giao',
            'user_ids.min' => 'Vui lòng chọn ít nhất 1 người được giao'
        ]);

        // Tạo task
        $task = Tasks::create([
            'Ten_task' => $request->Ten_task,
            'Mo_ta' => $request->Mo_ta,
            'Ngay_het_han' => $request->Ngay_het_han
        ]);

        // Gán nhiều users cho task với trạng thái mặc định
        $userData = [];
        foreach ($request->user_ids as $userId) {
            $userData[$userId] = ['trang_thai' => 'chua_bat_dau'];
        }
        $task->users()->attach($userData);

        // Thông báo và gửi email cho tất cả users được giao task
        $manager = Auth::user();
        foreach ($request->user_ids as $userId) {
            $user = User::find($userId);

            // Tạo thông báo
            Thongbao::create([
                'ID_nguoigui' => Auth::id(),
                'ID_nguoinhan' => $userId,
                'Tieu_de' => 'Phân công nhiệm vụ mới',
                'Noi_dung' => "Bạn đã được phân công nhiệm vụ: '{$request->Ten_task}'" .
                             ($request->Ngay_het_han ? ". Hạn hoàn thành: {$request->Ngay_het_han}" : ""),
                'Loai_thongbao' => 'phancong_task',
                'Da_xem' => 0
            ]);

            // Gửi email
            if ($user && $user->Email) {
                try {
                    $emailData = [
                        'userName' => $user->Ho_ten,
                        'managerName' => $manager->Ho_ten,
                        'taskName' => $request->Ten_task,
                        'taskDescription' => $request->Mo_ta,
                        'dateAssigned' => now()->format('d/m/Y H:i'),
                        'deadline' => $request->Ngay_het_han ? \Carbon\Carbon::parse($request->Ngay_het_han)->format('d/m/Y') : null,
                        'loginUrl' => url('/login')
                    ];

                    Mail::send('emails.task_assigned', $emailData, function($message) use ($user, $request) {
                        $message->from('dungli1221@gmail.com', 'Hệ Thống Quản Lý KPI');
                        $message->to($user->Email, $user->Ho_ten);
                        $message->subject('Phân Công Nhiệm Vụ Mới: ' . $request->Ten_task);
                    });
                } catch (\Exception $e) {
                    // Log error nhưng không dừng flow
                }
            }
        }

        return response()->json(['success' => true, 'message' => '🎉 Thêm nhiệm vụ và gửi email thành công!']);
    }

    public function show($id)
    {
        $task = Tasks::with('users')->findOrFail($id);
        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        $task = Tasks::findOrFail($id);

        $request->validate([
            'Ten_task' => 'required|string|max:255',
            'Mo_ta' => 'nullable|string',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,ID_user',
            'Ngay_het_han' => 'nullable|date'
        ], [
            'user_ids.required' => 'Vui lòng chọn ít nhất 1 người được giao',
            'user_ids.min' => 'Vui lòng chọn ít nhất 1 người được giao'
        ]);

        $oldUserIds = $task->users->pluck('ID_user')->toArray();
        $newUserIds = $request->user_ids;

        $task->update([
            'Ten_task' => $request->Ten_task,
            'Mo_ta' => $request->Mo_ta,
            'Ngay_het_han' => $request->Ngay_het_han
        ]);

        // Sync users - giữ nguyên trạng thái cũ của users có sẵn, users mới mặc định 'chua_bat_dau'
        $syncData = [];
        foreach ($newUserIds as $userId) {
            $existingPivot = $task->users()->where('ID_user', $userId)->first();
            $syncData[$userId] = [
                'trang_thai' => $existingPivot ? $existingPivot->pivot->trang_thai : 'chua_bat_dau'
            ];
        }
        $task->users()->sync($syncData);

        // Tìm users bị loại bỏ và users mới được thêm
        $removedUsers = array_diff($oldUserIds, $newUserIds);
        $addedUsers = array_diff($newUserIds, $oldUserIds);

        // Thông báo cho users bị loại bỏ
        foreach ($removedUsers as $userId) {
            Thongbao::create([
                'ID_nguoigui' => Auth::id(),
                'ID_nguoinhan' => $userId,
                'Tieu_de' => 'Thay đổi phân công nhiệm vụ',
                'Noi_dung' => "Nhiệm vụ '{$request->Ten_task}' đã được chuyển cho người khác.",
                'Loai_thongbao' => 'phancong_task',
                'Da_xem' => 0
            ]);
        }

        // Thông báo và gửi email cho users mới được thêm
        $manager = Auth::user();
        foreach ($addedUsers as $userId) {
            $user = User::find($userId);

            Thongbao::create([
                'ID_nguoigui' => Auth::id(),
                'ID_nguoinhan' => $userId,
                'Tieu_de' => 'Được phân công nhiệm vụ',
                'Noi_dung' => "Bạn đã được phân công nhiệm vụ: '{$request->Ten_task}'" .
                             ($request->Ngay_het_han ? ". Hạn hoàn thành: {$request->Ngay_het_han}" : ""),
                'Loai_thongbao' => 'phancong_task',
                'Da_xem' => 0
            ]);

            // Gửi email
            if ($user && $user->Email) {
                try {
                    $emailData = [
                        'userName' => $user->Ho_ten,
                        'managerName' => $manager->Ho_ten,
                        'taskName' => $request->Ten_task,
                        'taskDescription' => $request->Mo_ta,
                        'dateAssigned' => now()->format('d/m/Y H:i'),
                        'deadline' => $request->Ngay_het_han ? \Carbon\Carbon::parse($request->Ngay_het_han)->format('d/m/Y') : null,
                        'loginUrl' => url('/login')
                    ];

                    Mail::send('emails.task_assigned', $emailData, function($message) use ($user, $request) {
                        $message->from('dungli1221@gmail.com', 'Hệ Thống Quản Lý KPI');
                        $message->to($user->Email, $user->Ho_ten);
                        $message->subject('Phân Công Nhiệm Vụ: ' . $request->Ten_task);
                    });
                } catch (\Exception $e) {
                }
            }
        }

        return response()->json(['success' => true, 'message' => '✨ Cập nhật nhiệm vụ và gửi email thành công!']);
    }

    public function destroy($id)
    {
        $task = Tasks::findOrFail($id);
        $task->delete();

        return response()->json(['success' => true, 'message' => '🗑️ Xóa nhiệm vụ thành công!']);
    }

    public function viewTaskSubmissions($id)
    {
        $task = Tasks::with(['users' => function($q) {
            $q->where('ID_quyen', 3); // Chỉ lấy nhân viên
        }])->findOrFail($id);

        // Lấy thông tin từng user và bài nộp của họ
        $submissions = $task->users->map(function($user) use ($id) {
            $userSubmissions = DulieuTask::where('task_id', $id)
                ->where('user_id', $user->ID_user)
                ->orderBy('Ngay_gui', 'desc')
                ->get();

            return [
                'user_id' => $user->ID_user,
                'user_name' => $user->Ho_ten,
                'trang_thai' => $user->pivot->trang_thai,
                'comment' => $user->pivot->comment,
                'submissions' => $userSubmissions,
                'has_submission' => $userSubmissions->count() > 0
            ];
        });

        return response()->json([
            'success' => true,
            'task' => $task,
            'submissions' => $submissions
        ]);
    }

    public function approveTask(Request $request, $taskId, $userId)
    {
        $request->validate([
            'comment' => 'nullable|string'
        ]);

        $task = Tasks::findOrFail($taskId);

        $task->users()->updateExistingPivot($userId, [
            'trang_thai' => 'hoan_thanh',
            'comment' => $request->comment
        ]);

        // Thông báo cho user
        Thongbao::create([
            'ID_nguoigui' => Auth::id(),
            'ID_nguoinhan' => $userId,
            'Tieu_de' => 'Nhiệm vụ được duyệt',
            'Noi_dung' => "Bài nộp của bạn cho nhiệm vụ '{$task->Ten_task}' đã được duyệt và hoàn thành!" .
                         ($request->comment ? "\nNhận xét: {$request->comment}" : ""),
            'Loai_thongbao' => 'phancong_task',
            'Da_xem' => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => '✅ Duyệt nhiệm vụ thành công!'
        ]);
    }

    public function rejectTask(Request $request, $taskId, $userId)
    {
        $request->validate([
            'comment' => 'required|string'
        ], [
            'comment.required' => 'Vui lòng nhập lý do yêu cầu làm lại'
        ]);

        $task = Tasks::findOrFail($taskId);

        // Chỉ cập nhật trạng thái và comment, không xóa lịch sử nộp bài
        $task->users()->updateExistingPivot($userId, [
            'trang_thai' => 'chua_bat_dau',
            'comment' => $request->comment
        ]);

        // Thông báo cho user
        Thongbao::create([
            'ID_nguoigui' => Auth::id(),
            'ID_nguoinhan' => $userId,
            'Tieu_de' => 'Yêu cầu làm lại nhiệm vụ',
            'Noi_dung' => "Nhiệm vụ '{$task->Ten_task}' cần làm lại.\nLý do: {$request->comment}",
            'Loai_thongbao' => 'phancong_task',
            'Da_xem' => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => '📝 Đã yêu cầu nhân viên làm lại!'
        ]);
    }

    public function downloadSubmissionFile($dulieuId)
    {
        $dulieu = DulieuTask::findOrFail($dulieuId);

        if (!$dulieu->File_path) {
            return response()->json(['error' => 'File không tồn tại'], 404);
        }

        return Storage::disk('public')->download($dulieu->File_path, $dulieu->File_name);
    }
}

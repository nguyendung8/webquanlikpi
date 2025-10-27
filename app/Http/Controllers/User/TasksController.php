<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\Tasks;
use App\Models\DulieuTask;
use App\Models\Thongbao;
use App\Models\User;

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
        $status = $request->get('status');
        $perPage = $request->get('per_page', 10);

        $query = Tasks::with(['users' => function($q) use ($userId) {
            $q->where('ID_user', $userId);
        }])->whereHas('users', function($q) use ($userId) {
            $q->where('ID_user', $userId);
        });

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('Ten_task', 'like', "%{$search}%")
                  ->orWhere('Mo_ta', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->whereHas('users', function($q) use ($userId, $status) {
                $q->where('ID_user', $userId)
                  ->where('task_user.trang_thai', $status);
            });
        }

        // Paginate tasks
        $tasks = $query->orderBy('Ngay_giao', 'desc')->paginate($perPage);

        // Attach trạng thái của user vào mỗi task
        $tasks->each(function($task) use ($userId) {
            $user = $task->users->first();
            $task->user_status = $user ? $user->pivot->trang_thai : 'chua_bat_dau';
            $task->user_pivot = $user ? $user->pivot : null;
        });

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

        return view('user.tasks.index', compact('tasks', 'taskStats', 'search', 'status', 'perPage'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'Trang_thai' => 'required|in:chua_bat_dau,dang_thuc_hien,hoan_thanh'
        ]);

        $userId = Auth::id();
        $task = Tasks::whereHas('users', function($q) use ($userId) {
            $q->where('ID_user', $userId);
        })->findOrFail($id);

        // Cập nhật trạng thái trong pivot table cho user hiện tại
        $task->users()->updateExistingPivot($userId, ['trang_thai' => $request->Trang_thai]);

        return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công!']);
    }

    public function submitTask(Request $request, $id)
    {
        $request->validate([
            'minh_chung' => 'required|string',
            'file' => 'nullable|file|max:10240'
        ], [
            'minh_chung.required' => 'Vui lòng nhập minh chứng'
        ]);

        $userId = Auth::id();
        $task = Tasks::whereHas('users', function($q) use ($userId) {
            $q->where('ID_user', $userId);
        })->findOrFail($id);

        $submissionData = [
            'task_id' => $id,
            'user_id' => $userId,
            'Minh_chung' => $request->minh_chung,
            'Ngay_gui' => now()
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('tasks', $fileName, 'public');

            $submissionData['File_name'] = $fileName;
            $submissionData['File_path'] = $filePath;
        }

        // Tạo bản ghi mới trong dulieu_task
        DulieuTask::create($submissionData);

        // Cập nhật trạng thái trong pivot table
        $task->users()->updateExistingPivot($userId, [
            'trang_thai' => 'dang_thuc_hien'
        ]);

        // Gửi email cho các quản lý (role = 2)
        $currentUser = Auth::user();
        $managers = User::where('ID_quyen', 2)
            ->where('Trang_thai', 'hoat_dong')
            ->get();

        foreach ($managers as $manager) {
            if ($manager->Email) {
                try {
                    $emailData = [
                        'managerName' => $manager->Ho_ten,
                        'userName' => $currentUser->Ho_ten,
                        'taskName' => $task->Ten_task,
                        'submitDate' => now()->format('d/m/Y H:i'),
                        'evidenceText' => $request->minh_chung,
                        'hasFile' => $request->hasFile('file'),
                        'loginUrl' => url('/login')
                    ];

                    Mail::send('emails.task_submitted', $emailData, function($message) use ($manager, $task) {
                        $message->from('dungli1221@gmail.com', 'Hệ Thống Quản Lý KPI');
                        $message->to($manager->Email, $manager->Ho_ten);
                        $message->subject('Nhân viên đã nộp bài: ' . $task->Ten_task);
                    });
                } catch (\Exception $e) {
                    // Log error nhưng không dừng flow
                    \Log::error('Lỗi gửi email submit task: ' . $e->getMessage());
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => '✅ Nộp bài thành công! Quản lý đã được thông báo qua email.'
        ]);
    }

    public function viewSubmission($id)
    {
        $userId = Auth::id();
        $task = Tasks::with(['users' => function($q) use ($userId) {
            $q->where('ID_user', $userId);
        }])->whereHas('users', function($q) use ($userId) {
            $q->where('ID_user', $userId);
        })->findOrFail($id);

        // Lấy tất cả bài nộp của user cho task này
        $submissions = DulieuTask::where('task_id', $id)
            ->where('user_id', $userId)
            ->orderBy('Ngay_gui', 'desc')
            ->get();

        $user = $task->users->first();
        $pivot = $user ? $user->pivot : null;

        return response()->json([
            'success' => true,
            'task' => $task,
            'submissions' => $submissions,
            'pivot' => $pivot
        ]);
    }

    public function downloadFile($dulieuId)
    {
        $userId = Auth::id();
        $dulieu = DulieuTask::where('ID_dulieu', $dulieuId)
            ->where('user_id', $userId)
            ->firstOrFail();

        if (!$dulieu->File_path) {
            return response()->json(['error' => 'File không tồn tại'], 404);
        }

        return Storage::disk('public')->download($dulieu->File_path, $dulieu->File_name);
    }
}

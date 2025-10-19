<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\Manager\LoaiKpiController;
use App\Http\Controllers\Manager\KpiController as ManagerKpiController;
use App\Http\Controllers\Manager\TasksController as ManagerTasksController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\TasksController as UserTasksController;
use App\Http\Controllers\User\CalendarController as UserCalendarController;
use App\Http\Controllers\KpiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PhongbanController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\User\KpiController as UserKpiController;
use App\Models\Thongbao;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Redirect dashboard theo role
    Route::get('/dashboard', function () {
        $user = Auth::user();
        switch ($user->ID_quyen) {
            case 1: // Admin
                return redirect()->route('dashboard.index');
            case 2: // Manager
                return redirect()->route('manager.dashboard.index');
            case 3: // Employee
                return redirect()->route('user.dashboard.index');
            default:
                return redirect()->route('dashboard.index');
        }
    })->name('dashboard');

    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::resource('kpi', KpiController::class);
        Route::resource('users', UserController::class);
        Route::resource('phongban', PhongbanController::class);
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
        Route::get('/activity', [ActivityController::class, 'index'])->name('activity.index');
    });

    // Manager routes
    Route::prefix('manager')->middleware(['auth', 'manager'])->group(function () {
        Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('manager.dashboard.index');

        // KPI Management
        Route::resource('kpi', ManagerKpiController::class)->names([
            'index' => 'manager.kpi.index',
            'store' => 'manager.kpi.store',
            'destroy' => 'manager.kpi.destroy'
        ]);

        Route::post('kpi/{id}/progress', [ManagerKpiController::class, 'updateProgress'])->name('manager.kpi.progress');
        Route::get('kpi/{id}/submissions', [ManagerKpiController::class, 'viewSubmissions'])->name('manager.kpi.submissions');
        Route::post('kpi/{id}/evaluation', [ManagerKpiController::class, 'updateEvaluation'])->name('manager.kpi.evaluation');
        Route::get('/kpi/file/{id}/download', [ManagerKpiController::class, 'downloadFile'])->name('manager.kpi.download');

        // LoaiKpi Management
        Route::resource('kpi-type', \App\Http\Controllers\Manager\LoaiKpiController::class)->names([
            'index' => 'manager.kpi_type.index',
            'store' => 'manager.kpi_type.store',
            'update' => 'manager.kpi_type.update',
            'destroy' => 'manager.kpi_type.destroy'
        ]);

        // Tasks Management
        Route::resource('tasks', \App\Http\Controllers\Manager\TasksController::class)->names([
            'index' => 'manager.tasks.index',
            'store' => 'manager.tasks.store',
            'update' => 'manager.tasks.update',
            'destroy' => 'manager.tasks.destroy'
        ]);
    });

    // Employee routes
    Route::prefix('user')->middleware(['auth', 'employee:employee'])->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard.index');

        // KPI Management
        Route::get('/kpi', [UserKpiController::class, 'index'])->name('user.kpi.index');
        Route::post('/kpi/{id}/submit', [UserKpiController::class, 'submit'])->name('user.kpi.submit');
        Route::get('/kpi/file/{id}/download', [UserKpiController::class, 'downloadFile'])->name('user.kpi.download');
        Route::get('/kpi/{id}/submissions', [UserKpiController::class, 'viewSubmissions'])->name('user.kpi.submissions');

        // Tasks Management
        Route::get('/tasks', [UserTasksController::class, 'index'])->name('user.tasks.index');
        Route::post('/tasks/{id}/status', [UserTasksController::class, 'updateStatus'])->name('user.tasks.status');

        // Calendar
        Route::get('/calendar', [UserCalendarController::class, 'index'])->name('user.calendar.index');
    });

    // Thêm routes cho thông báo
    Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
});

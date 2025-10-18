<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\Manager\LoaiKpiController;
use App\Http\Controllers\Manager\KpiController as ManagerKpiController;
use App\Http\Controllers\Manager\TasksController as ManagerTasksController;
use App\Http\Controllers\KpiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PhongbanController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ActivityController;

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
                return redirect()->route('my-kpi.index');
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
    Route::middleware('auth')->group(function () {
        Route::get('/my-kpi', function () {
            return view('my-kpi.index');
        })->name('my-kpi.index');

        Route::get('/tasks', function () {
            return view('tasks.index');
        })->name('tasks.index');
    });
});

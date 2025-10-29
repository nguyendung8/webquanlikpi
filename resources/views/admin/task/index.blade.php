@extends('layouts.dashboard')

@section('title', 'Danh sách Tasks')
@section('page-title', 'Quản lý Tasks')
@section('search-placeholder', 'Tìm task, người thực hiện...')

@push('styles')
<style>
    .chart-container {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .chart-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chart-title i {
        color: #667eea;
    }

    .table-container {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .table-title {
        font-size: 20px;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
    }

    .controls {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .per-page-select {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .per-page-select select {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 5px 10px;
        font-size: 14px;
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .search-box input {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 8px 12px;
        font-size: 14px;
        width: 200px;
    }

    .table {
        margin-bottom: 0;
    }

    .table th {
        background: #f8f9fa;
        border: none;
        font-weight: 600;
        color: #495057;
        padding: 15px 12px;
        font-size: 14px;
    }

    .table td {
        border: none;
        padding: 15px 12px;
        font-size: 14px;
        vertical-align: middle;
    }

    .table tbody tr {
        border-bottom: 1px solid #f1f3f4;
    }

    .table tbody tr:hover {
        background: #f8f9fa;
    }

    .status-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-chua_bat_dau {
        background: #6c757d;
        color: white;
    }

    .status-dang_thuc_hien {
        background: #17a2b8;
        color: white;
    }

    .status-hoan_thanh {
        background: #28a745;
        color: white;
    }

    .pagination-info {
        color: #6c757d;
        font-size: 14px;
    }

    .pagination {
        margin: 0;
    }

    .page-link {
        border: 1px solid #dee2e6;
        color: #667eea;
        padding: 8px 12px;
    }

    .page-link:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .page-item.active .page-link {
        background: #667eea;
        border-color: #667eea;
    }

    .pagination .page-link svg {
        width: 14px !important;
        height: 14px !important;
        display: inline-block !important;
        vertical-align: middle !important;
    }

    .users-list {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .user-badge {
        padding: 4px 8px;
        background: #e9ecef;
        border-radius: 12px;
        font-size: 12px;
        color: #495057;
    }
</style>
@endpush

@section('content')
<!-- Biểu đồ Tasks tạo mới theo tháng -->
<div class="row">
    <div class="col-md-6">
        <div class="chart-container">
            <h3 class="chart-title">
                <i class="fas fa-chart-line"></i>
                Tasks tạo mới theo tháng
            </h3>
            <canvas id="taskByMonthChart" width="400" height="200"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="chart-container">
            <h3 class="chart-title">
                <i class="fas fa-chart-pie"></i>
                Tasks theo trạng thái
            </h3>
            <canvas id="taskByStatusChart" width="400" height="200"></canvas>
        </div>
    </div>
</div>

<!-- Danh sách Tasks -->
<div class="table-container">
    <div class="table-header">
        <h2 class="table-title">Danh sách Tasks</h2>
        <div class="controls">
            <div class="per-page-select">
                <span>Xem</span>
                <select onchange="changePerPage(this.value)">
                    <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                </select>
                <span>mục</span>
            </div>

            <div class="search-box">
                <span>Tìm kiếm:</span>
                <form method="GET" style="display: flex; gap: 10px;">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Tìm theo tên task, mô tả...">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-search"></i>
                    </button>
                    @if($search)
                        <a href="{{ route('task.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50px;">
                        <i class="fas fa-sort"></i> STT
                    </th>
                    <th>
                        <i class="fas fa-sort"></i> Tên Task
                    </th>
                    <th>
                        <i class="fas fa-sort"></i> Mô tả
                    </th>
                    <th>
                        <i class="fas fa-sort"></i> Người thực hiện
                    </th>
                    <th>
                        <i class="fas fa-sort"></i> Trạng thái
                    </th>
                    <th>
                        <i class="fas fa-sort"></i> Ngày giao
                    </th>
                    <th>
                        <i class="fas fa-sort"></i> Ngày hết hạn
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $index => $task)
                    <tr>
                        <td>{{ $tasks->firstItem() + $index }}</td>
                        <td>{{ $task->Ten_task }}</td>
                        <td>{{ Str::limit($task->Mo_ta ?? 'Không có mô tả', 50) }}</td>
                        <td>
                            <div class="users-list">
                                @foreach($task->users as $user)
                                    <span class="user-badge">{{ $user->Ho_ten }}</span>
                                @endforeach
                                @if($task->users->count() == 0)
                                    <span class="text-muted">Chưa phân công</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @php
                                $statusCounts = $task->users->groupBy('pivot.trang_thai')->map->count();
                                $totalUsers = $task->users->count();
                                $completed = $statusCounts->get('hoan_thanh', 0);
                                $inProgress = $statusCounts->get('dang_thuc_hien', 0);
                                $notStarted = $statusCounts->get('chua_bat_dau', 0);

                                // Xác định trạng thái chung
                                if ($totalUsers == 0) {
                                    $mainStatus = 'chua_bat_dau';
                                } elseif ($completed == $totalUsers) {
                                    $mainStatus = 'hoan_thanh';
                                } elseif ($inProgress > 0) {
                                    $mainStatus = 'dang_thuc_hien';
                                } else {
                                    $mainStatus = 'chua_bat_dau';
                                }
                            @endphp
                            <span class="status-badge status-{{ $mainStatus }}">
                                {{ ucfirst(str_replace('_', ' ', $mainStatus)) }}
                            </span>
                            @if($totalUsers > 0)
                                <br><small class="text-muted">
                                    ({{ $completed }}/{{ $totalUsers }} hoàn thành)
                                </small>
                            @endif
                        </td>
                        <td>{{ $task->Ngay_giao ? $task->Ngay_giao->format('d/m/Y H:i') : 'N/A' }}</td>
                        <td>{{ $task->Ngay_het_han ? $task->Ngay_het_han->format('d/m/Y') : 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Không có dữ liệu</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="pagination-info">
            Đang xem {{ $tasks->firstItem() ?? 0 }} đến {{ $tasks->lastItem() ?? 0 }}
            trong tổng số {{ $tasks->total() }} mục
        </div>
        <div>
            {{ $tasks->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ Tasks theo tháng
    const taskByMonthData = @json($taskByMonth);
    const monthLabels = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];
    const monthData = new Array(12).fill(0);

    taskByMonthData.forEach(item => {
        monthData[item.month - 1] = item.count;
    });

    const monthCtx = document.getElementById('taskByMonthChart').getContext('2d');
    new Chart(monthCtx, {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Số Tasks tạo mới',
                data: monthData,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Biểu đồ Tasks theo trạng thái
    const taskByStatusData = @json($taskByStatus);
    const statusLabels = taskByStatusData.map(item => {
        const labels = {
            'chua_bat_dau': 'Chưa bắt đầu',
            'dang_thuc_hien': 'Đang thực hiện',
            'hoan_thanh': 'Hoàn thành'
        };
        return labels[item.trang_thai] || item.trang_thai;
    });
    const statusCounts = taskByStatusData.map(item => item.count);
    const colors = ['#6c757d', '#17a2b8', '#28a745'];

    const statusCtx = document.getElementById('taskByStatusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusCounts,
                backgroundColor: colors.slice(0, statusLabels.length),
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    function changePerPage(value) {
        const url = new URL(window.location);
        url.searchParams.set('per_page', value);
        window.location.href = url.toString();
    }
</script>
@endpush


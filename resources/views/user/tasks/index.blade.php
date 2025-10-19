@extends('layouts.dashboard')

@section('title', 'Nhiệm vụ')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.css" rel="stylesheet">
<style>
    .kanban-board {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        padding: 20px 0;
    }

    .kanban-column {
        min-width: 300px;
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        flex-shrink: 0;
    }

    .kanban-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 15px;
        padding: 10px;
        border-radius: 8px;
        color: white;
        font-weight: 600;
    }

    .kanban-header.not-started {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    }

    .kanban-header.in-progress {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    }

    .kanban-header.completed {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .task-count {
        background: rgba(255, 255, 255, 0.2);
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
    }

    .task-card {
        background: white;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        cursor: move;
        transition: all 0.3s;
        border-left: 4px solid #667eea;
    }

    .task-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .task-card.dragging {
        opacity: 0.5;
        transform: rotate(5deg);
    }

    .task-title {
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .task-description {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .task-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 11px;
        color: #6c757d;
    }

    .task-date {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .priority-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .priority-high { background: #dc3545; }
    .priority-medium { background: #ffc107; }
    .priority-low { background: #6c757d; }

    .stats-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }

    .stats-number {
        font-size: 32px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .stats-label {
        font-size: 14px;
        color: #6c757d;
        font-weight: 500;
    }

    .empty-column {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
        font-style: italic;
    }

    .empty-column i {
        font-size: 48px;
        margin-bottom: 10px;
        opacity: 0.5;
    }

    .search-filter-section {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="stats-card">
                <h4 class="mb-0">
                    <i class="fas fa-tasks text-primary me-2"></i>
                    Nhiệm vụ của tôi
                </h4>
                <p class="text-muted mb-0">Quản lý và theo dõi các nhiệm vụ được giao</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card text-center">
                <div class="stats-number">{{ $taskStats['total'] }}</div>
                <div class="stats-label">Tổng nhiệm vụ</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card text-center">
                <div class="stats-number text-success">{{ $taskStats['completed'] }}</div>
                <div class="stats-label">Hoàn thành</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card text-center">
                <div class="stats-number text-warning">{{ $taskStats['in_progress'] }}</div>
                <div class="stats-label">Đang thực hiện</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card text-center">
                <div class="stats-number text-muted">{{ $taskStats['not_started'] }}</div>
                <div class="stats-label">Chưa bắt đầu</div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="search-filter-section">
        <div class="row">
            <div class="col-md-6">
                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2"
                           placeholder="Tìm kiếm nhiệm vụ..." value="{{ $search }}">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            <div class="col-md-3">
                <select class="form-select" onchange="changePerPage(this.value)">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 bản ghi/trang</option>
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 bản ghi/trang</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 bản ghi/trang</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="kanban-board">
        <!-- Chưa bắt đầu -->
        <div class="kanban-column">
            <div class="kanban-header not-started">
                <div>
                    <i class="fas fa-clock me-2"></i>
                    Chưa bắt đầu
                </div>
                <div class="task-count">{{ $tasks->where('Trang_thai', 'chua_bat_dau')->count() }}</div>
            </div>
            <div id="not-started-column" class="sortable-column">
                @forelse($tasks->where('Trang_thai', 'chua_bat_dau') as $task)
                <div class="task-card" data-task-id="{{ $task->ID_task }}" data-status="chua_bat_dau">
                    <div class="task-title">{{ $task->Ten_task }}</div>
                    @if($task->Mo_ta)
                    <div class="task-description">{{ Str::limit($task->Mo_ta, 80) }}</div>
                    @endif
                    <div class="task-meta">
                        <div class="task-date">
                            <i class="fas fa-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($task->Ngay_giao)->format('d/m/Y') }}
                        </div>
                        @if($task->Ngay_het_han)
                        <div class="task-date">
                            <i class="fas fa-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($task->Ngay_het_han)->format('d/m') }}
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="empty-column">
                    <i class="fas fa-inbox"></i>
                    <p>Không có nhiệm vụ</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Đang thực hiện -->
        <div class="kanban-column">
            <div class="kanban-header in-progress">
                <div>
                    <i class="fas fa-play me-2"></i>
                    Đang thực hiện
                </div>
                <div class="task-count">{{ $tasks->where('Trang_thai', 'dang_thuc_hien')->count() }}</div>
            </div>
            <div id="in-progress-column" class="sortable-column">
                @forelse($tasks->where('Trang_thai', 'dang_thuc_hien') as $task)
                <div class="task-card" data-task-id="{{ $task->ID_task }}" data-status="dang_thuc_hien">
                    <div class="task-title">{{ $task->Ten_task }}</div>
                    @if($task->Mo_ta)
                    <div class="task-description">{{ Str::limit($task->Mo_ta, 80) }}</div>
                    @endif
                    <div class="task-meta">
                        <div class="task-date">
                            <i class="fas fa-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($task->Ngay_giao)->format('d/m/Y') }}
                        </div>
                        @if($task->Ngay_het_han)
                        <div class="task-date">
                            <i class="fas fa-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($task->Ngay_het_han)->format('d/m') }}
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="empty-column">
                    <i class="fas fa-play"></i>
                    <p>Không có nhiệm vụ</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Hoàn thành -->
        <div class="kanban-column">
            <div class="kanban-header completed">
                <div>
                    <i class="fas fa-check me-2"></i>
                    Hoàn thành
                </div>
                <div class="task-count">{{ $tasks->where('Trang_thai', 'hoan_thanh')->count() }}</div>
            </div>
            <div id="completed-column" class="sortable-column">
                @forelse($tasks->where('Trang_thai', 'hoan_thanh') as $task)
                <div class="task-card" data-task-id="{{ $task->ID_task }}" data-status="hoan_thanh">
                    <div class="task-title">{{ $task->Ten_task }}</div>
                    @if($task->Mo_ta)
                    <div class="task-description">{{ Str::limit($task->Mo_ta, 80) }}</div>
                    @endif
                    <div class="task-meta">
                        <div class="task-date">
                            <i class="fas fa-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($task->Ngay_giao)->format('d/m/Y') }}
                        </div>
                        @if($task->Ngay_het_han)
                        <div class="task-date">
                            <i class="fas fa-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($task->Ngay_het_han)->format('d/m') }}
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="empty-column">
                    <i class="fas fa-check"></i>
                    <p>Không có nhiệm vụ</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize sortable for each column
        const notStartedColumn = document.getElementById('not-started-column');
        const inProgressColumn = document.getElementById('in-progress-column');
        const completedColumn = document.getElementById('completed-column');

        // Not Started Column - can move to In Progress
        new Sortable(notStartedColumn, {
            group: 'tasks',
            animation: 150,
            ghostClass: 'dragging',
            onEnd: function(evt) {
                const taskId = evt.item.dataset.taskId;
                const newStatus = evt.to.id === 'in-progress-column' ? 'dang_thuc_hien' : 'chua_bat_dau';

                if (evt.to.id === 'in-progress-column') {
                    updateTaskStatus(taskId, newStatus);
                } else {
                    // Revert if moved to wrong column
                    evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex]);
                }
            }
        });

        // In Progress Column - can move to Completed or back to Not Started
        new Sortable(inProgressColumn, {
            group: 'tasks',
            animation: 150,
            ghostClass: 'dragging',
            onEnd: function(evt) {
                const taskId = evt.item.dataset.taskId;
                let newStatus = 'dang_thuc_hien';

                if (evt.to.id === 'completed-column') {
                    newStatus = 'hoan_thanh';
                } else if (evt.to.id === 'not-started-column') {
                    newStatus = 'chua_bat_dau';
                }

                if (evt.to.id !== 'in-progress-column') {
                    updateTaskStatus(taskId, newStatus);
                } else {
                    // Revert if moved to wrong column
                    evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex]);
                }
            }
        });

        // Completed Column - can only move back to In Progress
        new Sortable(completedColumn, {
            group: 'tasks',
            animation: 150,
            ghostClass: 'dragging',
            onEnd: function(evt) {
                const taskId = evt.item.dataset.taskId;

                if (evt.to.id === 'in-progress-column') {
                    updateTaskStatus(taskId, 'dang_thuc_hien');
                } else {
                    // Revert if moved to wrong column
                    evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex]);
                }
            }
        });
    });

    function changePerPage(value) {
        const url = new URL(window.location);
        url.searchParams.set('per_page', value);
        window.location.href = url.toString();
    }

    function updateTaskStatus(taskId, status) {
        fetch(`/user/tasks/${taskId}/status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ Trang_thai: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                // Update task count
                updateTaskCounts();
            } else {
                showAlert(data.message, 'error');
                // Reload page to revert changes
                location.reload();
            }
        })
        .catch(error => {
            showAlert('Có lỗi xảy ra khi cập nhật trạng thái', 'error');
            // Reload page to revert changes
            location.reload();
        });
    }

    function updateTaskCounts() {
        // Update task counts in headers
        const notStartedCount = document.querySelectorAll('#not-started-column .task-card').length;
        const inProgressCount = document.querySelectorAll('#in-progress-column .task-card').length;
        const completedCount = document.querySelectorAll('#completed-column .task-card').length;

        document.querySelector('.kanban-header.not-started .task-count').textContent = notStartedCount;
        document.querySelector('.kanban-header.in-progress .task-count').textContent = inProgressCount;
        document.querySelector('.kanban-header.completed .task-count').textContent = completedCount;
    }

    function showAlert(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';

        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed"
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                <i class="${iconClass} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        // Remove existing alerts first
        const existingAlerts = document.querySelectorAll('.alert.position-fixed');
        existingAlerts.forEach(alert => alert.remove());

        // Insert new alert
        document.body.insertAdjacentHTML('beforeend', alertHtml);

        // Auto remove after 3 seconds
        setTimeout(() => {
            const alert = document.querySelector('.alert.position-fixed');
            if (alert) {
                alert.remove();
            }
        }, 3000);
    }
</script>
@endpush

@extends('layouts.dashboard')

@section('title', 'Nhiệm vụ')

@push('styles')
<style>
    .task-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border-left: 4px solid #667eea;
        transition: transform 0.3s;
        cursor: pointer;
    }

    .task-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .task-card-header {
        pointer-events: none;
    }

    .task-card .btn {
        pointer-events: auto;
    }

    .task-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .task-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .task-deadline {
        font-size: 12px;
        color: #dc3545;
        font-weight: 500;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-chua_bat_dau { background: #f8f9fa; color: #6c757d; }
    .status-dang_thuc_hien { background: #fff3cd; color: #856404; }
    .status-hoan_thanh { background: #d4edda; color: #155724; }

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

    .submission-item {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background: #f8f9fa;
    }

    .submission-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .submission-date {
        font-size: 12px;
        color: #6c757d;
        font-weight: 500;
    }

    .comment-badge {
        background: #fff3cd;
        color: #856404;
        padding: 8px 12px;
        border-radius: 8px;
        margin-top: 10px;
        display: inline-block;
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
    <div class="row mb-3">
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
            <select class="form-select" onchange="filterByStatus(this.value)">
                <option value="">Tất cả trạng thái</option>
                <option value="chua_bat_dau" {{ $status == 'chua_bat_dau' ? 'selected' : '' }}>Chưa bắt đầu</option>
                <option value="dang_thuc_hien" {{ $status == 'dang_thuc_hien' ? 'selected' : '' }}>Đang thực hiện</option>
                <option value="hoan_thanh" {{ $status == 'hoan_thanh' ? 'selected' : '' }}>Hoàn thành</option>
            </select>
        </div>
            <div class="col-md-3">
                <select class="form-select" onchange="changePerPage(this.value)">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 bản ghi/trang</option>
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 bản ghi/trang</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 bản ghi/trang</option>
                </select>
            </div>
        </div>

    <!-- Task List -->
    <div class="row">
        @forelse($tasks as $task)
        <div class="col-md-6 mb-3">
            <div class="task-card" onclick="viewTaskDetail({{ $task->ID_task }})">
                <div class="task-title task-card-header">{{ $task->Ten_task }}</div>

                <div class="task-meta">
                <div>
                            <i class="fas fa-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($task->Ngay_giao)->format('d/m/Y') }}
                        </div>
                        @if($task->Ngay_het_han)
                    <div class="task-deadline">
                            <i class="fas fa-clock me-1"></i>
                        Hạn: {{ \Carbon\Carbon::parse($task->Ngay_het_han)->format('d/m/Y') }}
                    </div>
                    @endif
                </div>

                @if($task->Mo_ta)
                <p class="text-muted mb-3">{{ Str::limit($task->Mo_ta, 100) }}</p>
                @endif

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="status-badge status-{{ $task->user_status }}">
                            @if($task->user_status == 'chua_bat_dau')
                                Chưa bắt đầu
                            @elseif($task->user_status == 'dang_thuc_hien')
                                Đang thực hiện
                            @else
                                Hoàn thành
                            @endif
                        </span>
                        @if($task->user_pivot && $task->user_pivot->comment)
                        <span class="badge bg-warning ms-2">
                            <i class="fas fa-comment me-1"></i>Có phản hồi
                        </span>
                        @endif
                        </div>
                    <div class="d-flex gap-2">
                        @if($task->user_status == 'hoan_thanh')
                            <button class="btn btn-success btn-sm" disabled>
                                <i class="fas fa-check-circle me-1"></i> Đã hoàn thành
                            </button>
                        @else
                            <button class="btn btn-primary btn-sm" onclick="openSubmitModal({{ $task->ID_task }}, '{{ $task->Ten_task }}')">
                                <i class="fas fa-upload me-1"></i> Nộp bài
                            </button>
                        @endif
                    </div>
                </div>
                    </div>
                </div>
                @empty
        <div class="col-12">
            <div class="stats-card text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Chưa có nhiệm vụ nào được giao</h5>
                <p class="text-muted">Bạn sẽ thấy các nhiệm vụ được phân công ở đây</p>
            </div>
        </div>
        @endforelse
        </div>

    <!-- Pagination -->
    @if($tasks->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $tasks->appends(request()->query())->links() }}
    </div>
    @endif
                </div>

<!-- Submit Modal -->
<div class="modal fade" id="submitModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-upload text-primary me-2"></i>
                    Nộp bài nhiệm vụ
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="submitForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="task_id">

                    <div class="mb-3">
                        <label class="form-label">Nhiệm vụ</label>
                        <input type="text" class="form-control" id="task_name" readonly>
                        </div>

                    <div class="mb-3">
                        <label class="form-label">Minh chứng <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="minh_chung" rows="3"
                                  placeholder="Mô tả chi tiết về kết quả thực hiện..." required></textarea>
                        </div>

                    <div class="mb-3">
                        <label class="form-label">File đính kèm</label>
                        <input type="file" class="form-control" id="file_upload"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                        <small class="form-text text-muted">
                            Hỗ trợ: PDF, Word, Excel, hình ảnh (tối đa 10MB)
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i> Nộp bài
                    </button>
                </div>
            </form>
        </div>
    </div>
                </div>

<!-- Task Detail Modal -->
<div class="modal fade" id="taskDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-tasks me-2"></i>
                    Chi tiết nhiệm vụ
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="taskDetailContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function filterByStatus(status) {
        const url = new URL(window.location);
        if (status) {
            url.searchParams.set('status', status);
                } else {
            url.searchParams.delete('status');
        }
        window.location.href = url.toString();
    }

    function changePerPage(value) {
        const url = new URL(window.location);
        url.searchParams.set('per_page', value);
        window.location.href = url.toString();
    }

    function openSubmitModal(id, name) {
        event.stopPropagation();

        document.getElementById('task_id').value = id;
        document.getElementById('task_name').value = name;

        // Reset form
        document.getElementById('submitForm').reset();
        document.getElementById('task_id').value = id;
        document.getElementById('task_name').value = name;

        new bootstrap.Modal(document.getElementById('submitModal')).show();
    }

    function viewTaskDetail(id) {
        const modal = new bootstrap.Modal(document.getElementById('taskDetailModal'));
        modal.show();

        fetch(`/user/tasks/${id}/submission`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayTaskDetail(data.task, data.submissions, data.pivot);
                } else {
                    showAlert('Không thể tải thông tin nhiệm vụ', 'error');
                }
            })
            .catch(error => {
                showAlert('Có lỗi xảy ra khi tải thông tin nhiệm vụ', 'error');
            });
    }

    function displayTaskDetail(task, submissions, pivot) {
        const content = document.getElementById('taskDetailContent');
        const createdDate = new Date(task.Ngay_giao).toLocaleDateString('vi-VN');
        const deadlineDate = task.Ngay_het_han ? new Date(task.Ngay_het_han).toLocaleDateString('vi-VN') : 'Không có';

        let html = `
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="mb-3"><i class="fas fa-tasks me-2 text-info"></i>${task.Ten_task}</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong><i class="fas fa-align-left me-2"></i>Mô tả:</strong></p>
                            <p class="text-muted">${task.Mo_ta || 'Không có mô tả'}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong><i class="fas fa-info-circle me-2"></i>Trạng thái:</strong></p>
                            <span class="status-badge status-${pivot ? pivot.trang_thai : 'chua_bat_dau'}">
                                ${getStatusText(pivot ? pivot.trang_thai : 'chua_bat_dau')}
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong><i class="fas fa-calendar me-2"></i>Ngày giao:</strong></p>
                            <p class="text-muted">${createdDate}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong><i class="fas fa-clock me-2"></i>Hạn hoàn thành:</strong></p>
                            <p class="text-muted">${deadlineDate}</p>
                        </div>
                    </div>

                    ${pivot && pivot.comment ? `
                        <div class="alert alert-warning mt-3">
                            <strong><i class="fas fa-comment me-2"></i>Phản hồi từ quản lý:</strong>
                            <p class="mb-0 mt-2">${pivot.comment}</p>
                        </div>
                    ` : ''}
                </div>
            </div>
        `;

        // Hiển thị lịch sử bài nộp
        html += '<div class="card border-0 shadow-sm"><div class="card-body">';
        html += `<h6 class="text-muted mb-3"><i class="fas fa-file-upload me-2"></i>Lịch sử bài nộp (${submissions.length})</h6>`;

        if (submissions.length > 0) {
            submissions.forEach((submission, index) => {
                const submitDate = new Date(submission.Ngay_gui).toLocaleString('vi-VN');
                const fileHtml = submission.File_name ?
                    `<a href="/user/tasks/${submission.ID_dulieu}/download" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-download me-1"></i> ${submission.File_name}
                    </a>` :
                    '<span class="text-muted">Không có file đính kèm</span>';

                // Đảo ngược số thứ tự: bài mới nhất (index 0) = #n, bài cũ nhất = #1
                const submissionNumber = submissions.length - index;

                html += `
                    <div class="submission-item">
                        <div class="submission-header">
                            <strong><i class="fas fa-file-alt me-2"></i>Bài nộp #${submissionNumber}</strong>
                            <span class="submission-date">
                                <i class="fas fa-clock me-1"></i>${submitDate}
                            </span>
                        </div>
                        <p class="mb-2"><strong>Minh chứng:</strong></p>
                        <p class="mb-3">${submission.Minh_chung || 'Không có mô tả'}</p>
                        ${fileHtml}
                    </div>
                `;
            });
        } else {
            html += `
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Bạn chưa nộp bài cho nhiệm vụ này</p>
                </div>
            `;
        }

        html += '</div></div>';
        content.innerHTML = html;
    }

    function getStatusText(status) {
        switch(status) {
            case 'chua_bat_dau': return 'Chưa bắt đầu';
            case 'dang_thuc_hien': return 'Đang thực hiện';
            case 'hoan_thanh': return 'Hoàn thành';
            default: return status;
        }
    }

    document.getElementById('submitForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append('minh_chung', document.getElementById('minh_chung').value);

        const fileInput = document.getElementById('file_upload');
        if (fileInput.files[0]) {
            formData.append('file', fileInput.files[0]);
        }

        fetch(`/user/tasks/${document.getElementById('task_id').value}/submit`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('submitModal')).hide();
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            showAlert('Có lỗi xảy ra khi nộp bài', 'error');
        });
    });

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

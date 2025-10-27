@extends('layouts.dashboard')

@section('title', 'Quản lý nhiệm vụ')
@section('page-title', 'Quản lý nhiệm vụ')
@section('search-placeholder', 'Tìm theo tên nhiệm vụ...')

@push('styles')
<style>
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

    .btn-add {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        color: white;
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

    .action-buttons {
        display: flex;
        gap: 5px;
    }

    .btn-action {
        padding: 5px 10px;
        border: none;
        border-radius: 5px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-edit {
        background: #17a2b8;
        color: white;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .btn-action:hover {
        opacity: 0.8;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
    }

    .status-badge.chua_bat_dau {
        background: #fff3cd;
        color: #856404;
    }

    .status-badge.dang_thuc_hien {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-badge.hoan_thanh {
        background: #d4edda;
        color: #155724;
    }

    .assigned-users {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .user-badge {
        display: inline-block;
        padding: 4px 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 500;
    }

    #user_ids {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 8px;
    }

    #user_ids option {
        padding: 8px;
        border-radius: 4px;
        margin: 2px 0;
    }

    #user_ids option:checked {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

    /* Modal styles */
    .modal-content {
        border-radius: 15px;
        border: none;
    }

    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px 15px 0 0;
        border: none;
    }

    .modal-title {
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-body {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 12px 15px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-modal {
        padding: 12px 30px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-cancel {
        background: #6c757d;
        color: white;
        border: none;
    }

    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
    }

    .btn-modal:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
</style>
@endpush

@section('content')
<!-- Danh sách nhiệm vụ -->
<div class="table-container">
    <div class="table-header">
        <h2 class="table-title">Danh sách nhiệm vụ</h2>
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
                    <input type="text" name="search" value="{{ $search }}" placeholder="Tên nhiệm vụ...">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-search"></i>
                    </button>
                    @if($search || $status)
                        <a href="{{ route('manager.tasks.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>

            <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#taskModal" onclick="openAddModal()">
                <i class="fas fa-plus"></i> Thêm nhiệm vụ mới
            </button>
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
                        <i class="fas fa-sort"></i> TÊN NHIỆM VỤ
                    </th>
                    <th>
                        <i class="fas fa-sort"></i> NGƯỜI ĐƯỢC GIAO & TRẠNG THÁI
                    </th>
                    <th style="display: none;">

                    </th>
                    <th>
                        <i class="fas fa-sort"></i> NGÀY GIAO
                    </th>
                    <th>
                        <i class="fas fa-sort"></i> HẠN HOÀN THÀNH
                    </th>
                    <th>THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $index => $task)
                    <tr>
                        <td>{{ $tasks->firstItem() + $index }}</td>
                        <td>
                            <div>
                                <strong>{{ $task->Ten_task }}</strong>
                                @if($task->Mo_ta)
                                    <br><small class="text-muted">{{ Str::limit($task->Mo_ta, 50) }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($task->users->count() > 0)
                                <div class="assigned-users">
                                    @foreach($task->users as $user)
                                        <div style="margin-bottom: 5px;">
                                            <span class="user-badge">{{ $user->Ho_ten }}</span>
                                            <span class="status-badge {{ $user->pivot->trang_thai }}" style="font-size: 10px;">
                                                @if($user->pivot->trang_thai == 'chua_bat_dau')
                                                    Chưa bắt đầu
                                                @elseif($user->pivot->trang_thai == 'dang_thuc_hien')
                                                    Đang làm
                                                @else
                                                    Hoàn thành
                                                @endif
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td style="display: none;">

                        </td>
                        <td>{{ \Carbon\Carbon::parse($task->Ngay_giao)->format('d/m/Y') }}</td>
                        <td>{{ $task->Ngay_het_han ? \Carbon\Carbon::parse($task->Ngay_het_han)->format('d/m/Y') : 'Không có' }}</td>
                        <td>
                            <div class="action-buttons">
                                <button onclick="viewSubmissions({{ $task->ID_task }})" class="btn-action btn-info" title="Xem bài nộp" style="background: #17a2b8; color: white;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editTask({{ $task->ID_task }})" class="btn-action btn-edit" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteTask({{ $task->ID_task }})" class="btn-action btn-delete" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
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

<!-- Modal Thêm/Sửa nhiệm vụ -->
<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalLabel">
                    <i class="fas fa-plus"></i>
                    Thêm nhiệm vụ mới
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="taskForm">
                    @csrf
                    <input type="hidden" id="task_id" name="task_id">

                    <div class="form-group">
                        <label for="Ten_task" class="form-label">Tên nhiệm vụ *</label>
                        <input type="text" class="form-control" id="Ten_task" name="Ten_task" required>
                    </div>

                    <div class="form-group">
                        <label for="Mo_ta" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="Mo_ta" name="Mo_ta" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_ids" class="form-label">Người được giao * <small class="text-muted">(Có thể chọn nhiều)</small></label>
                                <select class="form-control" id="user_ids" name="user_ids[]" multiple required style="height: 120px;">
                                    @foreach($users as $user)
                                        <option value="{{ $user->ID_user }}">{{ $user->Ho_ten }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Giữ Ctrl (Cmd trên Mac) để chọn nhiều người</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="Ngay_het_han" class="form-label">Hạn hoàn thành</label>
                                <input type="date" class="form-control" id="Ngay_het_han" name="Ngay_het_han">
                            </div>
                        </div>
                    </div>


                    <div class="text-end">
                        <button type="button" class="btn-modal btn-cancel me-2" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Hủy
                        </button>
                        <button type="submit" class="btn-modal btn-save">
                            <i class="fas fa-save"></i> Lưu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xem bài nộp -->
<div class="modal fade" id="submissionsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white">
                    <i class="fas fa-file-alt me-2"></i>
                    Bài nộp của nhân viên
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="submissionsContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Duyệt/Từ chối -->
<div class="modal fade" id="reviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">
                    <i class="fas fa-comment me-2"></i>
                    Đánh giá bài nộp
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="reviewForm">
                <div class="modal-body">
                    <input type="hidden" id="review_task_id">
                    <input type="hidden" id="review_user_id">
                    <input type="hidden" id="review_action">

                    <div class="mb-3">
                        <label class="form-label">Nhận xét</label>
                        <textarea class="form-control" id="review_comment" rows="3"
                                  placeholder="Nhập nhận xét của bạn..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Xác nhận
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Function để hiển thị thông báo
    function showAlert(message, type = 'success') {
        const alertContainer = document.getElementById('alertContainer');

        if (!alertContainer) {
            console.error('Alert container not found');
            return;
        }

        const alertId = 'alert-' + Date.now();
        const alertHtml = `
            <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                    <span>${message}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        alertContainer.innerHTML = alertHtml;

        // Auto hide sau 5 giây
        setTimeout(() => {
            const alert = document.getElementById(alertId);
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    }

    function changePerPage(value) {
        const url = new URL(window.location);
        url.searchParams.set('per_page', value);
        window.location.href = url.toString();
    }

    function openAddModal() {
        document.getElementById('taskModalLabel').innerHTML = '<i class="fas fa-plus"></i> Thêm nhiệm vụ mới';
        document.getElementById('taskForm').reset();
        document.getElementById('task_id').value = '';
    }

    function editTask(id) {
        fetch(`/manager/tasks/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('taskModalLabel').innerHTML = '<i class="fas fa-edit"></i> Chỉnh sửa nhiệm vụ';
                document.getElementById('task_id').value = data.ID_task;
                document.getElementById('Ten_task').value = data.Ten_task;
                document.getElementById('Mo_ta').value = data.Mo_ta || '';

                // Set multiple select values
                const userSelect = document.getElementById('user_ids');
                const userIds = data.users ? data.users.map(u => u.ID_user.toString()) : [];
                Array.from(userSelect.options).forEach(option => {
                    option.selected = userIds.includes(option.value);
                });

                document.getElementById('Ngay_het_han').value = data.Ngay_het_han ? data.Ngay_het_han.split(' ')[0] : '';

                const modal = new bootstrap.Modal(document.getElementById('taskModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Có lỗi xảy ra khi tải thông tin nhiệm vụ!', 'danger');
            });
    }

    function deleteTask(id) {
        if (confirm('Bạn có chắc chắn muốn xóa nhiệm vụ này?')) {
            fetch(`/manager/tasks/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Có lỗi xảy ra khi xóa nhiệm vụ!', 'danger');
            });
        }
    }

    // Xử lý form submit
    document.getElementById('taskForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const taskId = document.getElementById('task_id').value;
        const url = taskId ? `/manager/tasks/${taskId}` : '/manager/tasks';
        const method = taskId ? 'PUT' : 'POST';

        // Get selected user IDs
        const userSelect = document.getElementById('user_ids');
        const selectedUserIds = Array.from(userSelect.selectedOptions).map(option => option.value);

        if (selectedUserIds.length === 0) {
            showAlert('Vui lòng chọn ít nhất 1 người được giao!', 'danger');
            return;
        }

        const requestData = {
            Ten_task: document.getElementById('Ten_task').value,
            Mo_ta: document.getElementById('Mo_ta').value,
            user_ids: selectedUserIds,
            Ngay_het_han: document.getElementById('Ngay_het_han').value,
        };

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                const modal = bootstrap.Modal.getInstance(document.getElementById('taskModal'));
                modal.hide();
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showAlert(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Có lỗi xảy ra!', 'danger');
        });
    });

    // View Submissions
    function viewSubmissions(taskId) {
        const modal = new bootstrap.Modal(document.getElementById('submissionsModal'));
        modal.show();

        fetch(`/manager/tasks/${taskId}/submissions`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displaySubmissions(data.task, data.submissions);
                } else {
                    showAlert('Không thể tải danh sách bài nộp', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Có lỗi xảy ra khi tải bài nộp', 'danger');
            });
    }

    function displaySubmissions(task, submissions) {
        const content = document.getElementById('submissionsContent');

        let html = `
            <div class="mb-4">
                <h5><strong>Nhiệm vụ:</strong> ${task.Ten_task}</h5>
                <p class="text-muted mb-0">${task.Mo_ta || 'Không có mô tả'}</p>
            </div>
        `;

        if (submissions.length > 0) {
            html += '<div class="row">';

            submissions.forEach(submission => {
                const statusClass = submission.trang_thai === 'hoan_thanh' ? 'success' :
                                  submission.trang_thai === 'dang_thuc_hien' ? 'warning' : 'secondary';
                const statusText = submission.trang_thai === 'hoan_thanh' ? 'Hoàn thành' :
                                 submission.trang_thai === 'dang_thuc_hien' ? 'Đang làm' : 'Chưa bắt đầu';

                const hasSubmission = submission.has_submission;
                const submitDate = hasSubmission && submission.Ngay_gui ?
                    new Date(submission.Ngay_gui).toLocaleString('vi-VN') : 'Chưa nộp';

                html += `
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="mb-1"><i class="fas fa-user me-2"></i>${submission.user_name}</h6>
                                        <span class="badge bg-${statusClass}">${statusText}</span>
                                    </div>
                                    ${hasSubmission ? `
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>${submitDate}
                                        </small>
                                    ` : ''}
                                </div>

                                ${submission.comment ? `
                                    <div class="alert alert-warning mb-3">
                                        <strong><i class="fas fa-comment me-2"></i>Nhận xét của bạn:</strong>
                                        <p class="mb-0 mt-2">${submission.comment}</p>
                                    </div>
                                ` : ''}

                                ${hasSubmission ? `
                                    <div class="mb-3">
                                        <strong><i class="fas fa-history me-2"></i>Lịch sử bài nộp (${submission.submissions.length}):</strong>
                                        <div class="mt-2" style="max-height: 300px; overflow-y: auto;">
                                            ${submission.submissions.map((sub, idx) => {
                                                const subDate = new Date(sub.Ngay_gui).toLocaleString('vi-VN');
                                                // Đảo ngược số thứ tự: bài mới nhất (idx 0) = #n, bài cũ nhất = #1
                                                const submissionNumber = submission.submissions.length - idx;
                                                return `
                                                    <div class="border rounded p-2 mb-2 bg-light">
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <strong>Bài nộp #${submissionNumber}</strong>
                                                            <small class="text-muted">${subDate}</small>
                                                        </div>
                                                        <p class="mb-2 small">${sub.Minh_chung}</p>
                                                        ${sub.File_name ? `
                                                            <a href="/manager/tasks/file/${sub.ID_dulieu}/download"
                                                               class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-download me-1"></i> ${sub.File_name}
                                                            </a>
                                                        ` : '<small class="text-muted">Không có file</small>'}
                                                    </div>
                                                `;
                                            }).join('')}
                                        </div>
                                    </div>

                                    ${submission.trang_thai !== 'hoan_thanh' ? `
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-success btn-sm"
                                                    onclick="openReviewModal(${task.ID_task}, ${submission.user_id}, 'approve')">
                                                <i class="fas fa-check me-1"></i> Duyệt
                                            </button>
                                            <button class="btn btn-danger btn-sm"
                                                    onclick="openReviewModal(${task.ID_task}, ${submission.user_id}, 'reject')">
                                                <i class="fas fa-times me-1"></i> Yêu cầu làm lại
                                            </button>
                                        </div>
                                    ` : '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Đã duyệt</span>'}
                                ` : `
                                    <div class="text-center py-3">
                                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Chưa nộp bài</p>
                                    </div>
                                `}
                            </div>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
        } else {
            html += `
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Chưa có nhân viên được giao nhiệm vụ này</p>
                </div>
            `;
        }

        content.innerHTML = html;
    }

    function openReviewModal(taskId, userId, action) {
        document.getElementById('review_task_id').value = taskId;
        document.getElementById('review_user_id').value = userId;
        document.getElementById('review_action').value = action;

        const reviewModal = document.getElementById('reviewModal');
        const modalHeader = reviewModal.querySelector('.modal-header');
        const modalTitle = reviewModal.querySelector('.modal-title');
        const commentField = document.getElementById('review_comment');

        if (action === 'approve') {
            modalHeader.className = 'modal-header bg-success';
            modalTitle.innerHTML = '<i class="fas fa-check-circle me-2"></i> Duyệt bài nộp';
            commentField.placeholder = 'Nhập nhận xét của bạn... (không bắt buộc)';
            commentField.required = false;
        } else {
            modalHeader.className = 'modal-header bg-danger';
            modalTitle.innerHTML = '<i class="fas fa-times-circle me-2"></i> Yêu cầu làm lại';
            commentField.placeholder = 'Nhập lý do yêu cầu làm lại... (bắt buộc)';
            commentField.required = true;
        }

        document.getElementById('review_comment').value = '';

        // Hide submissions modal
        bootstrap.Modal.getInstance(document.getElementById('submissionsModal')).hide();

        // Show review modal
        new bootstrap.Modal(reviewModal).show();
    }

    // Handle review form submission
    document.getElementById('reviewForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const taskId = document.getElementById('review_task_id').value;
        const userId = document.getElementById('review_user_id').value;
        const action = document.getElementById('review_action').value;
        const comment = document.getElementById('review_comment').value;

        const url = action === 'approve' ?
            `/manager/tasks/${taskId}/approve/${userId}` :
            `/manager/tasks/${taskId}/reject/${userId}`;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ comment: comment })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('reviewModal')).hide();
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert(data.message || 'Có lỗi xảy ra!', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Có lỗi xảy ra!', 'danger');
        });
    });
</script>
@endpush

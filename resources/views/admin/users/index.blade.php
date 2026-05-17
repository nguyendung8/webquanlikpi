@extends('layouts.dashboard')

@section('title', 'Danh sách người dùng')
@section('page-title', 'Quản lý Người dùng')
@section('search-placeholder', 'Tìm theo tên, email...')

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

    .btn-add {
        background: linear-gradient(135deg, #eac066 0%, #F66600 100%);
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

    .status-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-hoat-dong {
        background: #28a745;
        color: white;
    }

    .status-khong-hoat-dong {
        background: #6c757d;
        color: white;
    }

    .status-bi-chan {
        background: #dc3545;
        color: white;
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

    /* Modal styles */
    .modal-content {
        border-radius: 15px;
        border: none;
    }

    .modal-header {
        background: linear-gradient(135deg, #eac066 0%, #F66600 100%);
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
        background: linear-gradient(135deg, #eac066 0%, #F66600 100%);
        color: white;
        border: none;
    }

    .btn-modal:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    /* Alert styles */
    .alert {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 10px;
        animation: slideDown 0.3s ease-out;
    }

    .alert-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .alert-danger {
        background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
        color: white;
    }

    .alert-warning {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        color: #212529;
    }

    .alert-info {
        background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
        color: white;
    }

    .alert .btn-close {
        filter: invert(1);
    }

    .alert .btn-close:hover {
        opacity: 0.8;
    }

    @keyframes slideDown {
        from {
            transform: translateX(-50%) translateY(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }
    }
</style>
@endpush

@section('content')
<!-- Biểu đồ số lượng tài khoản theo thời gian -->
<div class="row">
    <div class="col-md-6">
        <div class="chart-container">
            <h3 class="chart-title">
                <i class="fas fa-chart-line"></i>
                Số lượng tài khoản theo thời gian
            </h3>
            <canvas id="usersByTimeChart" width="400" height="200"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="chart-container">
            <h3 class="chart-title">
                <i class="fas fa-chart-pie"></i>
                Thống kê tài khoản theo phòng ban
            </h3>
            <canvas id="usersByDepartmentChart" width="400" height="200"></canvas>
        </div>
    </div>
</div>

<!-- Danh sách người dùng -->
<div class="table-container">
    <div class="table-header">
        <h2 class="table-title">Danh sách người dùng</h2>
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
                    <input type="text" name="search" value="{{ $search }}" placeholder="Tìm theo tên, email...">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-search"></i>
                    </button>
                    @if($search)
                        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>

            <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#userModal" onclick="openAddModal()">
                <i class="fas fa-plus"></i> Thêm người dùng mới
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
                        <i class="fas fa-sort"></i> TÊN NGƯỜI DÙNG
                    </th>
                    <th>
                        <i class="fas fa-sort"></i> EMAIL
                    </th>
                    <th>
                        <i class="fas fa-sort"></i> QUYỀN
                    </th>
                    <th>
                        <i class="fas fa-sort"></i> PHÒNG BAN
                    </th>
                    <th>
                        <i class="fas fa-sort"></i> TRẠNG THÁI
                    </th>
                    <th>THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                    <tr>
                        <td>{{ $users->firstItem() + $index }}</td>
                        <td>{{ $user->Ho_ten ?? 'Chưa cập nhật' }}</td>
                        <td>{{ $user->Email }}</td>
                        <td>{{ $user->quyen->Ten_quyen ?? 'Chưa phân quyền' }}</td>
                        <td>{{ $user->phongban->Ten_phongban ?? 'Chưa phân phòng ban' }}</td>
                        <td>
                            <span class="status-badge status-{{ str_replace('_', '-', $user->Trang_thai) }}">
                                {{ ucfirst(str_replace('_', ' ', $user->Trang_thai)) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button onclick="editUser({{ $user->ID_user }})" class="btn-action btn-edit" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteUser({{ $user->ID_user }})" class="btn-action btn-delete" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
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
            Đang xem {{ $users->firstItem() ?? 0 }} đến {{ $users->lastItem() ?? 0 }}
            trong tổng số {{ $users->total() }} mục
        </div>
        <div>
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Modal Thêm/Sửa người dùng -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">
                    <i class="fas fa-plus"></i>
                    Thêm người dùng mới
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    @csrf
                    <input type="hidden" id="user_id" name="user_id">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="Ho_ten" class="form-label">Họ và tên *</label>
                                <input type="text" class="form-control" id="Ho_ten" name="Ho_ten" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="Email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="Email" name="Email" required>
                            </div>
                        </div>
                    </div>

                    <!-- Password fields - chỉ hiển thị khi thêm mới -->
                    <div id="passwordFields">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-label">Mật khẩu *</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label">Xác nhận mật khẩu *</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ID_phongban" class="form-label">Phòng ban *</label>
                                <select class="form-control" id="ID_phongban" name="ID_phongban" required>
                                    <option value="">--- Chọn ---</option>
                                    @foreach(\App\Models\Phongban::all() as $phongban)
                                        <option value="{{ $phongban->ID_phongban }}">{{ $phongban->Ten_phongban }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ID_quyen" class="form-label">Quyền *</label>
                                <select class="form-control" id="ID_quyen" name="ID_quyen" required>
                                    <option value="">--- Chọn ---</option>
                                    @foreach(\App\Models\Quyen::all() as $quyen)
                                        <option value="{{ $quyen->ID_quyen }}">{{ $quyen->Ten_quyen }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="Trang_thai" class="form-label">Trạng thái *</label>
                        <select class="form-control" id="Trang_thai" name="Trang_thai" required>
                            <option value="hoat_dong" selected>Hoạt động</option>
                            <option value="khong_hoat_dong">Không hoạt động</option>
                            <option value="bi_chan">Bị chặn</option>
                        </select>
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Function để hiển thị thông báo
    function showAlert(message, type = 'success') {
        const alertContainer = document.getElementById('alertContainer');

        const alertId = 'alert-' + Date.now();
        const alertHtml = `
            <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
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

    // Biểu đồ số lượng tài khoản theo thời gian
    const usersByTimeData = @json($usersByTime);
    const timeLabels = usersByTimeData.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('vi-VN');
    });
    const timeCounts = usersByTimeData.map(item => item.count);

    const timeCtx = document.getElementById('usersByTimeChart').getContext('2d');
    new Chart(timeCtx, {
        type: 'line',
        data: {
            labels: timeLabels,
            datasets: [{
                label: 'Số tài khoản tạo mới',
                data: timeCounts,
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

    // Biểu đồ tài khoản theo phòng ban
    const usersByDepartmentData = @json($usersByDepartment);
    const departmentLabels = usersByDepartmentData.map(item => item.Ten_phongban);
    const departmentCounts = usersByDepartmentData.map(item => item.count);
    const colors = ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe', '#00f2fe'];

    const departmentCtx = document.getElementById('usersByDepartmentChart').getContext('2d');
    new Chart(departmentCtx, {
        type: 'doughnut',
        data: {
            labels: departmentLabels,
            datasets: [{
                data: departmentCounts,
                backgroundColor: colors.slice(0, departmentLabels.length),
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

    function openAddModal() {
        document.getElementById('userModalLabel').innerHTML = '<i class="fas fa-plus"></i> Thêm người dùng mới';
        document.getElementById('userForm').reset();
        document.getElementById('user_id').value = '';

        // Hiển thị password fields và set required
        document.getElementById('passwordFields').style.display = 'block';
        document.getElementById('password').required = true;
        document.getElementById('password_confirmation').required = true;
    }

    function editUser(id) {
        fetch(`/users/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('userModalLabel').innerHTML = '<i class="fas fa-edit"></i> Chỉnh sửa người dùng';
                document.getElementById('user_id').value = data.ID_user;
                document.getElementById('Ho_ten').value = data.Ho_ten || '';
                document.getElementById('Email').value = data.Email;
                document.getElementById('ID_phongban').value = data.ID_phongban || '';
                document.getElementById('ID_quyen').value = data.ID_quyen;
                document.getElementById('Trang_thai').value = data.Trang_thai;

                // Ẩn password fields khi edit
                document.getElementById('passwordFields').style.display = 'none';
                document.getElementById('password').required = false;
                document.getElementById('password_confirmation').required = false;

                const modal = new bootstrap.Modal(document.getElementById('userModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Có lỗi xảy ra khi tải thông tin người dùng!', 'danger');
            });
    }

    function deleteUser(id) {
        if (confirm('Bạn có chắc chắn muốn xóa người dùng này?')) {
            fetch(`/users/${id}`, {
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
                    // Reload sau 1.5 giây để user thấy thông báo
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Có lỗi xảy ra khi xóa người dùng!', 'danger');
            });
        }
    }

    // Xử lý form submit
    document.getElementById('userForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const userId = document.getElementById('user_id').value;
        const url = userId ? `/users/${userId}` : '/users';
        const method = userId ? 'PUT' : 'POST';

        // Nếu là edit, xóa password khỏi formData
        if (userId) {
            formData.delete('password');
            formData.delete('password_confirmation');
        }

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(Object.fromEntries(formData))
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                // Đóng modal và reload sau 1.5 giây
                const modal = bootstrap.Modal.getInstance(document.getElementById('userModal'));
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
</script>
@endpush

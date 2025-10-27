@extends('layouts.dashboard')

@section('title', 'Quản lý phòng ban')
@section('page-title', 'Quản lý Phòng ban')
@section('search-placeholder', 'Tìm theo tên phòng ban...')

@push('styles')
<style>
    .chart-container {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        height: 400px; /* Giới hạn chiều cao */
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

    /* Giới hạn kích thước canvas */
    .chart-container canvas {
        max-height: 300px !important;
        max-width: 100% !important;
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

    .user-count {
        background: #e9ecef;
        color: #495057;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<!-- Biểu đồ thống kê user theo phòng ban -->
<div class="row">
    <div class="col-md-12">
        <div class="chart-container">
            <h3 class="chart-title">
                <i class="fas fa-chart-pie"></i>
                Thống kê người dùng theo phòng ban
            </h3>
            <canvas id="usersByDepartmentChart"></canvas>
        </div>
    </div>
</div>

<!-- Danh sách phòng ban -->
<div class="table-container">
    <div class="table-header">
        <h2 class="table-title">Danh sách phòng ban</h2>
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
                    <input type="text" name="search" value="{{ $search }}" placeholder="Tìm theo tên phòng ban...">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-search"></i>
                    </button>
                    @if($search)
                        <a href="{{ route('phongban.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>

            <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#phongbanModal" onclick="openAddModal()">
                <i class="fas fa-plus"></i> Thêm phòng ban mới
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
                        <i class="fas fa-sort"></i> TÊN PHÒNG BAN
                    </th>
                    <th>
                        <i class="fas fa-sort"></i> SỐ NGƯỜI DÙNG
                    </th>
                    <th>THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
                @forelse($phongbans as $index => $phongban)
                    <tr>
                        <td>{{ $phongbans->firstItem() + $index }}</td>
                        <td>{{ $phongban->Ten_phongban }}</td>
                        <td>
                            <span class="user-count">{{ $phongban->users_count }} người</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button onclick="editPhongban({{ $phongban->ID_phongban }})" class="btn-action btn-edit" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deletePhongban({{ $phongban->ID_phongban }})" class="btn-action btn-delete" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <i class="fas fa-building fa-3x text-muted mb-3"></i>
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
            Đang xem {{ $phongbans->firstItem() ?? 0 }} đến {{ $phongbans->lastItem() ?? 0 }}
            trong tổng số {{ $phongbans->total() }} mục
        </div>
        <div>
            {{ $phongbans->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Modal Thêm/Sửa phòng ban -->
<div class="modal fade" id="phongbanModal" tabindex="-1" aria-labelledby="phongbanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="phongbanModalLabel">
                    <i class="fas fa-plus"></i>
                    Thêm phòng ban mới
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="phongbanForm">
                    @csrf
                    <input type="hidden" id="phongban_id" name="phongban_id">

                    <div class="form-group">
                        <label for="Ten_phongban" class="form-label">Tên phòng ban *</label>
                        <input type="text" class="form-control" id="Ten_phongban" name="Ten_phongban" required>
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

    // Biểu đồ thống kê user theo phòng ban
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
            maintainAspectRatio: false, // Tắt tỷ lệ khung hình tự động
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                }
            },
            // Giới hạn kích thước
            aspectRatio: 2, // Tỷ lệ chiều rộng/chiều cao
            layout: {
                padding: {
                    top: 10,
                    bottom: 10,
                    left: 10,
                    right: 10
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
        document.getElementById('phongbanModalLabel').innerHTML = '<i class="fas fa-plus"></i> Thêm phòng ban mới';
        document.getElementById('phongbanForm').reset();
        document.getElementById('phongban_id').value = '';
    }

    function editPhongban(id) {
        fetch(`/phongban/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('phongbanModalLabel').innerHTML = '<i class="fas fa-edit"></i> Chỉnh sửa phòng ban';
                document.getElementById('phongban_id').value = data.ID_phongban;
                document.getElementById('Ten_phongban').value = data.Ten_phongban;

                const modal = new bootstrap.Modal(document.getElementById('phongbanModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Có lỗi xảy ra khi tải thông tin phòng ban!', 'danger');
            });
    }

    function deletePhongban(id) {
        if (confirm('Bạn có chắc chắn muốn xóa phòng ban này?')) {
            fetch(`/phongban/${id}`, {
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
                showAlert('Có lỗi xảy ra khi xóa phòng ban!', 'danger');
            });
        }
    }

    // Xử lý form submit
    document.getElementById('phongbanForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const phongbanId = document.getElementById('phongban_id').value;
        const url = phongbanId ? `/phongban/${phongbanId}` : '/phongban';
        const method = phongbanId ? 'PUT' : 'POST';

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
                const modal = bootstrap.Modal.getInstance(document.getElementById('phongbanModal'));
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

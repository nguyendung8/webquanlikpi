@extends('layouts.dashboard')

@section('title', 'Quản lý loại KPI')
@section('page-title', 'Quản lý loại KPI')
@section('search-placeholder', 'Tìm theo tên loại KPI...')

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

    .kpi-count {
        background: #e9ecef;
        color: #495057;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
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
        color: #eac066;
        padding: 8px 12px;
    }

    .page-link:hover {
        background: #eac066;
        color: white;
        border-color: #eac066;
    }

    .page-item.active .page-link {
        background: #eac066;
        border-color: #667eea;
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
<!-- Danh sách loại KPI -->
<div class="table-container">
    <div class="table-header">
        <h2 class="table-title">Danh sách loại KPI</h2>
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
                    <input type="text" name="search" value="{{ $search }}" placeholder="Tìm theo tên loại KPI...">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-search"></i>
                    </button>
                    @if($search)
                        <a href="{{ route('manager.kpi_type.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>

            <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#loaiKpiModal" onclick="openAddModal()">
                <i class="fas fa-plus"></i> Thêm loại KPI mới
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
                        <i class="fas fa-sort"></i> TÊN LOẠI KPI
                    </th>
                    <th>
                        <i class="fas fa-sort"></i> SỐ KPI
                    </th>
                    <th>THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loaiKpis as $index => $loaiKpi)
                    <tr>
                        <td>{{ $loaiKpis->firstItem() + $index }}</td>
                        <td>{{ $loaiKpi->Ten_loai_kpi }}</td>
                        <td>
                            <span class="kpi-count">{{ $loaiKpi->kpis_count }} KPI</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button onclick="editLoaiKpi({{ $loaiKpi->ID_loai_kpi }})" class="btn-action btn-edit" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteLoaiKpi({{ $loaiKpi->ID_loai_kpi }})" class="btn-action btn-delete" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <i class="fas fa-list fa-3x text-muted mb-3"></i>
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
            Đang xem {{ $loaiKpis->firstItem() ?? 0 }} đến {{ $loaiKpis->lastItem() ?? 0 }}
            trong tổng số {{ $loaiKpis->total() }} mục
        </div>
        <div>
            {{ $loaiKpis->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Modal Thêm/Sửa loại KPI -->
<div class="modal fade" id="loaiKpiModal" tabindex="-1" aria-labelledby="loaiKpiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loaiKpiModalLabel">
                    <i class="fas fa-plus"></i>
                    Thêm loại KPI mới
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="loaiKpiForm">
                    @csrf
                    <input type="hidden" id="loai_kpi_id" name="loai_kpi_id">

                    <div class="form-group">
                        <label for="Ten_loai_kpi" class="form-label">Tên loại KPI *</label>
                        <input type="text" class="form-control" id="Ten_loai_kpi" name="Ten_loai_kpi" required>
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
        document.getElementById('loaiKpiModalLabel').innerHTML = '<i class="fas fa-plus"></i> Thêm loại KPI mới';
        document.getElementById('loaiKpiForm').reset();
        document.getElementById('loai_kpi_id').value = '';
    }

    function editLoaiKpi(id) {
        console.log('Edit ID:', id);

        fetch(`/manager/kpi-type/${id}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);
            document.getElementById('loaiKpiModalLabel').innerHTML = '<i class="fas fa-edit"></i> Chỉnh sửa loại KPI';
            document.getElementById('loai_kpi_id').value = data.ID_loai_kpi;
            document.getElementById('Ten_loai_kpi').value = data.Ten_loai_kpi;

            const modal = new bootstrap.Modal(document.getElementById('loaiKpiModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Có lỗi xảy ra khi tải thông tin loại KPI!', 'danger');
        });
    }

    function deleteLoaiKpi(id) {
        console.log('Delete ID:', id);

        if (confirm('Bạn có chắc chắn muốn xóa loại KPI này?')) {
            fetch(`/manager/kpi-type/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                console.log('Delete response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Delete response:', data);
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
                showAlert('Có lỗi xảy ra khi xóa loại KPI!', 'danger');
            });
        }
    }

    // Xử lý form submit
    document.getElementById('loaiKpiForm').addEventListener('submit', function(e) {
        e.preventDefault();

        console.log('Form submitted');

        const formData = new FormData(this);
        const loaiKpiId = document.getElementById('loai_kpi_id').value;
        const url = loaiKpiId ? `/manager/kpi-type/${loaiKpiId}` : '/manager/kpi-type';
        const method = loaiKpiId ? 'PUT' : 'POST';

        console.log('URL:', url, 'Method:', method);

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(Object.fromEntries(formData))
        })
        .then(response => {
            console.log('Form response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Form response:', data);
            if (data.success) {
                showAlert(data.message, 'success');
                const modal = bootstrap.Modal.getInstance(document.getElementById('loaiKpiModal'));
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

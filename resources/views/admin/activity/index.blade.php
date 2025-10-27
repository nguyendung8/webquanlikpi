@extends('layouts.dashboard')

@section('title', 'Nhật ký hoạt động')
@section('page-title', 'Nhật ký hoạt động')
@section('search-placeholder', 'Tìm theo tên người thực hiện...')

@push('styles')
<style>
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        text-align: center;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-size: 20px;
        color: white;
    }

    .stat-icon.total {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-icon.them {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    .stat-icon.sua {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .stat-icon.xoa {
        background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
    }

    .stat-icon.duyet {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    }

    .stat-number {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 12px;
        color: #6c757d;
        font-weight: 500;
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

    .filters {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-group select {
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

    .action-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
    }

    .action-badge.them {
        background: #d4edda;
        color: #155724;
    }

    .action-badge.sua {
        background: #d1ecf1;
        color: #0c5460;
    }

    .action-badge.xoa {
        background: #f8d7da;
        color: #721c24;
    }

    .action-badge.duyet {
        background: #fff3cd;
        color: #856404;
    }

    .object-badge {
        background: #e9ecef;
        color: #495057;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 14px;
    }

    .user-details h6 {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50;
    }

    .user-details small {
        color: #6c757d;
        font-size: 12px;
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

    .time-info {
        color: #6c757d;
        font-size: 12px;
    }

    .time-info i {
        margin-right: 5px;
    }
</style>
@endpush

@section('content')
<!-- Thống kê tổng quan -->
<div class="stats-container">
    <div class="stat-card">
        <div class="stat-icon total">
            <i class="fas fa-history"></i>
        </div>
        <div class="stat-number">{{ $stats['total'] }}</div>
        <div class="stat-label">Tổng hoạt động</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon them">
            <i class="fas fa-plus"></i>
        </div>
        <div class="stat-number">{{ $stats['them'] }}</div>
        <div class="stat-label">Thêm mới</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon sua">
            <i class="fas fa-edit"></i>
        </div>
        <div class="stat-number">{{ $stats['sua'] }}</div>
        <div class="stat-label">Chỉnh sửa</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon xoa">
            <i class="fas fa-trash"></i>
        </div>
        <div class="stat-number">{{ $stats['xoa'] }}</div>
        <div class="stat-label">Xóa</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon duyet">
            <i class="fas fa-check"></i>
        </div>
        <div class="stat-number">{{ $stats['duyet'] }}</div>
        <div class="stat-label">Duyệt</div>
    </div>
</div>

<!-- Danh sách hoạt động -->
<div class="table-container">
    <div class="table-header">
        <h2 class="table-title">Danh sách hoạt động</h2>
        <div class="filters">
            <div class="filter-group">
                <span>Hành động:</span>
                <select onchange="filterByAction(this.value)">
                    <option value="">Tất cả</option>
                    <option value="them" {{ $action == 'them' ? 'selected' : '' }}>Thêm</option>
                    <option value="sua" {{ $action == 'sua' ? 'selected' : '' }}>Sửa</option>
                    <option value="xoa" {{ $action == 'xoa' ? 'selected' : '' }}>Xóa</option>
                    <option value="duyet" {{ $action == 'duyet' ? 'selected' : '' }}>Duyệt</option>
                </select>
            </div>

            <div class="filter-group">
                <span>Đối tượng:</span>
                <select onchange="filterByObject(this.value)">
                    <option value="">Tất cả</option>
                    <option value="user" {{ $object == 'user' ? 'selected' : '' }}>Người dùng</option>
                    <option value="kpi" {{ $object == 'kpi' ? 'selected' : '' }}>KPI</option>
                    <option value="phancong" {{ $object == 'phancong' ? 'selected' : '' }}>Phân công</option>
                </select>
            </div>

            <div class="search-box">
                <span>Tìm kiếm:</span>
                <form method="GET" style="display: flex; gap: 10px;">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Tên người thực hiện...">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <input type="hidden" name="action" value="{{ $action }}">
                    <input type="hidden" name="object" value="{{ $object }}">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-search"></i>
                    </button>
                    @if($search || $action || $object)
                        <a href="{{ route('activity.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>

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
                        <i class="fas fa-user"></i> NGƯỜI THỰC HIỆN
                    </th>
                    <th>
                        <i class="fas fa-tag"></i> HÀNH ĐỘNG
                    </th>
                    <th>
                        <i class="fas fa-object-group"></i> ĐỐI TƯỢNG
                    </th>
                    <th>
                        <i class="fas fa-info-circle"></i> CHI TIẾT
                    </th>
                    <th>
                        <i class="fas fa-clock"></i> THỜI GIAN
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $index => $activity)
                    <tr>
                        <td>{{ $activities->firstItem() + $index }}</td>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">
                                    {{ substr($activity->nguoiThucHien->Ho_ten ?? 'N/A', 0, 1) }}
                                </div>
                                <div class="user-details">
                                    <h6>{{ $activity->nguoiThucHien->Ho_ten ?? 'Người dùng không tồn tại' }}</h6>
                                    <small>{{ $activity->nguoiThucHien->Email ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="action-badge {{ $activity->Hanh_dong }}">
                                {{ $activity->hanh_dong_text }}
                            </span>
                        </td>
                        <td>
                            <span class="object-badge">{{ $activity->doi_tuong_text }}</span>
                        </td>
                        <td>
                            <strong>{{ $activity->doi_tuong_name }}</strong>
                        </td>
                        <td>
                            <div class="time-info">
                                <i class="fas fa-calendar"></i>
                                {{ $activity->Ngay_thuchien->format('d/m/Y H:i') }}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Không có hoạt động nào</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="pagination-info">
            Đang xem {{ $activities->firstItem() ?? 0 }} đến {{ $activities->lastItem() ?? 0 }}
            trong tổng số {{ $activities->total() }} mục
        </div>
        <div>
            {{ $activities->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function changePerPage(value) {
        const url = new URL(window.location);
        url.searchParams.set('per_page', value);
        window.location.href = url.toString();
    }

    function filterByAction(action) {
        const url = new URL(window.location);
        if (action) {
            url.searchParams.set('action', action);
        } else {
            url.searchParams.delete('action');
        }
        window.location.href = url.toString();
    }

    function filterByObject(object) {
        const url = new URL(window.location);
        if (object) {
            url.searchParams.set('object', object);
        } else {
            url.searchParams.delete('object');
        }
        window.location.href = url.toString();
    }
</script>
@endpush

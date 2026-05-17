@extends('layouts.dashboard')

@section('title', 'Danh sách KPI')
@section('page-title', 'Quản lý KPIs')
@section('search-placeholder', 'Tìm KPI, nhiệm vụ...')

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

    .priority-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .priority-rat-gap {
        background: #dc3545;
        color: white;
    }

    .priority-gap {
        background: #fd7e14;
        color: white;
    }

    .priority-trung-binh {
        background: #ffc107;
        color: #212529;
    }

    .priority-khong {
        background: #6c757d;
        color: white;
    }

    .status-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-chua-thuc-hien {
        background: #6c757d;
        color: white;
    }

    .status-dang-thuc-hien {
        background: #17a2b8;
        color: white;
    }

    .status-hoan-thanh {
        background: #28a745;
        color: white;
    }

    .status-qua-han {
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
</style>
@endpush

@section('content')
<!-- Biểu đồ KPI tạo mới theo tháng -->
<div class="row">
    <div class="col-md-6">
        <div class="chart-container">
            <h3 class="chart-title">
                <i class="fas fa-chart-line"></i>
                KPI tạo mới theo tháng
            </h3>
            <canvas id="kpiByMonthChart" width="400" height="200"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="chart-container">
            <h3 class="chart-title">
                <i class="fas fa-chart-pie"></i>
                KPI theo loại
            </h3>
            <canvas id="kpiByTypeChart" width="400" height="200"></canvas>
        </div>
    </div>
</div>

<!-- Danh sách phân công KPI -->
<div class="table-container">
    <div class="table-header">
        <h2 class="table-title">Danh sách KPI</h2>
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
                    <input type="text" name="search" value="{{ $search }}" placeholder="Tìm theo tên KPI, người thực hiện...">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-search"></i>
                    </button>
                    @if($search)
                        <a href="{{ route('kpi.index') }}" class="btn btn-secondary btn-sm">
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
                        <i class="fas fa-sort"></i> Tên KPI
                    </th>
                    <th>
                        <i class="fas fa-sort"></i> Chỉ tiêu
                    </th>
                    <th>
                        <i class="fas fa-sort"></i> Đơn vị
                    </th>
                    <th>
                        <i class="fas fa-sort"></i> Độ ưu tiên
                    </th>
                    <th>
                        <i class="fas fa-sort"></i> Người thực hiện
                    </th>
                    <th>
                        <i class="fas fa-sort"></i> Phòng ban
                    </th>
                    <th>
                        <i class="fas fa-sort"></i> Trạng thái
                    </th>
                    <th>
                        <i class="fas fa-sort"></i> Ngày tạo
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($phancongKpis as $index => $phancong)
                    <tr>
                        <td>{{ $phancongKpis->firstItem() + $index }}</td>
                        <td>{{ $phancong->kpi->Ten_kpi }}</td>
                        <td>{{ number_format($phancong->kpi->Chi_tieu) }}</td>
                        <td>{{ $phancong->kpi->Donvi_tinh }}</td>
                        <td>
                            <span class="priority-badge priority-{{ strtolower(str_replace(' ', '-', $phancong->kpi->Do_uu_tien)) }}">
                                {{ $phancong->kpi->Do_uu_tien }}
                            </span>
                        </td>
                        <td>{{ $phancong->user->Ho_ten ?? 'Chưa phân công' }}</td>
                        <td>{{ $phancong->phongban->Ten_phongban ?? 'Chưa phân phòng ban' }}</td>
                        <td>
                            <span class="status-badge status-{{ $phancong->Trang_thai }}">
                                {{ ucfirst(str_replace('_', ' ', $phancong->Trang_thai)) }}
                            </span>
                        </td>
                        <td>{{ $phancong->kpi->Ngay_tao->format('d/m/Y H:i:s') }}</td>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-4">
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
            Đang xem {{ $phancongKpis->firstItem() ?? 0 }} đến {{ $phancongKpis->lastItem() ?? 0 }}
            trong tổng số {{ $phancongKpis->total() }} mục
        </div>
        <div>
            {{ $phancongKpis->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Form xóa ẩn -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ KPI theo tháng
    const kpiByMonthData = @json($kpiByMonth);
    const monthLabels = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];
    const monthData = new Array(12).fill(0);

    kpiByMonthData.forEach(item => {
        monthData[item.month - 1] = item.count;
    });

    const monthCtx = document.getElementById('kpiByMonthChart').getContext('2d');
    new Chart(monthCtx, {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Số KPI tạo mới',
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

    // Biểu đồ KPI theo loại
    const kpiByTypeData = @json($kpiByType);
    const typeLabels = kpiByTypeData.map(item => item.Ten_loai_kpi);
    const typeCounts = kpiByTypeData.map(item => item.count);
    const colors = ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe', '#00f2fe'];

    const typeCtx = document.getElementById('kpiByTypeChart').getContext('2d');
    new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: typeLabels,
            datasets: [{
                data: typeCounts,
                backgroundColor: colors.slice(0, typeLabels.length),
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

    function deleteKpi(id) {
        if (confirm('Bạn có chắc chắn muốn xóa KPI này?')) {
            const form = document.getElementById('deleteForm');
            form.action = `/kpi/${id}`;
            form.submit();
        }
    }
</script>
@endpush

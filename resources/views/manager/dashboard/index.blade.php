@extends('layouts.dashboard')

@section('title', 'Trang chủ')
@section('page-title', 'Trang chủ')
@section('search-placeholder', 'Tìm kiếm...')

@push('styles')
<style>
    .stats-grid {
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

    .stat-icon.active {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    .stat-icon.completed {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .stat-icon.overdue {
        background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
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

    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
        margin-bottom: 30px;
    }

    .chart-container {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        height: 350px;
    }

    .chart-title {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chart-title i {
        color: #667eea;
    }

    .chart-container canvas {
        max-height: 250px !important;
        max-width: 100% !important;
    }

    .activities-container {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .activity-item {
        display: flex;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #f1f3f4;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 15px;
    }

    .activity-content h6 {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50;
    }

    .activity-content p {
        margin: 0;
        font-size: 12px;
        color: #6c757d;
    }

    .status-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
        text-transform: uppercase;
    }

    .status-badge.dang_thuc_hien {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-badge.hoan_thanh {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.qua_han {
        background: #f8d7da;
        color: #721c24;
    }

    .status-badge.chua_thuc_hien {
        background: #fff3cd;
        color: #856404;
    }
</style>
@endpush

@section('content')
<!-- Thống kê tổng quan -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon total">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-number">{{ $stats['total_kpis'] ?? 0 }}</div>
        <div class="stat-label">Tổng số KPI</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon active">
            <i class="fas fa-play"></i>
        </div>
        <div class="stat-number">{{ $stats['active_kpis'] ?? 0 }}</div>
        <div class="stat-label">KPI đang thực hiện</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon completed">
            <i class="fas fa-check"></i>
        </div>
        <div class="stat-number">{{ $stats['completed_kpis'] ?? 0 }}</div>
        <div class="stat-label">KPI hoàn thành</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon overdue">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="stat-number">{{ $stats['overdue_kpis'] ?? 0 }}</div>
        <div class="stat-label">KPI quá hạn</div>
    </div>
</div>

<!-- Biểu đồ thống kê -->
<div class="charts-grid">
    <!-- KPI theo trạng thái -->
    <div class="chart-container">
        <h3 class="chart-title">
            <i class="fas fa-chart-pie"></i>
            KPI theo trạng thái
        </h3>
        <canvas id="kpiByStatusChart"></canvas>
    </div>

    <!-- KPI theo người dùng -->
    <div class="chart-container">
        <h3 class="chart-title">
            <i class="fas fa-chart-bar"></i>
            KPI theo người dùng
        </h3>
        <canvas id="kpiByUserChart"></canvas>
    </div>
</div>

<!-- Hoạt động gần đây -->
<div class="activities-container">
    <h3 class="chart-title">
        <i class="fas fa-history"></i>
        Hoạt động gần đây
    </h3>

    @forelse($recentActivities as $activity)
        <div class="activity-item">
            <div class="activity-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="activity-content" style="flex: 1;">
                <h6>{{ $activity->Ten_kpi }}</h6>
                <p>Phân công cho: {{ $activity->Ho_ten }} • {{ \Carbon\Carbon::parse($activity->Ngay_batdau)->format('d/m/Y') }}</p>
            </div>
            <div>
                <span class="status-badge {{ $activity->Trang_thai }}">
                    {{ ucfirst(str_replace('_', ' ', $activity->Trang_thai)) }}
                </span>
            </div>
        </div>
    @empty
        <div class="text-center py-4">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">Chưa có hoạt động nào</p>
        </div>
    @endforelse
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const colors = ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe', '#00f2fe'];

    // KPI theo trạng thái
    const kpiByStatusData = @json($kpiByStatus);
    const statusLabels = kpiByStatusData.map(item => {
        const statusMap = {
            'chua_thuc_hien': 'Chưa thực hiện',
            'dang_thuc_hien': 'Đang thực hiện',
            'hoan_thanh': 'Hoàn thành',
            'qua_han': 'Quá hạn'
        };
        return statusMap[item.Trang_thai] || item.Trang_thai;
    });
    const statusCounts = kpiByStatusData.map(item => item.count);

    new Chart(document.getElementById('kpiByStatusChart').getContext('2d'), {
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
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: { size: 11 }
                    }
                }
            }
        }
    });

    // KPI theo người dùng
    const kpiByUserData = @json($kpiByUser);
    const userLabels = kpiByUserData.map(item => item.Ho_ten);
    const userCounts = kpiByUserData.map(item => item.count);

    new Chart(document.getElementById('kpiByUserChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: userLabels,
            datasets: [{
                label: 'Số KPI',
                data: userCounts,
                backgroundColor: colors.slice(0, userLabels.length),
                borderWidth: 0,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
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
</script>
@endpush

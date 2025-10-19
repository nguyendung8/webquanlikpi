@extends('layouts.dashboard')

@section('title', 'Trang chủ')

@push('styles')
<style>
    .stats-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
        transition: transform 0.3s;
    }

    .stats-card:hover {
        transform: translateY(-5px);
    }

    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        margin-bottom: 15px;
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

    .chart-container {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        height: 400px;
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

    .upcoming-item {
        background: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border-left: 4px solid #667eea;
    }

    .upcoming-title {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .upcoming-date {
        font-size: 12px;
        color: #6c757d;
    }

    .status-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
    }

    .status-hoan_thanh { background: #d4edda; color: #155724; }
    .status-dang_thuc_hien { background: #fff3cd; color: #856404; }
    .status-chua_thuc_hien { background: #f8f9fa; color: #6c757d; }
    .status-qua_han { background: #f8d7da; color: #721c24; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="stats-card">
                <h4 class="mb-0">
                    <i class="fas fa-user-circle text-primary me-2"></i>
                    Chào mừng, {{ Auth::user()->Ho_ten }}!
                </h4>
                <p class="text-muted mb-0">Đây là tổng quan về công việc của bạn hôm nay</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stats-number">{{ $kpiStats['total'] }}</div>
                <div class="stats-label">Tổng KPI</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-number">{{ $kpiStats['completed'] }}</div>
                <div class="stats-label">KPI hoàn thành</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-number">{{ $kpiStats['in_progress'] }}</div>
                <div class="stats-label">Đang thực hiện</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stats-number">{{ $kpiStats['overdue'] }}</div>
                <div class="stats-label">Quá hạn</div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row">
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-chart-pie"></i>
                    Thống kê KPI theo trạng thái
                </div>
                <canvas id="kpiStatusChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-chart-line"></i>
                    KPI được phân công theo tháng
                </div>
                <canvas id="kpiMonthlyChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Upcoming Deadlines -->
    <div class="row">
        <div class="col-md-6">
            <div class="stats-card">
                <h5 class="mb-3">
                    <i class="fas fa-calendar-alt text-warning me-2"></i>
                    KPI sắp đến hạn
                </h5>
                @forelse($upcomingKpis as $kpi)
                <div class="upcoming-item">
                    <div class="upcoming-title">{{ $kpi->kpi->Ten_kpi }}</div>
                    <div class="upcoming-date">
                        <i class="fas fa-calendar me-1"></i>
                        Hạn: {{ \Carbon\Carbon::parse($kpi->Ngay_ketthuc)->format('d/m/Y') }}
                        <span class="status-badge status-{{ $kpi->Trang_thai }} ms-2">
                            {{ ucfirst(str_replace('_', ' ', $kpi->Trang_thai)) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-3">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <p>Không có KPI nào sắp đến hạn</p>
                </div>
                @endforelse
            </div>
        </div>
        <div class="col-md-6">
            <div class="stats-card">
                <h5 class="mb-3">
                    <i class="fas fa-tasks text-info me-2"></i>
                    Nhiệm vụ sắp đến hạn
                </h5>
                @forelse($upcomingTasks as $task)
                <div class="upcoming-item">
                    <div class="upcoming-title">{{ $task->Ten_task }}</div>
                    <div class="upcoming-date">
                        <i class="fas fa-calendar me-1"></i>
                        Hạn: {{ \Carbon\Carbon::parse($task->Ngay_het_han)->format('d/m/Y') }}
                        <span class="status-badge status-{{ $task->Trang_thai }} ms-2">
                            {{ ucfirst(str_replace('_', ' ', $task->Trang_thai)) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-3">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <p>Không có nhiệm vụ nào sắp đến hạn</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Charts data
    const kpiStatusData = @json($kpiByStatus);
    const kpiMonthlyData = @json($kpiByMonth);

    // Status Chart
    const statusCtx = document.getElementById('kpiStatusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(kpiStatusData).map(key => key.replace('_', ' ')),
            datasets: [{
                data: Object.values(kpiStatusData),
                backgroundColor: ['#ffc107', '#17a2b8', '#28a745', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Monthly Chart
    const monthlyCtx = document.getElementById('kpiMonthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: Object.keys(kpiMonthlyData).map(month => `Tháng ${month}`),
            datasets: [{
                label: 'Số lượng KPI',
                data: Object.values(kpiMonthlyData),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush

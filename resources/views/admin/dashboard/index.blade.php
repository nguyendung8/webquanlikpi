@extends('layouts.dashboard')

@section('title', 'Trang chủ')
@section('page-title', 'Trang chủ')
@section('search-placeholder', 'Tìm kiếm...')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        text-align: center;
        transition: transform 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--card-color), var(--card-color-light));
    }

    .stat-card.active-kpis {
        --card-color: #667eea;
        --card-color-light: #764ba2;
    }

    .stat-card.total-users {
        --card-color: #43e97b;
        --card-color-light: #38f9d7;
    }

    .stat-card.attention-kpis {
        --card-color: #ffc107;
        --card-color-light: #fd7e14;
    }

    .stat-card.average-progress {
        --card-color: #4facfe;
        --card-color-light: #00f2fe;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 24px;
        color: white;
    }

    .stat-icon.active-kpis {
        background: linear-gradient(135deg, #eac066 0%, #F66600 100%);
    }

    .stat-icon.total-users {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    .stat-icon.attention-kpis {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    }

    .stat-icon.average-progress {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .stat-number {
        font-size: 32px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 14px;
        color: #6c757d;
        font-weight: 500;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 30px;
    }

    .chart-container {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
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

    .chart-container canvas {
        max-height: 300px !important;
        max-width: 100% !important;
    }

    .welcome-section {
        background: linear-gradient(135deg, #eac066 0%, #F66600 100%);
        color: white;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        text-align: center;
    }

    .welcome-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .welcome-subtitle {
        font-size: 16px;
        opacity: 0.9;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .quick-action-btn {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        padding: 15px 20px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
    }

    .quick-action-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<!-- Welcome Section -->
<div class="welcome-section">
    <h1 class="welcome-title">Chào mừng trở lại, {{ Auth::user()->Ho_ten }}!</h1>
    <p class="welcome-subtitle">Quản lý hiệu quả hệ thống KPI của bạn</p>

    <div class="quick-actions">
        <a href="{{ route('kpi.index') }}" class="quick-action-btn">
            <i class="fas fa-chart-line"></i>
            Quản lý KPI
        </a>
        <a href="{{ route('users.index') }}" class="quick-action-btn">
            <i class="fas fa-users"></i>
            Quản lý người dùng
        </a>
        <a href="{{ route('reports.index') }}" class="quick-action-btn">
            <i class="fas fa-chart-bar"></i>
            Báo cáo
        </a>
        <a href="{{ route('activity.index') }}" class="quick-action-btn">
            <i class="fas fa-history"></i>
            Nhật ký hoạt động
        </a>
    </div>
</div>

<!-- Thống kê tổng quan -->
<div class="stats-grid">
    <div class="stat-card active-kpis">
        <div class="stat-icon active-kpis">
            <i class="fas fa-bullseye"></i>
        </div>
        <div class="stat-number">{{ $stats['active_kpis'] }}</div>
        <div class="stat-label">KPI đang hoạt động</div>
    </div>

    <div class="stat-card total-users">
        <div class="stat-icon total-users">
            <i class="fas fa-user-plus"></i>
        </div>
        <div class="stat-number">{{ $stats['total_users'] }}</div>
        <div class="stat-label">Số lượng người dùng</div>
    </div>

    <div class="stat-card attention-kpis">
        <div class="stat-icon attention-kpis">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="stat-number">{{ $stats['attention_kpis'] }}</div>
        <div class="stat-label">KPIs cần chú ý</div>
    </div>

    <div class="stat-card average-progress">
        <div class="stat-icon average-progress">
            <i class="fas fa-chart-bar"></i>
        </div>
        <div class="stat-number">{{ $stats['average_progress'] }}%</div>
        <div class="stat-label">Tiến độ trung bình</div>
    </div>
</div>

<!-- Biểu đồ thống kê -->
<div class="charts-grid">
    <!-- Số lượng KPI theo phòng ban -->
    <div class="chart-container">
        <h3 class="chart-title">
            <i class="fas fa-chart-pie"></i>
            Số lượng KPI theo phòng ban
        </h3>
        <canvas id="kpiByDepartmentChart"></canvas>
    </div>

    <!-- Tỷ lệ hoàn thành theo phòng ban -->
    <div class="chart-container">
        <h3 class="chart-title">
            <i class="fas fa-chart-bar"></i>
            Tỷ lệ hoàn thành theo phòng ban
        </h3>
        <canvas id="completionByDepartmentChart"></canvas>
    </div>

    <!-- Mức độ hoàn thành đạt tiến độ -->
    <div class="chart-container">
        <h3 class="chart-title">
            <i class="fas fa-chart-doughnut"></i>
            Mức độ hoàn thành đạt tiến độ
        </h3>
        <canvas id="progressStatusChart"></canvas>
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

        setTimeout(() => {
            const alert = document.getElementById(alertId);
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    }

    // Màu sắc cho các biểu đồ
    const colors = ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe', '#00f2fe', '#43e97b', '#38f9d7'];

    // 1. Số lượng KPI theo phòng ban
    const kpiByDepartmentData = @json($kpiByDepartment);
    const departmentLabels = kpiByDepartmentData.map(item => item.Ten_phongban);
    const departmentCounts = kpiByDepartmentData.map(item => item.count);

    new Chart(document.getElementById('kpiByDepartmentChart').getContext('2d'), {
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
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: { size: 12 }
                    }
                }
            }
        }
    });

    // 2. Tỷ lệ hoàn thành theo phòng ban
    const completionByDepartmentData = @json($completionByDepartment);
    const completionLabels = completionByDepartmentData.map(item => item.Ten_phongban);
    const completionValues = completionByDepartmentData.map(item => parseFloat(item.avg_completion || 0));

    new Chart(document.getElementById('completionByDepartmentChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: completionLabels,
            datasets: [{
                label: 'Tỷ lệ hoàn thành (%)',
                data: completionValues,
                backgroundColor: colors.slice(0, completionLabels.length),
                borderWidth: 0,
                borderRadius: 8
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
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    });

    // 3. Mức độ hoàn thành đạt tiến độ
    const progressStatusData = @json($progressStatus);
    const statusLabels = progressStatusData.map(item => {
        const statusMap = {
            'cho_duyet': 'Chờ duyệt',
            'dat': 'Đạt',
            'khong_dat': 'Không đạt'
        };
        return statusMap[item.Trang_thai] || item.Trang_thai;
    });
    const statusCounts = progressStatusData.map(item => item.count);

    new Chart(document.getElementById('progressStatusChart').getContext('2d'), {
        type: 'pie',
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
                        padding: 20,
                        usePointStyle: true,
                        font: { size: 12 }
                    }
                }
            }
        }
    });
</script>
@endpush

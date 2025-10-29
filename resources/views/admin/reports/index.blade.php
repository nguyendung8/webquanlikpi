@extends('layouts.dashboard')

@section('title', 'Báo cáo')
@section('page-title', 'Báo cáo')
@section('search-placeholder', 'Tìm báo cáo...')

@push('styles')
<style>
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

    .chart-container canvas {
        max-height: 300px !important;
        max-width: 100% !important;
    }

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
    }

    .stat-card:hover {
        transform: translateY(-5px);
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

    .stat-icon.kpi {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-icon.users {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .stat-icon.tasks {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .stat-icon.evaluation {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
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
</style>
@endpush

@section('content')
<!-- Thống kê tổng quan -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon kpi">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-number">{{ \App\Models\Kpi::count() }}</div>
        <div class="stat-label">Tổng số KPI</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon users">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-number">{{ \App\Models\User::count() }}</div>
        <div class="stat-label">Tổng số người dùng</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon tasks">
            <i class="fas fa-tasks"></i>
        </div>
        <div class="stat-number">{{ \App\Models\Tasks::count() }}</div>
        <div class="stat-label">Tổng số nhiệm vụ</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon evaluation">
            <i class="fas fa-star"></i>
        </div>
        <div class="stat-number">{{ \App\Models\DanhgiaKpi::count() }}</div>
        <div class="stat-label">Tổng số đánh giá</div>
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

    <!-- KPI theo độ ưu tiên -->
    <div class="chart-container">
        <h3 class="chart-title">
            <i class="fas fa-chart-bar"></i>
            KPI theo độ ưu tiên
        </h3>
        <canvas id="kpiByPriorityChart"></canvas>
    </div>

    <!-- Đánh giá KPI theo kết quả -->
    <div class="chart-container">
        <h3 class="chart-title">
            <i class="fas fa-chart-doughnut"></i>
            Đánh giá KPI theo kết quả
        </h3>
        <canvas id="danhgiaByResultChart"></canvas>
    </div>

    <!-- Tasks theo trạng thái -->
    <div class="chart-container">
        <h3 class="chart-title">
            <i class="fas fa-chart-pie"></i>
            Nhiệm vụ theo trạng thái
        </h3>
        <canvas id="tasksByStatusChart"></canvas>
    </div>

    <!-- Người dùng theo phòng ban -->
    <div class="chart-container">
        <h3 class="chart-title">
            <i class="fas fa-chart-doughnut"></i>
            Người dùng theo phòng ban
        </h3>
        <canvas id="usersByDepartmentChart"></canvas>
    </div>

    <!-- KPI được tạo theo tháng -->
    <div class="chart-container">
        <h3 class="chart-title">
            <i class="fas fa-chart-line"></i>
            KPI được tạo theo tháng
        </h3>
        <canvas id="kpiByMonthChart"></canvas>
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

    // 1. KPI theo trạng thái
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
                        padding: 20,
                        usePointStyle: true,
                        font: { size: 12 }
                    }
                }
            }
        }
    });

    // 2. KPI theo độ ưu tiên
    const kpiByPriorityData = @json($kpiByPriority);
    const priorityLabels = kpiByPriorityData.map(item => item.Do_uu_tien || 'Không xác định');
    const priorityCounts = kpiByPriorityData.map(item => item.count);

    new Chart(document.getElementById('kpiByPriorityChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: priorityLabels,
            datasets: [{
                label: 'Số lượng KPI',
                data: priorityCounts,
                backgroundColor: colors.slice(0, priorityLabels.length),
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
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // 3. Đánh giá KPI theo kết quả
    const danhgiaByResultData = @json($danhgiaByResult);
    const resultLabels = danhgiaByResultData.map(item => {
        const resultMap = {
            'cho_duyet': 'Chờ duyệt',
            'dat': 'Đạt',
            'khong_dat': 'Không đạt'
        };
        return resultMap[item.Trang_thai] || item.Trang_thai;
    });
    const resultCounts = danhgiaByResultData.map(item => item.count);

    new Chart(document.getElementById('danhgiaByResultChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: resultLabels,
            datasets: [{
                data: resultCounts,
                backgroundColor: colors.slice(0, resultLabels.length),
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

    // 4. Tasks theo trạng thái
    const tasksByStatusData = @json($tasksByStatus);
    const taskStatusLabels = tasksByStatusData.map(item => {
        const statusMap = {
            'chua_bat_dau': 'Chưa bắt đầu',
            'dang_thuc_hien': 'Đang thực hiện',
            'hoan_thanh': 'Hoàn thành'
        };
        return statusMap[item.trang_thai] || item.trang_thai;
    });
    const taskStatusCounts = tasksByStatusData.map(item => item.count);

    new Chart(document.getElementById('tasksByStatusChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: taskStatusLabels,
            datasets: [{
                data: taskStatusCounts,
                backgroundColor: colors.slice(0, taskStatusLabels.length),
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

    // 5. Người dùng theo phòng ban
    const usersByDepartmentData = @json($usersByDepartment);
    const departmentLabels = usersByDepartmentData.map(item => item.Ten_phongban);
    const departmentCounts = usersByDepartmentData.map(item => item.count);

    new Chart(document.getElementById('usersByDepartmentChart').getContext('2d'), {
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

    // 6. KPI được tạo theo tháng
    const kpiByMonthData = @json($kpiByMonth);
    const monthLabels = kpiByMonthData.map(item => {
        const date = new Date(item.month + '-01');
        return date.toLocaleDateString('vi-VN', { month: 'short', year: 'numeric' });
    });
    const monthCounts = kpiByMonthData.map(item => item.count);

    new Chart(document.getElementById('kpiByMonthChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Số KPI tạo mới',
                data: monthCounts,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
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

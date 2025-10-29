@extends('layouts.dashboard')

@section('title', 'Quản lý KPIs')

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
        max-height: 300px;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-chua_thuc_hien { background: #f8f9fa; color: #6c757d; }
    .status-dang_thuc_hien { background: #fff3cd; color: #856404; }
    .status-hoan_thanh { background: #d4edda; color: #155724; }
    .status-qua_han { background: #f8d7da; color: #721c24; }

    .priority-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
    }

    .priority-Rất_gấp { background: #dc3545; color: white; }
    .priority-Gấp { background: #fd7e14; color: white; }
    .priority-Trung_Bình { background: #ffc107; color: #212529; }
    .priority-Không { background: #6c757d; color: white; }

    .action-btn {
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-view { background: #17a2b8; color: white; }
    .btn-progress { background: #6c757d; color: white; }
    .btn-delete { background: #dc3545; color: white; }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .submission-item {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        background: #f8f9fa;
    }

    .submission-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 10px;
    }

    .submission-date {
        font-size: 12px;
        color: #6c757d;
    }

    .submission-item {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background: #f8f9fa;
        transition: all 0.3s;
    }

    .submission-item:hover {
        background: #e9ecef;
        transform: translateY(-2px);
    }

    .submission-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 8px;
    }

    .submission-date {
        font-size: 12px;
        color: #6c757d;
        font-weight: 500;
    }

    .evaluation-form {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
    }

    .input-group-text {
        background-color: #e9ecef;
        border-color: #ced4da;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
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
                    KPI được tạo theo tháng
                </div>
                <canvas id="kpiMonthlyChart"></canvas>
            </div>
        </div>
    </div>

    <!-- KPI Management Section -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-clipboard-list text-primary"></i>
                Danh sách phân công KPI
            </h5>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Thêm KPI mới
                </button>
            </div>
        </div>

        <div class="card-body">
            <!-- Search and Filter -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control me-2"
                               placeholder="Tìm kiếm KPI..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                <div class="col-md-3">
                    <select class="form-select" onchange="changePerPage(this.value)">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 bản ghi/trang</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 bản ghi/trang</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 bản ghi/trang</option>
                    </select>
                </div>
            </div>

            <!-- KPI Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>STT</th>
                            <th>Tên KPI</th>
                            <th>Người thực hiện</th>
                            <th>Phòng ban</th>
                            <th>Mức độ ưu tiên</th>
                            <th>Trạng thái</th>
                            <th>Tiến độ</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($phancongKpis as $index => $phancong)
                        <tr>
                            <td>{{ $phancongKpis->firstItem() + $index }}</td>
                            <td>
                                <div>
                                    <strong>{{ $phancong->kpi->Ten_kpi }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $phancong->kpi->Chi_tieu }} {{ $phancong->kpi->Donvi_tinh }}</small>
                                </div>
                            </td>
                            <td>{{ $phancong->user ? $phancong->user->Ho_ten : 'N/A' }}</td>
                            <td>{{ $phancong->phongban ? $phancong->phongban->Ten_phongban : 'N/A' }}</td>
                            <td>
                                <span class="priority-badge priority-{{ $phancong->kpi->Do_uu_tien }}">
                                    {{ $phancong->kpi->Do_uu_tien }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $phancong->Trang_thai }}">
                                    {{ ucfirst(str_replace('_', ' ', $phancong->Trang_thai)) }}
                                </span>
                            </td>
                            <td>
                                @if($phancong->danhgiaKpi)
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar"
                                             style="width: {{ $phancong->danhgiaKpi->Ty_le_hoanthanh }}%">
                                            {{ $phancong->danhgiaKpi->Ty_le_hoanthanh }}%
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Chưa có dữ liệu</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="action-btn btn-view" style="margin-right: 5px" onclick="viewSubmissions({{ $phancong->ID_Phancong }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn btn-delete" onclick="deleteKpi({{ $phancong->ID_Phancong }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Chưa có KPI nào được phân công</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Hiển thị {{ $phancongKpis->firstItem() }} - {{ $phancongKpis->lastItem() }}
                    trong tổng số {{ $phancongKpis->total() }} bản ghi
                </div>
                <div>
                    {{ $phancongKpis->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add KPI Modal -->
<div class="modal fade" id="addKpiModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus text-primary"></i>
                    Thêm KPI mới
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addKpiForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tên KPI <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="Ten_kpi" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Chỉ tiêu <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="Chi_tieu" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Đơn vị tính <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="Donvi_tinh" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Mức độ ưu tiên <span class="text-danger">*</span></label>
                                <select class="form-select" name="Do_uu_tien" required>
                                    <option value="">Chọn mức độ ưu tiên</option>
                                    <option value="Rất gấp">Rất gấp</option>
                                    <option value="Gấp">Gấp</option>
                                    <option value="Trung Bình">Trung Bình</option>
                                    <option value="Không">Không</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="Mo_ta" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Loại KPI <span class="text-danger">*</span></label>
                        <select class="form-select" name="ID_loai_kpi" required>
                            <option value="">Chọn loại KPI</option>
                            @foreach(\App\Models\LoaiKpi::all() as $loai)
                                <option value="{{ $loai->ID_loai_kpi }}">{{ $loai->Ten_loai_kpi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phân công cho <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="assignment_type" value="user" id="assignUser" checked>
                            <label class="form-check-label" for="assignUser">
                                Người dùng cụ thể
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="assignment_type" value="department" id="assignDept">
                            <label class="form-check-label" for="assignDept">
                                Cả phòng ban
                            </label>
                        </div>
                    </div>

                    <div id="userSelection" class="mb-3">
                        <label class="form-label">Chọn người dùng <span class="text-danger">*</span></label>
                        <select class="form-select" name="ID_user">
                            <option value="">Chọn người dùng</option>
                            @foreach(\App\Models\User::where('Trang_thai', 'hoat_dong')->where('ID_quyen', 3)->get() as $user)
                                <option value="{{ $user->ID_user }}">{{ $user->Ho_ten }} ({{ $user->phongban->Ten_phongban ?? 'N/A' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="deptSelection" class="mb-3" style="display: none;">
                        <label class="form-label">Chọn phòng ban <span class="text-danger">*</span></label>
                        <select class="form-select" name="ID_phongban">
                            <option value="">Chọn phòng ban</option>
                            @foreach(\App\Models\Phongban::all() as $phongban)
                                <option value="{{ $phongban->ID_phongban }}">{{ $phongban->Ten_phongban }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="Ngay_batdau" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="Ngay_ketthuc" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Tạo KPI
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Submissions Modal -->
<div class="modal fade" id="submissionsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye text-info"></i>
                    Bài nộp của nhân viên
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="submissionsContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Progress Update Modal -->
<div class="modal fade" id="progressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cập nhật tiến độ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="progressForm">
                    <input type="hidden" id="progress_kpi_id">
                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-select" id="progress_status" required>
                            <option value="chua_thuc_hien">Chưa thực hiện</option>
                            <option value="dang_thuc_hien">Đang thực hiện</option>
                            <option value="hoan_thanh">Hoàn thành</option>
                            <option value="qua_han">Quá hạn</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" onclick="submitProgress()">Cập nhật</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Charts
    const kpiStatusData = @json($kpiStats['byStatus']);
    const kpiMonthlyData = @json($kpiStats['byMonth']);

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

    function changePerPage(value) {
        const url = new URL(window.location);
        url.searchParams.set('per_page', value);
        window.location.href = url.toString();
    }

    function openAddModal() {
        document.getElementById('addKpiForm').reset();
        new bootstrap.Modal(document.getElementById('addKpiModal')).show();
    }

    function updateProgress(id) {
        document.getElementById('progress_kpi_id').value = id;
        new bootstrap.Modal(document.getElementById('progressModal')).show();
    }

    function viewSubmissions(id) {
        fetch(`/manager/kpi/${id}/submissions`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Lưu target để tính toán
                    window.currentPhancongTarget = data.phancong.kpi.Chi_tieu;
                    displaySubmissions(data.phancong, data.submissions);
                    new bootstrap.Modal(document.getElementById('submissionsModal')).show();
                } else {
                    showAlert('Không thể tải dữ liệu bài nộp', 'error');
                }
            })
            .catch(error => {
                showAlert('Có lỗi xảy ra khi tải bài nộp', 'error');
            });
    }

    function displaySubmissions(phancong, submissions) {
        const content = document.getElementById('submissionsContent');

        let html = `
            <div class="mb-4">
                <h6><strong>KPI:</strong> ${phancong.kpi.Ten_kpi}</h6>
                <p><strong>Người thực hiện:</strong> ${phancong.user ? phancong.user.Ho_ten : 'N/A'}</p>
                <p><strong>Chỉ tiêu:</strong> ${phancong.kpi.Chi_tieu} ${phancong.kpi.Donvi_tinh}</p>
                <p><strong>Mô tả:</strong> ${phancong.kpi.Mo_ta || 'Không có mô tả'}</p>
            </div>
        `;

        if (submissions.length > 0) {
            html += '<h6>Danh sách bài nộp:</h6>';

            submissions.forEach((submission, index) => {
                const submitDate = new Date(submission.Ngay_gui).toLocaleString('vi-VN');
                const fileHtml = submission.File_name ?
                    `<a href="/manager/kpi/file/${submission.ID_dulieu}/download" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-download me-1"></i> ${submission.File_name}
                    </a>` :
                    '<span class="text-muted">Không có file đính kèm</span>';

                html += `
                    <div class="submission-item">
                        <div class="submission-header">
                            <strong>Bài nộp #${index + 1}</strong>
                            <span class="submission-date">
                                <i class="fas fa-clock me-1"></i>
                                ${submitDate}
                            </span>
                        </div>
                        <div class="mb-2">
                            <strong>Minh chứng:</strong>
                            <p class="mb-1">${submission.Minh_chung || 'Không có mô tả'}</p>
                        </div>
                        <div class="mb-2">
                            <strong>File đính kèm:</strong>
                            <div class="mt-1">${fileHtml}</div>
                        </div>
                        <div>
                            <strong>Người nộp:</strong> ${submission.nguoigui ? submission.nguoigui.Ho_ten : 'N/A'}
                        </div>
                    </div>
                `;
            });

            // Form đánh giá với dữ liệu hiện tại
            const currentEvaluation = phancong.danhgia_kpi;
            const isCompleted = currentEvaluation && parseFloat(currentEvaluation.Ty_le_hoanthanh) >= 100;
            const disabledAttr = isCompleted ? 'disabled' : '';
            const disabledClass = isCompleted ? 'opacity-75' : '';

            html += `
                <div class="mt-4 p-3 bg-light rounded">
                    <h6><i class="fas fa-clipboard-check text-success me-2"></i>Đánh giá KPI:</h6>
                    ${isCompleted ? '<div class="alert alert-success mb-3"><i class="fas fa-check-circle me-2"></i>KPI đã hoàn thành 100%, không thể chỉnh sửa!</div>' : ''}
                    <form id="evaluationForm">
                        <input type="hidden" id="evaluation_kpi_id" value="${phancong.ID_Phancong}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Kết quả thực hiện <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control ${disabledClass}" id="evaluation_result"
                                               min="0" step="0.01" required placeholder="Nhập số lượng"
                                               value="${currentEvaluation ? currentEvaluation.Ketqua_thuchien || '' : ''}" ${disabledAttr}>
                                        <span class="input-group-text">${phancong.kpi.Donvi_tinh}</span>
                                    </div>
                                    <small class="form-text text-muted">Số lượng đã hoàn thành</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Tỷ lệ hoàn thành (%) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control ${disabledClass}" id="evaluation_percentage"
                                           min="0" max="100" step="0.01" required
                                           value="${currentEvaluation ? currentEvaluation.Ty_le_hoanthanh || '' : ''}" ${disabledAttr}>
                                    <small class="form-text text-muted">Tự động tính: (Kết quả / Chỉ tiêu) × 100</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                    <select class="form-select ${disabledClass}" id="evaluation_status" required ${disabledAttr}>
                                        <option value="cho_duyet" ${currentEvaluation && currentEvaluation.Trang_thai === 'cho_duyet' ? 'selected' : ''}>Chờ duyệt</option>
                                        <option value="dat" ${currentEvaluation && currentEvaluation.Trang_thai === 'dat' ? 'selected' : ''}>Đạt</option>
                                        <option value="khong_dat" ${currentEvaluation && currentEvaluation.Trang_thai === 'khong_dat' ? 'selected' : ''}>Không đạt</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nhận xét</label>
                            <textarea class="form-control ${disabledClass}" id="evaluation_comment" rows="3"
                                      placeholder="Nhận xét về kết quả thực hiện..." ${disabledAttr}>${currentEvaluation ? currentEvaluation.Nhan_xet || '' : ''}</textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-success" onclick="submitEvaluation()" ${disabledAttr}>
                                <i class="fas fa-check me-1"></i> ${currentEvaluation ? 'Cập nhật đánh giá' : 'Tạo đánh giá'}
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="calculatePercentage()" ${disabledAttr}>
                                <i class="fas fa-calculator me-1"></i> Tính tỷ lệ tự động
                            </button>
                        </div>
                    </form>
                </div>
            `;
        } else {
            html += `
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Chưa có bài nộp nào từ nhân viên.
                </div>
            `;
        }

        content.innerHTML = html;
    }

    function deleteKpi(id) {
        if (confirm('Bạn có chắc chắn muốn xóa KPI này?')) {
            fetch(`/manager/kpi/${id}`, {
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
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert(data.message, 'error');
                }
            })
            .catch(error => {
                showAlert('Có lỗi xảy ra khi xóa KPI', 'error');
            });
        }
    }

    function submitProgress() {
        const id = document.getElementById('progress_kpi_id').value;
        const status = document.getElementById('progress_status').value;

        fetch(`/manager/kpi/${id}/progress`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ Trang_thai: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('progressModal')).hide();
                setTimeout(() => location.reload(), 2000);
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            showAlert('Có lỗi xảy ra khi cập nhật tiến độ', 'error');
        });
    }

    function submitEvaluation() {
        const id = document.getElementById('evaluation_kpi_id').value;
        const result = document.getElementById('evaluation_result').value;
        const percentage = document.getElementById('evaluation_percentage').value;
        const status = document.getElementById('evaluation_status').value;
        const comment = document.getElementById('evaluation_comment').value;

        if (!result || !percentage) {
            showAlert('Vui lòng nhập đầy đủ thông tin đánh giá', 'error');
            return;
        }

        fetch(`/manager/kpi/${id}/evaluation`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                Ketqua_thuchien: result, // Thêm dòng này
                Ty_le_hoanthanh: percentage,
                Trang_thai: status,
                Nhan_xet: comment
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                setTimeout(() => location.reload(), 2000);
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            showAlert('Có lỗi xảy ra khi cập nhật đánh giá', 'error');
        });
    }

    function showAlert(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';

        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed"
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                <i class="${iconClass} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        // Remove existing alerts first
        const existingAlerts = document.querySelectorAll('.alert.position-fixed');
        existingAlerts.forEach(alert => alert.remove());

        // Insert new alert
        document.body.insertAdjacentHTML('beforeend', alertHtml);

        // Auto remove after 5 seconds
        setTimeout(() => {
            const alert = document.querySelector('.alert.position-fixed');
            if (alert) {
                alert.remove();
            }
        }, 5000);
    }

    function calculatePercentage() {
        const resultInput = document.getElementById('evaluation_result');
        const percentageInput = document.getElementById('evaluation_percentage');
        const phancongId = document.getElementById('evaluation_kpi_id').value;

        const result = parseFloat(resultInput.value);
        if (!result || result <= 0) {
            showAlert('Vui lòng nhập kết quả thực hiện trước', 'warning');
            return;
        }

        // Lấy chỉ tiêu từ phancong data (cần lưu trong biến global)
        const target = window.currentPhancongTarget || 1; // Fallback value
        const percentage = (result / target) * 100;

        percentageInput.value = Math.min(percentage, 100).toFixed(2);
        showAlert(`Đã tính tỷ lệ: ${percentage.toFixed(2)}%`, 'success');
    }

    // Handle assignment type change
    document.addEventListener('DOMContentLoaded', function() {
        const assignUser = document.getElementById('assignUser');
        const assignDept = document.getElementById('assignDept');
        const userSelection = document.getElementById('userSelection');
        const deptSelection = document.getElementById('deptSelection');

        assignUser.addEventListener('change', function() {
            if (this.checked) {
                userSelection.style.display = 'block';
                deptSelection.style.display = 'none';
                document.querySelector('select[name="ID_user"]').required = true;
                document.querySelector('select[name="ID_phongban"]').required = false;
            }
        });

        assignDept.addEventListener('change', function() {
            if (this.checked) {
                userSelection.style.display = 'none';
                deptSelection.style.display = 'block';
                document.querySelector('select[name="ID_user"]').required = false;
                document.querySelector('select[name="ID_phongban"]').required = true;
            }
        });

        // Handle form submission
        document.getElementById('addKpiForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            // Validate required fields based on assignment type
            const assignmentType = data.assignment_type;
            if (assignmentType === 'user' && !data.ID_user) {
                showAlert('Vui lòng chọn người dùng', 'error');
                return;
            }
            if (assignmentType === 'department' && !data.ID_phongban) {
                showAlert('Vui lòng chọn phòng ban', 'error');
                return;
            }

            fetch('/manager/kpi', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);

                // Check if response is HTML (error page)
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('text/html')) {
                    return response.text().then(html => {
                        console.log('HTML Response:', html);
                        throw new Error('Server returned HTML instead of JSON. Check server logs.');
                    });
                }

                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);

                if (data.success) {
                    showAlert(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('addKpiModal')).hide();
                    // Delay reload để user có thể thấy alert
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showAlert(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Có lỗi xảy ra khi tạo KPI: ' + error.message, 'error');
            });
        });
    });
</script>
@endpush

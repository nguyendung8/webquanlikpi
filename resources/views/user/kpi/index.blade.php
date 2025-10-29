@extends('layouts.dashboard')

@section('title', 'KPI của tôi')

@push('styles')
<style>
    .kpi-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border-left: 4px solid #667eea;
        transition: transform 0.3s;
        cursor: pointer;
    }

    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .kpi-card-header {
        pointer-events: none;
    }

    .kpi-card .btn {
        pointer-events: auto;
    }

    .kpi-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .kpi-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .kpi-progress {
        font-size: 14px;
        color: #6c757d;
    }

    .kpi-deadline {
        font-size: 12px;
        color: #dc3545;
        font-weight: 500;
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

    .evaluation-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
    }

    .evaluation-cho_duyet { background: #fff3cd; color: #856404; }
    .evaluation-dat { background: #d4edda; color: #155724; }
    .evaluation-khong_dat { background: #f8d7da; color: #721c24; }

    .stats-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
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

    .progress-bar-custom {
        height: 20px;
        background-color: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
        transition: width 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        font-weight: 500;
    }

    /* Thêm vào phần styles */
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
    }

    .submission-date {
        font-size: 12px;
        color: #6c757d;
        font-weight: 500;
    }

    .submission-content {
        margin-bottom: 10px;
    }

    .submission-file {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        background: white;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        text-decoration: none;
        color: #495057;
        transition: all 0.3s;
    }

    .submission-file:hover {
        background: #e9ecef;
        color: #495057;
        text-decoration: none;
    }

    .file-icon {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-submissions {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }

    .empty-submissions i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-secondary:disabled {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .completed-kpi {
        border-left-color: #28a745 !important;
        background: linear-gradient(135deg, #f8fff8 0%, #ffffff 100%);
    }

    .completed-kpi .kpi-title {
        color: #28a745;
    }

    .completed-kpi .progress-fill {
        background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
    }

    /* Thêm vào phần styles */
    .evaluation-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        border-left: 4px solid #28a745; /* Màu xanh lá */
        margin-bottom: 20px;
    }

    .evaluation-card {
        background: white;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .evaluation-item {
        margin-bottom: 10px;
    }

    .evaluation-value {
        font-weight: 600;
        color: #2c3e50;
    }

    .evaluation-status {
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 500;
    }

    .evaluation-status.cho_duyet { background: #fff3cd; color: #856404; }
    .evaluation-status.dat { background: #d4edda; color: #155724; }
    .evaluation-status.khong_dat { background: #f8d7da; color: #721c24; }

    .evaluation-comment {
        margin-top: 15px;
        padding-top: 10px;
        border-top: 1px solid #e9ecef;
    }

    .comment-content {
        font-style: italic;
        color: #6c757d;
        font-size: 13px;
    }

    /* Thêm vào phần styles */
    .evaluation-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        border-left: 4px solid #28a745;
    }

    .evaluation-card {
        background: white;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .evaluation-item {
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .evaluation-item:last-child {
        border-bottom: none;
    }

    .evaluation-value {
        font-weight: 600;
        color: #2c3e50;
    }

    .evaluation-status {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
    }

    .evaluation-cho_duyet {
        background: #fff3cd;
        color: #856404;
    }

    .evaluation-dat {
        background: #d4edda;
        color: #155724;
    }

    .evaluation-khong_dat {
        background: #f8d7da;
        color: #721c24;
    }

    .evaluation-date {
        font-size: 12px;
        color: #6c757d;
    }

    .evaluation-comment {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
    }

    .comment-content {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 6px;
        margin-top: 8px;
        font-style: italic;
        color: #495057;
    }

    .alert-info {
        background-color: #d1ecf1;
        border-color: #bee5eb;
        color: #0c5460;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="stats-card">
                <h4 class="mb-0">
                    <i class="fas fa-clipboard-list text-primary me-2"></i>
                    KPI của tôi
                </h4>
                <p class="text-muted mb-0">Danh sách KPI được phân công và theo dõi tiến độ</p>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-3">
        <div class="col-md-6">
            <form method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2"
                       placeholder="Tìm kiếm KPI..." value="{{ $search }}">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        <div class="col-md-3">
            <select class="form-select" onchange="filterByStatus(this.value)">
                <option value="">Tất cả trạng thái</option>
                <option value="chua_thuc_hien" {{ $status == 'chua_thuc_hien' ? 'selected' : '' }}>Chưa thực hiện</option>
                <option value="dang_thuc_hien" {{ $status == 'dang_thuc_hien' ? 'selected' : '' }}>Đang thực hiện</option>
                <option value="hoan_thanh" {{ $status == 'hoan_thanh' ? 'selected' : '' }}>Hoàn thành</option>
                <option value="qua_han" {{ $status == 'qua_han' ? 'selected' : '' }}>Quá hạn</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" onchange="changePerPage(this.value)">
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 bản ghi/trang</option>
                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 bản ghi/trang</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 bản ghi/trang</option>
            </select>
        </div>
    </div>

    <!-- KPI List -->
    <div class="row">
        @forelse($kpis as $phancong)
        <div class="col-md-6 mb-3">
            <div class="kpi-card {{ $phancong->danhgiaKpi && $phancong->danhgiaKpi->Ty_le_hoanthanh >= 100 ? 'completed-kpi' : '' }}" onclick="viewKpiDetail({{ $phancong->ID_Phancong }})">
                <div class="kpi-title kpi-card-header">{{ $phancong->kpi->Ten_kpi }}</div>

                <!-- Thêm badge hoàn thành nếu đạt 100% -->
                @if($phancong->danhgiaKpi && $phancong->danhgiaKpi->Ty_le_hoanthanh >= 100)
                    <div class="mb-2">
                        <span class="badge bg-success">
                            <i class="fas fa-trophy me-1"></i> Hoàn thành 100%
                        </span>
                    </div>
                @endif

                <!-- Phần còn lại giữ nguyên -->
                <div class="kpi-meta">
                    <div class="kpi-progress">
                        <i class="fas fa-target me-1"></i>
                        @if($phancong->danhgiaKpi && $phancong->danhgiaKpi->Ketqua_thuchien)
                            {{ $phancong->danhgiaKpi->Ketqua_thuchien }} / {{ $phancong->kpi->Chi_tieu }} {{ $phancong->kpi->Donvi_tinh }}
                        @else
                            Chưa đánh giá / {{ $phancong->kpi->Chi_tieu }} {{ $phancong->kpi->Donvi_tinh }}
                        @endif
                    </div>
                    <div class="kpi-deadline">
                        <i class="fas fa-calendar me-1"></i>
                        {{ \Carbon\Carbon::parse($phancong->Ngay_ketthuc)->format('d/m/Y') }}
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="progress-bar-custom mb-3">
                    <div class="progress-fill" style="width: {{ $phancong->danhgiaKpi ? $phancong->danhgiaKpi->Ty_le_hoanthanh ?? 0 : 0 }}%">
                        {{ $phancong->danhgiaKpi ? number_format($phancong->danhgiaKpi->Ty_le_hoanthanh, 2) : 0 }}%
                    </div>
                </div>

                <!-- Phần button action đã sửa ở trên -->
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="status-badge status-{{ $phancong->Trang_thai }}">
                            {{ ucfirst(str_replace('_', ' ', $phancong->Trang_thai)) }}
                        </span>
                        @if($phancong->danhgiaKpi)
                        <span class="evaluation-badge evaluation-{{ $phancong->danhgiaKpi->Trang_thai }} ms-2">
                            {{ ucfirst(str_replace('_', ' ', $phancong->danhgiaKpi->Trang_thai)) }}
                        </span>
                        @endif
                    </div>
                    <div class="d-flex gap-2" onclick="event.stopPropagation()">
                        <button class="btn btn-info btn-sm" onclick="viewSubmissions({{ $phancong->ID_Phancong }}, event)">
                            <i class="fas fa-eye me-1"></i> Xem bài nộp
                        </button>
                        @if($phancong->Trang_thai == 'qua_han')
                            <button class="btn btn-danger btn-sm" disabled title="KPI đã quá hạn">
                                <i class="fas fa-times-circle me-1"></i> Đã quá hạn
                            </button>
                        @elseif($phancong->danhgiaKpi && $phancong->danhgiaKpi->Ty_le_hoanthanh >= 100)
                            <button class="btn btn-secondary btn-sm" disabled title="KPI đã hoàn thành 100%">
                                <i class="fas fa-check-circle me-1"></i> Đã hoàn thành
                            </button>
                        @else
                            <button class="btn btn-primary btn-sm" onclick="openSubmitModal({{ $phancong->ID_Phancong }}, '{{ $phancong->kpi->Ten_kpi }}', '{{ $phancong->kpi->Chi_tieu }}', '{{ $phancong->kpi->Donvi_tinh }}', event)">
                                <i class="fas fa-upload me-1"></i> Nộp bài
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="stats-card text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Chưa có KPI nào được phân công</h5>
                <p class="text-muted">Bạn sẽ thấy các KPI được phân công ở đây</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($kpis->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $kpis->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<!-- Submit Modal -->
<div class="modal fade" id="submitModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-upload text-primary me-2"></i>
                    Nộp bài KPI
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="submitForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="phancong_id">

                    <div class="mb-3">
                        <label class="form-label">KPI</label>
                        <input type="text" class="form-control" id="kpi_name" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Minh chứng <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="minh_chung" rows="3"
                                  placeholder="Mô tả chi tiết về kết quả thực hiện..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">File đính kèm</label>
                        <input type="file" class="form-control" id="file_upload"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                        <small class="form-text text-muted">
                            Hỗ trợ: PDF, Word, Excel, hình ảnh (tối đa 10MB)
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i> Nộp bài
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
                    <i class="fas fa-eye text-info me-2"></i>
                    Danh sách bài nộp
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

<!-- KPI Detail Modal -->
<div class="modal fade" id="kpiDetailModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>
                    Chi tiết KPI
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="kpiDetailContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function filterByStatus(status) {
        const url = new URL(window.location);
        if (status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }
        window.location.href = url.toString();
    }

    function changePerPage(value) {
        const url = new URL(window.location);
        url.searchParams.set('per_page', value);
        window.location.href = url.toString();
    }

    // Sửa function openSubmitModal để kiểm tra điều kiện
    function openSubmitModal(id, name, target, unit, event) {
        if (event) {
            event.stopPropagation();
        }
        // Kiểm tra xem KPI đã hoàn thành 100% chưa
        const kpiCard = document.querySelector(`[onclick*="openSubmitModal(${id}"]`).closest('.kpi-card');

        // Kiểm tra trạng thái quá hạn
        const statusBadge = kpiCard.querySelector('.status-badge');
        if (statusBadge && statusBadge.classList.contains('status-qua_han')) {
            showAlert('KPI này đã quá hạn, không thể nộp bài!', 'warning');
            return;
        }

        const progressFill = kpiCard.querySelector('.progress-fill');
        const progressText = progressFill.textContent.trim();
        const progressValue = parseFloat(progressText.replace('%', ''));

        if (progressValue >= 100) {
            showAlert('KPI này đã hoàn thành 100%, không thể nộp bài thêm!', 'warning');
            return;
        }

        document.getElementById('phancong_id').value = id;
        document.getElementById('kpi_name').value = name;

        // Reset form
        document.getElementById('submitForm').reset();
        document.getElementById('phancong_id').value = id;
        document.getElementById('kpi_name').value = name;

        new bootstrap.Modal(document.getElementById('submitModal')).show();
    }

    function viewSubmissions(id, event) {
        if (event) {
            event.stopPropagation();
        }
        document.getElementById('phancong_id').value = id; // Pass the ID to the modal
        new bootstrap.Modal(document.getElementById('submissionsModal')).show();

        fetch(`/user/kpi/${id}/submissions`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displaySubmissions(data.phancong, data.submissions);
                } else {
                    showAlert('Không thể tải danh sách bài nộp', 'error');
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
                <p class="text-muted mb-0">Chỉ tiêu: ${phancong.kpi.Chi_tieu} ${phancong.kpi.Donvi_tinh}</p>
            </div>
        `;

        // Hiển thị đánh giá của manager nếu có
        if (phancong.danhgia_kpi) {
            const evaluation = phancong.danhgia_kpi;
            const evaluationDate = new Date(evaluation.Ngay_thamdinh).toLocaleString('vi-VN');

            html += `
                <div class="evaluation-section mb-4">
                    <h6><i class="fas fa-clipboard-check text-success me-2"></i>Đánh giá của quản lý:</h6>
                    <div class="evaluation-card">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="evaluation-item">
                                    <strong>Kết quả thực hiện:</strong>
                                    <span class="evaluation-value">${evaluation.Ketqua_thuchien || 'Chưa đánh giá'} ${phancong.kpi.Donvi_tinh}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="evaluation-item">
                                    <strong>Tỷ lệ hoàn thành:</strong>
                                    <span class="evaluation-value">${evaluation.Ty_le_hoanthanh || 0}%</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="evaluation-item">
                                    <strong>Trạng thái:</strong>
                                    <span class="evaluation-status evaluation-${evaluation.Trang_thai}">
                                        ${getEvaluationStatusText(evaluation.Trang_thai)}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="evaluation-item">
                                    <strong>Ngày đánh giá:</strong>
                                    <span class="evaluation-date">${evaluationDate}</span>
                                </div>
                            </div>
                        </div>
                        ${evaluation.Nhan_xet ? `
                            <div class="evaluation-comment">
                                <strong>Nhận xét:</strong>
                                <div class="comment-content">${evaluation.Nhan_xet}</div>
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;
        } else {
            html += `
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    Quản lý chưa đánh giá KPI này.
                </div>
            `;
        }

        if (submissions.length > 0) {
            html += '<h6 class="mb-3">Danh sách bài nộp:</h6>';

            submissions.forEach((submission, index) => {
                const submitDate = new Date(submission.Ngay_gui).toLocaleString('vi-VN');
                const fileHtml = submission.File_name ?
                    `<a href="/user/kpi/file/${submission.ID_dulieu}/download" class="submission-file">
                        <div class="file-icon">
                            <i class="fas fa-file-${getFileIcon(submission.File_name)}"></i>
                        </div>
                        <span>${submission.File_name}</span>
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
                        <div class="submission-content">
                            <p class="mb-2"><strong>Minh chứng:</strong></p>
                            <p class="mb-3">${submission.Minh_chung || 'Không có mô tả'}</p>
                            <p class="mb-2"><strong>File đính kèm:</strong></p>
                            ${fileHtml}
                        </div>
                    </div>
                `;
            });
        } else {
            html += `
                <div class="empty-submissions">
                    <i class="fas fa-inbox"></i>
                    <h6>Chưa có bài nộp nào</h6>
                    <p class="text-muted">Bạn chưa nộp bài nào cho KPI này</p>
                </div>
            `;
        }

        content.innerHTML = html;
    }

    function getFileIcon(fileName) {
        const extension = fileName.split('.').pop().toLowerCase();

        switch (extension) {
            case 'pdf':
                return 'pdf';
            case 'doc':
            case 'docx':
                return 'word';
            case 'xls':
            case 'xlsx':
                return 'excel';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                return 'image';
            default:
                return 'alt';
        }
    }

    document.getElementById('submitForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append('Minh_chung', document.getElementById('minh_chung').value);

        const fileInput = document.getElementById('file_upload');
        if (fileInput.files[0]) {
            formData.append('file', fileInput.files[0]);
        }

        fetch(`/user/kpi/${document.getElementById('phancong_id').value}/submit`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('submitModal')).hide();
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            showAlert('Có lỗi xảy ra khi nộp bài', 'error');
        });
    });

    function showAlert(message, type) {
        let alertClass, iconClass;

        switch(type) {
            case 'success':
                alertClass = 'alert-success';
                iconClass = 'fas fa-check-circle';
                break;
            case 'warning':
                alertClass = 'alert-warning';
                iconClass = 'fas fa-exclamation-triangle';
                break;
            case 'error':
            default:
                alertClass = 'alert-danger';
                iconClass = 'fas fa-exclamation-circle';
                break;
        }

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

    // Thêm function để hiển thị trạng thái đánh giá
    function getEvaluationStatusText(status) {
        switch(status) {
            case 'cho_duyet':
                return 'Chờ duyệt';
            case 'dat':
                return 'Đạt';
            case 'khong_dat':
                return 'Không đạt';
            default:
                return 'Chưa đánh giá';
        }
    }

    // View KPI Detail
    function viewKpiDetail(id) {
        const modal = new bootstrap.Modal(document.getElementById('kpiDetailModal'));
        modal.show();

        fetch(`/user/kpi/${id}/submissions`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayKpiDetail(data.phancong, data.submissions);
                } else {
                    showAlert('Không thể tải thông tin KPI', 'error');
                }
            })
            .catch(error => {
                showAlert('Có lỗi xảy ra khi tải thông tin KPI', 'error');
            });
    }

    function displayKpiDetail(phancong, submissions) {
        const content = document.getElementById('kpiDetailContent');
        const startDate = new Date(phancong.Ngay_batdau).toLocaleDateString('vi-VN');
        const endDate = new Date(phancong.Ngay_ketthuc).toLocaleDateString('vi-VN');

        let html = `
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted mb-3"><i class="fas fa-clipboard-list me-2"></i>Thông tin KPI</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>Tên KPI:</strong></td>
                                    <td>${phancong.kpi.Ten_kpi}</td>
                                </tr>
                                <tr>
                                    <td><strong>Loại KPI:</strong></td>
                                    <td><span class="badge bg-info">${phancong.loai_kpi ? phancong.loai_kpi.Ten_loai_kpi : 'N/A'}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Chỉ tiêu:</strong></td>
                                    <td><strong class="text-primary">${phancong.kpi.Chi_tieu} ${phancong.kpi.Donvi_tinh}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Độ ưu tiên:</strong></td>
                                    <td><span class="badge bg-warning">${phancong.kpi.Do_uu_tien || 'Không xác định'}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Mô tả:</strong></td>
                                    <td>${phancong.kpi.Mo_ta || 'Không có mô tả'}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted mb-3"><i class="fas fa-calendar-alt me-2"></i>Thời gian & Trạng thái</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>Ngày bắt đầu:</strong></td>
                                    <td><i class="fas fa-calendar-check text-success me-1"></i>${startDate}</td>
                                </tr>
                                <tr>
                                    <td><strong>Ngày kết thúc:</strong></td>
                                    <td><i class="fas fa-calendar-times text-danger me-1"></i>${endDate}</td>
                                </tr>
                                <tr>
                                    <td><strong>Trạng thái:</strong></td>
                                    <td><span class="status-badge status-${phancong.Trang_thai}">${getStatusText(phancong.Trang_thai)}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Người phân công:</strong></td>
                                    <td><i class="fas fa-user-tie me-1"></i>${phancong.nguoi_phan_cong ? phancong.nguoi_phan_cong.Ho_ten : 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phòng ban:</strong></td>
                                    <td><i class="fas fa-building me-1"></i>${phancong.phongban ? phancong.phongban.Ten_phongban : 'N/A'}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Hiển thị đánh giá nếu có
        if (phancong.danhgia_kpi) {
            const evaluation = phancong.danhgia_kpi;
            const evaluationDate = new Date(evaluation.Ngay_thamdinh).toLocaleString('vi-VN');

            html += `
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="text-muted mb-3"><i class="fas fa-clipboard-check me-2"></i>Kết quả đánh giá</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="display-6 text-primary">${evaluation.Ketqua_thuchien || 0}</div>
                                    <small class="text-muted">Kết quả thực hiện</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="display-6 text-success">${evaluation.Ty_le_hoanthanh || 0}%</div>
                                    <small class="text-muted">Tỷ lệ hoàn thành</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <span class="evaluation-status evaluation-${evaluation.Trang_thai}">${getEvaluationStatusText(evaluation.Trang_thai)}</span>
                                    <br><small class="text-muted">Trạng thái</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="small text-muted">${evaluationDate}</div>
                                    <small class="text-muted">Ngày đánh giá</small>
                                </div>
                            </div>
                        </div>
                        ${evaluation.Nhan_xet ? `
                            <div class="mt-3">
                                <strong>Nhận xét của quản lý:</strong>
                                <div class="alert alert-info mt-2">${evaluation.Nhan_xet}</div>
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;
        } else {
            html += `
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    KPI này chưa được đánh giá
                </div>
            `;
        }

        // Hiển thị danh sách bài nộp
        html += '<div class="card border-0 shadow-sm"><div class="card-body">';
        html += '<h6 class="text-muted mb-3"><i class="fas fa-file-upload me-2"></i>Lịch sử bài nộp (' + submissions.length + ')</h6>';

        if (submissions.length > 0) {
            submissions.forEach((submission, index) => {
                const submitDate = new Date(submission.Ngay_gui).toLocaleString('vi-VN');
                const fileHtml = submission.File_name ?
                    `<a href="/user/kpi/file/${submission.ID_dulieu}/download" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-download me-1"></i> ${submission.File_name}
                    </a>` :
                    '<span class="text-muted">Không có file đính kèm</span>';

                html += `
                    <div class="submission-item">
                        <div class="submission-header">
                            <strong><i class="fas fa-file-alt me-2"></i>Bài nộp #${index + 1}</strong>
                            <span class="submission-date">
                                <i class="fas fa-clock me-1"></i>${submitDate}
                            </span>
                        </div>
                        <div class="submission-content">
                            <p class="mb-2"><strong>Minh chứng:</strong></p>
                            <p class="mb-3">${submission.Minh_chung || 'Không có mô tả'}</p>
                            ${fileHtml}
                        </div>
                    </div>
                `;
            });
        } else {
            html += `
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Chưa có bài nộp nào</p>
                </div>
            `;
        }

        html += '</div></div>';
        content.innerHTML = html;
    }

    function getStatusText(status) {
        switch(status) {
            case 'chua_thuc_hien': return 'Chưa thực hiện';
            case 'dang_thuc_hien': return 'Đang thực hiện';
            case 'hoan_thanh': return 'Hoàn thành';
            case 'qua_han': return 'Quá hạn';
            default: return status;
        }
    }
</script>
@endpush

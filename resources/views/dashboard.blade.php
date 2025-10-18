@extends('layouts.dashboard')

@section('title', 'Trang chủ')
@section('page-title', 'Trang chủ')
@section('search-placeholder', 'Tìm kiếm...')

@section('content')
<div class="welcome-card">
    <h1 class="welcome-title">
        Chào, {{ Auth::user()->Ho_ten ?? 'Người dùng' }}!
        <span style="font-size: 24px;">😊</span>
    </h1>
    <p class="welcome-subtitle">
        <i class="fas fa-chart-line"></i>
        Theo dõi hiệu suất công việc của bạn hôm nay.
    </p>
</div>

<div class="performance-card">
    <h2 class="performance-title">
        <i class="fas fa-chart-line"></i>
        Theo dõi hiệu suất công việc
    </h2>

    <div class="row">
        <div class="col-md-4">
            <div class="card text-center p-3">
                <div class="card-body">
                    <i class="fas fa-clipboard-list fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Tổng KPI</h5>
                    <h3 class="text-primary">0</h3>
                    <p class="text-muted">KPI được giao</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center p-3">
                <div class="card-body">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Hoàn thành</h5>
                    <h3 class="text-success">0</h3>
                    <p class="text-muted">KPI đã hoàn thành</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center p-3">
                <div class="card-body">
                    <i class="fas fa-clock fa-3x text-warning mb-3"></i>
                    <h5 class="card-title">Đang thực hiện</h5>
                    <h3 class="text-warning">0</h3>
                    <p class="text-muted">KPI đang thực hiện</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <h5>Thông tin cá nhân</h5>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Email:</strong> {{ Auth::user()->Email }}</p>
                <p><strong>Quyền:</strong> {{ Auth::user()->quyen->Ten_quyen ?? 'Chưa phân quyền' }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Phòng ban:</strong> {{ Auth::user()->phongban->Ten_phongban ?? 'Chưa phân phòng ban' }}</p>
                <p><strong>Trạng thái:</strong>
                    <span class="badge bg-success">{{ Auth::user()->Trang_thai ?? 'hoạt động' }}</span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

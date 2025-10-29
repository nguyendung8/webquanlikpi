<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hệ thống KPI')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #f8f9fa;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            background: #f8f9fa;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 0 20px 30px;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 20px;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
        }

        .sidebar-logo .logo-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin: 5px 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #6c757d;
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 0 25px 25px 0;
            margin-right: 10px;
        }

        .sidebar-menu a:hover {
            background: #e9ecef;
            color: #495057;
        }

        .sidebar-menu a.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .sidebar-menu i {
            width: 20px;
            text-align: center;
        }

        /* Pagination Styles */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            margin: 20px 0;
        }

        .pagination .page-item {
            list-style: none;
        }

        .pagination .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 8px 12px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            background: white;
        }

        .pagination .page-link:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(102, 126, 234, 0.3);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
            box-shadow: 0 3px 8px rgba(102, 126, 234, 0.4);
        }

        .pagination .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .pagination .page-link svg {
            width: 16px !important;
            height: 16px !important;
            fill: currentColor;
        }

        /* Ẩn text "Previous" và "Next", chỉ hiện icon */
        .pagination .page-link .hidden {
            display: none !important;
        }

        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 0;
        }

        .top-header {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
        }

        .header-logo .logo-icon {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }

        .search-bar {
            position: relative;
            width: 400px;
        }

        .search-bar input {
            width: 100%;
            padding: 10px 15px 10px 45px;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            background: #f8f9fa;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .search-bar input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
        }

        .search-bar i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notification-icon {
            position: relative;
            color: #6c757d;
            font-size: 20px;
            cursor: pointer;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 8px;
            height: 8px;
            background: #dc3545;
            border-radius: 50%;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-details {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
            font-size: 14px;
        }

        .user-role {
            color: #6c757d;
            font-size: 12px;
            margin: 0;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }

        .logout-btn {
            background: none;
            border: none;
            color: #6c757d;
            font-size: 18px;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #f8f9fa;
            color: #dc3545;
        }

        .user-info .dropdown-menu {
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            border: none;
            padding: 8px 0;
            min-width: 200px;
            margin-top: 10px;
        }

        .user-info .dropdown-item {
            padding: 10px 20px;
            font-size: 14px;
            transition: all 0.2s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .user-info .dropdown-item:hover {
            background: #f8f9fa;
            padding-left: 25px;
        }

        .user-info .dropdown-item.text-danger:hover {
            background: #fff5f5;
            color: #dc3545;
        }

        .user-info .dropdown-divider {
            margin: 5px 0;
        }

        .content-area {
            padding: 30px;
        }

        .welcome-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .welcome-title {
            font-size: 28px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .welcome-subtitle {
            color: #6c757d;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .performance-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            min-height: 400px;
        }

        .performance-title {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .performance-title i {
            color: #667eea;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .search-bar {
                width: 200px;
            }
        }
    </style>
    @push('styles')
    <style>
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

        /* Notification styles */
        .notification-dropdown {
            position: relative;
        }

        .notification-icon {
            position: relative;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.3s;
        }

        .notification-icon:hover {
            background-color: rgba(0,0,0,0.1);
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex !important;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .notification-panel {
            position: absolute;
            top: 100%;
            right: 0;
            width: 350px;
            max-height: 500px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            z-index: 1000;
            display: none;
            overflow: hidden;
        }

        .notification-panel.show {
            display: block;
        }

        .notification-header {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
        }

        .notification-header h6 {
            margin: 0;
            font-weight: 600;
            color: #2c3e50;
        }

        .notification-list {
            max-height: 350px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item.unread {
            background-color: #e3f2fd;
            border-left: 3px solid #2196f3;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-content {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .notification-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            font-weight: bold;
            flex-shrink: 0;
        }

        .notification-details {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 4px;
            font-size: 14px;
        }

        .notification-message {
            color: #6c757d;
            font-size: 13px;
            line-height: 1.4;
            margin-bottom: 4px;
        }

        .notification-time {
            color: #adb5bd;
            font-size: 11px;
        }

        .notification-type-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 500;
            margin-top: 4px;
        }

        .notification-type-phancong_kpi {
            background: #e3f2fd;
            color: #1976d2;
        }

        .notification-type-review_kpi {
            background: #e8f5e8;
            color: #2e7d32;
        }

        .notification-type-submit_kpi {
            background: #fff3e0;
            color: #f57c00;
        }

        .notification-type-phancong_task {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .notification-footer {
            padding: 10px;
            border-top: 1px solid #eee;
            background: #f8f9fa;
        }

        .empty-notifications {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }

        .empty-notifications i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }
    </style>
    @endpush
    @stack('styles')
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <div class="logo-icon">
                        <img width="40px" src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid">
                    </div>
                    <span>Quản lý KPIs</span>
                </div>
            </div>

            <ul class="sidebar-menu">
                @if(Auth::user()->ID_quyen == 1) {{-- Admin --}}
                    <li>
                        <a href="{{ route('dashboard.index') }}" class="{{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Trang chủ</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kpi.index') }}" class="{{ request()->routeIs('kpi.*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-list"></i>
                            <span>Quản lý KPIs</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('task.index') }}" class="{{ request()->routeIs('task.*') ? 'active' : '' }}">
                            <i class="fas fa-tasks"></i>
                            <span>Quản lý Tasks</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <span>Quản lý Người dùng</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('phongban.index') }}" class="{{ request()->routeIs('phongban.*') ? 'active' : '' }}">
                            <i class="fas fa-building"></i>
                            <span>Quản lý Phòng ban</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Báo cáo</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('activity.index') }}" class="{{ request()->routeIs('activity.*') ? 'active' : '' }}">
                            <i class="fas fa-history"></i>
                            <span>Nhật ký hoạt động</span>
                        </a>
                    </li>
                @elseif(Auth::user()->ID_quyen == 2) {{-- Quản lý --}}
                    <li>
                        <a href="{{ route('manager.dashboard.index') }}" class="{{ request()->routeIs('manager.dashboard.index') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Trang chủ</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('manager.kpi.index') }}" class="{{ request()->routeIs('manager.kpi.*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-list"></i>
                            <span>Quản lý KPIs</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('manager.kpi_type.index') }}" class="{{ request()->routeIs('manager.kpi_type.*') ? 'active' : '' }}">
                            <i class="fas fa-list"></i>
                            <span>Quản lý Loại KPI</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('manager.tasks.index') }}" class="{{ request()->routeIs('manager.tasks.*') ? 'active' : '' }}">
                            <i class="fas fa-tasks"></i>
                            <span>Quản lý Nhiệm vụ</span>
                        </a>
                    </li>
                @elseif(Auth::user()->ID_quyen == 3) {{-- Nhân viên --}}
                    <li>
                        <a href="{{ route('user.dashboard.index') }}" class="{{ request()->routeIs('user.dashboard.*') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Trang chủ</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.kpi.index') }}" class="{{ request()->routeIs('user.kpi.*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-list"></i>
                            <span>KPI của tôi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.tasks.index') }}" class="{{ request()->routeIs('user.tasks.*') ? 'active' : '' }}">
                            <i class="fas fa-tasks"></i>
                            <span>Nhiệm vụ</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.calendar.index') }}" class="{{ request()->routeIs('user.calendar.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Lịch</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Header -->
            <div class="top-header">
                <div class="header-left">
                    <div class="header-logo">
                        <div class="logo-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span>@yield('page-title', 'Quản lý KPIs')</span>
                    </div>
                </div>

                <div class="header-right">
                    @if (Auth::user()->ID_quyen != 1)
                        <div class="notification-dropdown">
                            <div class="notification-icon" onclick="toggleNotifications()">
                                <i class="fas fa-bell"></i>
                                <div class="notification-badge" id="notificationBadge">0</div>
                            </div>

                            <div class="notification-panel" id="notificationPanel">
                                <div class="notification-header">
                                    <h6>Thông báo</h6>
                                    <button class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">
                                        <i class="fas fa-check-double"></i> Đọc tất cả
                                    </button>
                                </div>

                                <div class="notification-list" id="notificationList">
                                    <div class="text-center p-3">
                                        <div class="spinner-border spinner-border-sm" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="text-muted mt-2">Đang tải thông báo...</p>
                                    </div>
                                </div>

                                <div class="notification-footer">
                                    <button class="btn btn-sm btn-link w-100" onclick="loadMoreNotifications()" id="loadMoreBtn" style="display: none;">
                                        <i class="fas fa-chevron-down"></i> Xem thêm
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif


                    <div class="user-info">
                        <div class="user-details">
                            <p class="user-name">{{ Auth::user()->Ho_ten ?? 'Người dùng' }}</p>
                            <p class="user-role">{{ Auth::user()->quyen->Ten_quyen ?? 'Chưa phân quyền' }}</p>
                        </div>
                        <div class="dropdown">
                            <div class="user-avatar" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                                {{ substr(Auth::user()->Ho_ten ?? 'U', 0, 1) }}
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.index') }}">
                                        <i class="fas fa-user-circle me-2"></i>Quản lý hồ sơ
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Alert Container -->
                <div id="alertContainer" style="position: fixed; top: 80px; left: 50%; transform: translateX(-50%); z-index: 9999; width: 90%; max-width: 500px;">
                </div>

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
// Simple notification toggle
function toggleNotifications() {
    const panel = document.getElementById('notificationPanel');
    if (panel) {
        panel.classList.toggle('show');

        // Load notifications when panel opens
        if (panel.classList.contains('show')) {
            loadNotifications();
        }
    }
}

// Simple load notifications
async function loadNotifications() {
    try {
        const response = await fetch('/notifications');
        const data = await response.json();

        const notificationList = document.getElementById('notificationList');
        if (data.data && data.data.length > 0) {
            notificationList.innerHTML = data.data.map(notification => `
                <div class="notification-item ${notification.Da_xem ? '' : 'unread'}" onclick="markAsRead(${notification.ID_thongbao})">
                    <div class="notification-content">
                        <div class="notification-avatar">
                            ${notification.nguoigui ? notification.nguoigui.Ho_ten.charAt(0) : 'S'}
                        </div>
                        <div class="notification-details">
                            <h6>${notification.Tieu_de}</h6>
                            <p>${notification.Noi_dung}</p>
                            <small class="text-muted">${new Date(notification.Ngay_gui).toLocaleString('vi-VN')}</small>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            notificationList.innerHTML = '<div class="text-center p-3"><p class="text-muted">Chưa có thông báo</p></div>';
        }
    } catch (error) {
        console.error('Error loading notifications:', error);
        document.getElementById('notificationList').innerHTML = '<div class="text-center p-3"><p class="text-danger">Lỗi tải thông báo</p></div>';
    }
}

// Mark as read
async function markAsRead(id) {
    try {
        await fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        });

        // Reload notifications
        loadNotifications();
    } catch (error) {
        console.error('Error marking as read:', error);
    }
}

// Mark all as read
async function markAllAsRead() {
    try {
        const response = await fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        });

        if (response.ok) {
            loadNotifications();
        }
    } catch (error) {
        console.error('Error marking all as read:', error);
    }
}

// Update unread count
async function updateUnreadCount() {
    try {
        const response = await fetch('/notifications/unread-count');
        const data = await response.json();

        const badge = document.getElementById('notificationBadge');
        if (badge) {
            badge.textContent = data.count || 0;
            badge.style.display = data.count > 0 ? 'block' : 'none';
        }
    } catch (error) {
        console.error('Error updating unread count:', error);
    }
}

// Load unread count on page load
document.addEventListener('DOMContentLoaded', function() {
    updateUnreadCount();
});
</script>

@stack('scripts')

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
                        <a href="{{ route('my-kpi.index') }}" class="{{ request()->routeIs('my-kpi.*') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Trang chủ</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('my-kpi.index') }}" class="{{ request()->routeIs('my-kpi.*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-list"></i>
                            <span>KPI của tôi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tasks.index') }}" class="{{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                            <i class="fas fa-tasks"></i>
                            <span>Nhiệm vụ</span>
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
                        <div class="notification-icon">
                            <i class="fas fa-bell"></i>
                            <div class="notification-badge"></div>
                        </div>
                    @endif


                    <div class="user-info">
                        <div class="user-details">
                            <p class="user-name">{{ Auth::user()->Ho_ten ?? 'Người dùng' }}</p>
                            <p class="user-role">{{ Auth::user()->quyen->Ten_quyen ?? 'Chưa phân quyền' }}</p>
                        </div>
                        <div class="user-avatar">
                            {{ substr(Auth::user()->Ho_ten ?? 'U', 0, 1) }}
                        </div>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="logout-btn" title="Đăng xuất">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
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
    @push('scripts')
    @endpush
    @stack('scripts')
    <script>
// Cập nhật title theo URL hiện tại
document.addEventListener('DOMContentLoaded', function() {
    const path = window.location.pathname;
    const titleElement = document.getElementById('pageTitle');

    const titleMap = {
        '/dashboard': 'Trang chủ',
        '/kpi': 'Quản lý KPIs',
        '/users': 'Quản lý Người dùng',
        '/phongban': 'Quản lý Phòng ban',
        '/reports': 'Báo cáo',
        '/activity': 'Nhật ký hoạt động'
    };

    // Tìm title phù hợp
    for (const [pathKey, title] of Object.entries(titleMap)) {
        if (path.includes(pathKey)) {
            titleElement.textContent = title;
            break;
        }
    }
});
</script>
</body>
</html>

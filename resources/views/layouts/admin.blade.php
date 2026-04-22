<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Neo Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
@if(session('success'))
<div id="toast" class="custom-toast">
    <i class="fa fa-check-circle me-2"></i>
    {{ session('success') }}
    <span class="close-toast" onclick="closeToast()">×</span>
</div>
@endif

@if(session('error'))
<div class="custom-toast bg-danger">
    <i class="fa fa-times-circle me-2"></i>
    {{ session('error') }}
</div>
@endif
<!-- TOP BAR -->
<div class="topbar d-flex justify-content-between align-items-center">

    <!-- LOGO -->
    <div>
        <img src="/logo.svg" height="40">
    </div>

    <!-- RIGHT SIDE -->
    <div class="d-flex align-items-center gap-2 position-relative">

        <!-- ADMIN BUTTON -->
        <button class="btn btn-success d-flex align-items-center gap-1">
            <i class="fa fa-user"></i>
            <span>Administrator</span>
        </button>

        <!-- NOTIFICATION -->
        <div class="position-relative">

            <button class="btn btn-success position-relative" onclick="toggleNotification()">
                <i class="fa fa-bell"></i>
                <span class="badge bg-light text-dark position-absolute top-0 start-100 translate-middle">
                    0
                </span>
            </button>

            <!-- NOTIFICATION DROPDOWN -->
            <div id="notificationBox" class="notification-box">

                <div class="notif-header d-flex justify-content-between">
                    <strong>Notifications</strong>
                    <a href="#" class="clear-all">Clear All</a>
                </div>

                <div class="notif-body text-center">
                    <i class="fa fa-bell-slash mb-2"></i>
                    <div>No Notifications</div>
                </div>

            </div>

        </div>

        <!-- USER DROPDOWN BUTTON -->
        <button class="btn btn-success" onclick="toggleDropdown()">
            <i class="fa fa-user-circle"></i>
        </button>

        <!-- DROPDOWN MENU -->
        <div id="userDropdown" class="dropdown-menu-custom">

            <a href="{{ route('profile.edit') }}">
                <i class="fa fa-user me-2"></i> Profile
            </a>

            <a href="{{ route('change.password') }}">
                <i class="fa fa-key me-2"></i> Change Password
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">
                    <i class="fa fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>

        </div>

    </div>

</div>

<!-- MENU -->
<div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">

        <div class="container-fluid">

            <!-- TOGGLE (Mobile) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- MENU ITEMS -->
            <div class="collapse navbar-collapse" id="mainNavbar">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-chart-line me-1"></i> Dashboard
                        </a>
                    </li>

                    <!-- Admin -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.index') }}">
                            <i class="fa fa-users-gear me-1"></i> Admin
                        </a>
                    </li>

                    <!-- Projects -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('projects.index') }}">
                            <i class="fa fa-diagram-project me-1"></i> Projects
                        </a>
                    </li>

                    <!-- Cashflow -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cashflow.index') }}">
                            <i class="fa fa-cash-register me-1"></i> Cashflow
                        </a>
                    </li>

                    <!-- Registers DROPDOWN -->
                    <li class="nav-item dropdown">

                        <a class="nav-link dropdown-toggle" href="#" id="registerDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">

                            <i class="fa fa-book me-1"></i> Registers
                        </a>

                        <ul class="dropdown-menu">

                            <li>
                                <a class="dropdown-item" href="{{ route('users.index') }}">
                                    <i class="fa fa-users me-2"></i> Manage Users
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fa fa-clock me-2"></i> Timesheet
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fa fa-clipboard-list me-2"></i> Checklist
                                </a>
                            </li>

                        </ul>

                    </li>

                    <!-- Reports -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reports.index') }}">
                            <i class="fa fa-file-lines me-1"></i> Reports
                        </a>
                    </li>

                </ul>

            </div>

        </div>

    </nav>
</div>

<!-- CONTENT -->
<div class="container-fluid">
    @yield('content')
</div>
<!-- jQuery FIRST -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Your App JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>

<!-- Page Scripts -->
@stack('scripts')
</body>
</html>

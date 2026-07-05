<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistem Informasi Anggaran - SIAGA">
    <meta name="author" content="Stevan Tarigan">
    <meta name="theme-color" content="#6d5dfc">

    <link rel="icon" type="image/svg+xml" href="{{ asset('images/gitlab.svg') }}">
    <title>SIAGA | @yield('title', 'Dashboard')</title>

    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
        /* ============================================
           COMPACT VERSION - SINKRON DENGAN SIDEBAR
           ============================================ */

        :root {
            --primary: #6d5dfc;
            --primary-dark: #5b4bd8;
            --primary-light: #8a7dfd;
            --primary-soft: rgba(109, 93, 252, 0.1);
            --secondary: #22d3ee;
            --accent: #f59e0b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;

            --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);

            /* COMPACT SHADOWS */
            --shadow-sm: 0 2px 8px rgba(109, 93, 252, 0.06);
            --shadow-md: 0 4px 14px rgba(109, 93, 252, 0.08);
            --shadow-lg: 0 8px 24px rgba(109, 93, 252, 0.1);

            /* COMPACT RADIUS */
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 10px;
            --radius-xl: 12px;

            /* COMPACT LAYOUT */
            --sidebar-width: 240px;
            --header-height: 60px;

            --transition-fast: 200ms ease;
            --transition-base: 300ms ease;
            --transition-slow: 500ms ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f5f7ff 0%, #f0f3ff 100%);
            min-height: 100vh;
            color: #1e293b;
            overflow-x: hidden;
            line-height: 1.45;
        }

        /* Animated Background */
        .bg-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            opacity: 0.4;
            background:
                radial-gradient(circle at 10% 20%, rgba(109, 93, 252, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(34, 211, 238, 0.08) 0%, transparent 40%);
        }

        .floating-element {
            position: fixed;
            border-radius: 50%;
            filter: blur(50px);
            opacity: 0.2;
            z-index: -1;
            animation: float 20s infinite alternate;
        }

        .floating-element:nth-child(1) {
            width: 400px;
            height: 400px;
            background: var(--primary);
            top: -100px;
            left: -100px;
        }

        .floating-element:nth-child(2) {
            width: 300px;
            height: 300px;
            background: var(--secondary);
            bottom: -50px;
            right: -50px;
            animation-delay: 5s;
        }

        .floating-element:nth-child(3) {
            width: 200px;
            height: 200px;
            background: var(--accent);
            top: 40%;
            right: 15%;
            animation-delay: 10s;
        }

        @keyframes float {
            0% {
                transform: translate(0, 0);
            }

            100% {
                transform: translate(30px, 30px);
            }
        }

        /* Layout Container */
        .layout-container {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* Main Content Wrapper */
        .main-content-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin-left var(--transition-slow);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header Styles - COMPACT */
        .app-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 100;
            height: var(--header-height);
            transition: all var(--transition-base);
        }

        .app-header.scrolled {
            height: 55px;
            box-shadow: var(--shadow-md);
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            padding: 0 1.2rem;
            max-width: 100%;
        }

        /* Menu Toggle - COMPACT */
        .menu-toggle {
            display: none;
            width: 38px;
            height: 38px;
            border: none;
            background: transparent;
            color: var(--primary);
            font-size: 1.2rem;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all var(--transition-base);
            border: 2px solid transparent;
        }

        .menu-toggle:hover {
            background: var(--primary-soft);
            transform: rotate(90deg);
        }

        @media (max-width: 992px) {
            .menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        /* Breadcrumb - COMPACT */
        .breadcrumb-nav {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .breadcrumb-item {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: #64748b;
            font-size: 0.8rem;
        }

        .breadcrumb-item a {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 0.6rem;
            color: #64748b;
            text-decoration: none;
            border-radius: var(--radius-sm);
            transition: all var(--transition-fast);
            background: transparent;
        }

        .breadcrumb-item a:hover {
            background: var(--primary-soft);
            color: var(--primary);
            transform: translateY(-1px);
        }

        .breadcrumb-divider {
            color: #cbd5e1;
            font-size: 0.75rem;
        }

        .breadcrumb-current {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 0.8rem;
            background: var(--primary-soft);
            color: var(--primary);
            font-weight: 600;
            border-radius: 30px;
            font-size: 0.8rem;
        }

        /* User Profile - COMPACT */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            cursor: pointer;
            position: relative;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 700;
            color: #1e293b;
            font-size: 0.8rem;
            line-height: 1.3;
        }

        .user-role {
            font-size: 0.65rem;
            color: #64748b;
            background: rgba(0, 0, 0, 0.03);
            padding: 0.15rem 0.5rem;
            border-radius: 30px;
            display: inline-block;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.9rem;
            position: relative;
            border: 2px solid rgba(255, 255, 255, 0.9);
            box-shadow: var(--shadow-sm);
            transition: all var(--transition-base);
        }

        .user-avatar:hover {
            transform: scale(1.03);
            box-shadow: var(--shadow-md);
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            min-width: 16px;
            height: 16px;
            background: var(--danger);
            color: white;
            font-size: 0.55rem;
            font-weight: 700;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        /* User Dropdown - COMPACT */
        .user-dropdown-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            min-width: 260px;
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-15px);
            transition: all var(--transition-base);
            z-index: 1000;
            border: 1px solid rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .user-dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown-header {
            padding: 1rem;
            background: linear-gradient(135deg, rgba(109, 93, 252, 0.05), rgba(34, 211, 238, 0.05));
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .user-dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.6rem 1rem;
            color: #475569;
            text-decoration: none;
            transition: all var(--transition-fast);
            font-size: 0.8rem;
        }

        .user-dropdown-item:hover {
            background: var(--primary-soft);
            color: var(--primary);
            padding-left: 1.4rem;
        }

        .user-dropdown-item i {
            width: 18px;
            color: var(--primary);
        }

        .user-dropdown-item.text-danger i {
            color: var(--danger);
        }

        .user-dropdown-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e2e8f0, transparent);
            margin: 0.4rem 1rem;
        }

        /* Main Content - COMPACT */
        .main-content {
            padding: 1rem;
            flex: 1;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Content Card - COMPACT */
        .content-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: all var(--transition-base);
            border: 1px solid rgba(0, 0, 0, 0.03);
            position: relative;
        }

        .content-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .content-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.5s ease;
        }

        .content-card:hover::before {
            transform: scaleX(1);
        }

        .card-header {
            padding: 0.9rem 1.2rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 0.6rem;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.98));
        }

        .card-header h1 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin: 0;
        }

        .card-header h1 i {
            width: 34px;
            height: 34px;
            background: var(--primary-soft);
            color: var(--primary);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: all var(--transition-base);
        }

        .content-card:hover .card-header h1 i {
            background: var(--gradient-primary);
            color: white;
            transform: rotate(5deg);
        }

        .card-body {
            padding: 1rem;
            background: white;
        }

        /* =========================
   PROFESSIONAL FOOTER
========================= */
        .app-footer {
            margin-top: auto;
            position: relative;
            background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%);
            border-top: 1px solid rgba(109, 93, 252, .10);
            padding: 1.1rem 0;
            overflow: hidden;
        }

        /* garis gradient atas */
        .app-footer::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg,
                    var(--primary),
                    var(--secondary),
                    var(--primary));
            background-size: 200% auto;
            animation: footerLine 6s linear infinite;
        }

        @keyframes footerLine {
            from {
                background-position: 0% center;
            }

            to {
                background-position: 200% center;
            }
        }

        /* glow background lembut */
        .app-footer::after {
            content: "";
            position: absolute;
            width: 240px;
            height: 240px;
            right: -80px;
            bottom: -140px;
            background: rgba(109, 93, 252, .08);
            filter: blur(70px);
            border-radius: 50%;
            pointer-events: none;
        }

        .footer-content {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            padding: 0 1.5rem;
        }

        /* copyright */
        .footer-text {
            display: flex;
            align-items: center;
            gap: .55rem;
            flex-wrap: wrap;
            color: #64748b;
            font-size: .78rem;
            font-weight: 500;
            letter-spacing: .2px;
        }

        .footer-text::before {
            content: "";
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--success);
            box-shadow:
                0 0 0 4px rgba(16, 185, 129, .12);
        }

        /* nama author */
        .footer-text a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
            position: relative;
            transition: .3s ease;
        }

        .footer-text a::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: .3s;
        }

        .footer-text a:hover {
            color: var(--primary-dark);
        }

        .footer-text a:hover::after {
            width: 100%;
        }

        /* area versi */
        .footer-version {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .45rem .6rem .45rem 1rem;
            border-radius: 40px;
            background: #fff;
            border: 1px solid rgba(109, 93, 252, .10);
            box-shadow:
                0 4px 12px rgba(109, 93, 252, .05),
                inset 0 1px 0 rgba(255, 255, 255, .8);
        }

        .footer-version span:first-child {
            font-size: .75rem;
            font-weight: 600;
            color: #64748b;
        }

        /* badge version */
        .version-badge {
            padding: .38rem .85rem;
            border-radius: 999px;
            background: linear-gradient(135deg,
                    var(--primary),
                    var(--secondary));
            color: #fff;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .5px;
            box-shadow: 0 4px 12px rgba(109, 93, 252, .25);
            transition: .3s ease;
        }

        .version-badge:hover {
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 6px 16px rgba(109, 93, 252, .32);
        }

        /* responsive */
        @media (max-width:768px) {

            .app-footer {
                padding: 1rem 0;
            }

            .footer-content {
                flex-direction: column;
                justify-content: center;
                text-align: center;
                gap: .8rem;
            }

            .footer-text {
                justify-content: center;
                font-size: .74rem;
            }

            .footer-version {
                padding: .45rem .85rem;
            }
        }

        .version-badge {
            padding: 0.2rem 0.6rem;
            background: var(--primary-soft);
            color: var(--primary);
            font-size: 0.65rem;
            font-weight: 600;
            border-radius: 30px;
            transition: all var(--transition-fast);
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(3px);
            z-index: 1045;
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-base);
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .main-content-wrapper {
                margin-left: 0;
            }
        }

        @media (max-width: 992px) {
            .main-content {
                padding: 0.75rem;
            }

            .card-header {
                padding: 0.75rem 1rem;
            }

            .card-body {
                padding: 0.85rem;
            }

            .user-info {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .header-container {
                padding: 0 0.8rem;
            }

            .main-content {
                padding: 0.6rem;
            }

            .card-header {
                padding: 0.6rem 0.85rem;
            }

            .card-body {
                padding: 0.75rem;
            }

            .user-avatar {
                width: 34px;
                height: 34px;
                font-size: 0.8rem;
            }

            .card-header h1 {
                font-size: 1rem;
            }

            .card-header h1 i {
                width: 30px;
                height: 30px;
                font-size: 0.85rem;
            }

            .content-card {
                border-radius: 10px;
            }
        }

        @media (max-width: 576px) {
            .breadcrumb-current span {
                display: none;
            }

            .breadcrumb-current i {
                margin: 0;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        /* DataTables Customization - COMPACT */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e2e8f0;
            border-radius: var(--radius-sm);
            padding: 0.3rem 0.6rem;
            font-size: 0.75rem;
        }

        .dataTables_wrapper .dataTables_length select:focus,
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 2px var(--primary-soft);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: var(--radius-sm);
            padding: 0.3rem 0.6rem;
            margin: 0 0.15rem;
            font-size: 0.75rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--primary) !important;
            color: white !important;
            border: none;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--primary-soft) !important;
            color: var(--primary) !important;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="bg-pattern"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>

    <div class="layout-container">
        @include('layouts.navbar')

        <div class="main-content-wrapper" id="mainContent">
            <header class="app-header" id="appHeader">
                <div class="header-container">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="bi bi-list"></i>
                    </button>

                    <nav class="breadcrumb-nav">
                        <div class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                <i class="bi bi-house-door"></i>
                                <span>Home</span>
                            </a>
                            <span class="breadcrumb-divider">›</span>
                        </div>
                        <div class="breadcrumb-item">
                            <span class="breadcrumb-current" id="currentPage">
                                <i class="bi bi-@yield('icon', 'gitlab')"></i>
                                <span>@yield('title', 'Dashboard')</span>
                            </span>
                        </div>
                    </nav>

                    @auth
                        <div class="user-profile">
                            <div class="user-info">
                                <div class="user-name">{{ Auth::user()->nama }}</div>
                                <div class="user-role">
                                    @if (Auth::user()->role == 'admin')
                                        Administrator
                                    @elseif(Auth::user()->role == 'keuangan')
                                        Keuangan
                                    @elseif(Auth::user()->role == 'kepala_gizi')
                                        Kepala Gizi
                                    @else
                                        {{ ucfirst(Auth::user()->role) }}
                                    @endif
                                </div>
                            </div>

                            <div class="user-avatar" id="userAvatar">
                                {{ strtoupper(substr(Auth::user()->nama, 0, 2)) }}
                                @php $notifications = 0; @endphp
                                @if ($notifications > 0)
                                    <span class="notification-badge">{{ $notifications }}</span>
                                @endif
                            </div>

                            <div class="user-dropdown-menu" id="userDropdown">
                                <div class="user-dropdown-header">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="user-avatar" style="width: 44px; height: 44px;">
                                            {{ strtoupper(substr(Auth::user()->nama, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ Auth::user()->nama }}</div>
                                            <div class="small text-muted">{{ Auth::user()->username }}</div>
                                            <span class="badge bg-primary bg-opacity-10 text-primary mt-1"
                                                style="font-size: 0.65rem;">
                                                @if (Auth::user()->role == 'admin')
                                                    Administrator
                                                @elseif(Auth::user()->role == 'keuangan')
                                                    Keuangan
                                                @elseif(Auth::user()->role == 'kepala_gizi')
                                                    Kepala Gizi
                                                @else
                                                    {{ ucfirst(Auth::user()->role) }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <a href="#" class="user-dropdown-item">
                                    <i class="bi bi-person-circle"></i>
                                    <span>Profil Saya</span>
                                </a>

                                <a href="#" class="user-dropdown-item" id="changePasswordBtn">
                                    <i class="bi bi-key-fill"></i>
                                    <span>Ubah Password</span>
                                </a>

                                <div class="user-dropdown-divider"></div>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                                <a href="#" class="user-dropdown-item text-danger" id="logoutBtn">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Logout</span>
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>
            </header>

            <main class="main-content">
                <div class="content-card" data-aos="fade-up">
                    <div class="card-header">
                        <h1>
                            <i class="bi bi-@yield('icon', 'gitlab')"></i>
                            <span>@yield('title', 'Dashboard')</span>
                        </h1>
                    </div>
                    <div class="card-body">
                        @yield('content')
                    </div>
                </div>
            </main>

            <footer class="app-footer">
                <div class="footer-content">
                    <div class="footer-text">
                        &copy; <span id="currentYear"></span> Sistem Informasi Anggaran (SIAGA).
                        Dibangun oleh <a href="#">Stevan Tarigan</a>
                    </div>
                    <div class="footer-version">
                        <span>Versi</span>
                        <span class="version-badge">v2.1.0</span>
                    </div>
                </div>
            </footer>
        </div>

        <div class="sidebar-overlay" id="sidebarOverlay"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        AOS.init({
            duration: 600,
            once: true
        });

        $(document).ready(function() {
            $('#currentYear').text(new Date().getFullYear());

            $(window).on('scroll', function() {
                if ($(window).scrollTop() > 50) {
                    $('#appHeader').addClass('scrolled');
                } else {
                    $('#appHeader').removeClass('scrolled');
                }
            });

            $('#menuToggle').on('click', function(e) {
                e.preventDefault();
                $('.sidebar-nav').toggleClass('active');
                $('#sidebarOverlay').toggleClass('active');
                createRipple(e, $(this));
            });

            $('#sidebarOverlay').on('click', function() {
                $('.sidebar-nav').removeClass('active');
                $(this).removeClass('active');
            });

            $('#userAvatar').on('click', function(e) {
                e.stopPropagation();
                $('#userDropdown').toggleClass('show');
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#userAvatar, #userDropdown').length) {
                    $('#userDropdown').removeClass('show');
                }
            });

            $('#logoutBtn').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Keluar dari Sistem?',
                    text: 'Anda akan logout dari Sistem Informasi Anggaran',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#6d5dfc',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Logout',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('logout-form').submit();
                    }
                });
            });

            $('#changePasswordBtn').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Ubah Password',
                    html: `
                        <div class="text-start">
                            <div class="mb-3">
                                <input type="password" class="form-control" id="currentPassword" 
                                       placeholder="Password Saat Ini">
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" id="newPassword" 
                                       placeholder="Password Baru" minlength="6">
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" id="confirmPassword" 
                                       placeholder="Konfirmasi Password Baru" minlength="6">
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Simpan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#6d5dfc',
                    preConfirm: () => {
                        const newPass = $('#newPassword').val();
                        const confirm = $('#confirmPassword').val();
                        if (newPass !== confirm) {
                            Swal.showValidationMessage('Password tidak cocok');
                            return false;
                        }
                        if (newPass.length < 6) {
                            Swal.showValidationMessage('Password minimal 6 karakter');
                            return false;
                        }
                        return true;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Password telah diubah',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            });

            $('.data-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                responsive: true,
                pageLength: 10,
                initComplete: function() {
                    $('.dataTables_filter input').addClass('form-control');
                    $('.dataTables_length select').addClass('form-select');
                }
            });

            function createRipple(event, element) {
                const $btn = element;
                const offset = $btn.offset();
                const x = event.pageX - offset.left;
                const y = event.pageY - offset.top;
                const $ripple = $('<span class="ripple"></span>');
                $ripple.css({
                    left: x,
                    top: y,
                    width: '10px',
                    height: '10px'
                });
                $btn.append($ripple);
                setTimeout(() => {
                    $ripple.remove();
                }, 600);
            }

            $('.nav-item[href]').on('click', function(e) {
                const pageTitle = $(this).find('.nav-text').text();
                $('#currentPage span').text(pageTitle);
                document.title = 'SIAGA | ' + pageTitle;
                $('.nav-item').removeClass('active');
                $(this).addClass('active');
                if ($(window).width() <= 992) {
                    $('.sidebar-nav').removeClass('active');
                    $('#sidebarOverlay').removeClass('active');
                }
            });

            function setActiveFromUrl() {
                const currentPath = window.location.pathname;
                $('.nav-item[href]').each(function() {
                    const href = $(this).attr('href');
                    if (href && currentPath.includes(href) && href !== '#') {
                        $(this).addClass('active');
                        const pageTitle = $(this).find('.nav-text').text();
                        $('#currentPage span').text(pageTitle);
                        document.title = 'SIAGA | ' + pageTitle;
                    }
                });
            }
            setActiveFromUrl();
        });
    </script>

    @stack('scripts')
</body>

</html>

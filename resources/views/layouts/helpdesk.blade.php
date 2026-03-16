<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IT Helpdesk')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #1e3a5f;
            --primary-light: #2c5282;
            --primary-lighter: #3182ce;
            --accent: #e8913a;
            --accent-light: #f6ad55;
            --bg: #f4f7fa;
            --white: #ffffff;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --green: #059669;
            --green-light: #d1fae5;
            --red: #dc2626;
            --red-light: #fee2e2;
            --yellow: #d97706;
            --yellow-light: #fef3c7;
            --blue: #2563eb;
            --blue-light: #dbeafe;
            --radius: 12px;
            --shadow: 0 4px 20px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 40px rgba(0,0,0,0.1);
        }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--gray-800);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* ─── Navbar ────────────────────────────── */
        .navbar {
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .navbar-brand {
            font-size: 20px;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .navbar-brand span { color: var(--accent); }
        .navbar-links {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
        }
        .navbar-links a {
            text-decoration: none;
            color: var(--gray-600);
            font-size: 14px;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .navbar-links a:hover,
        .navbar-links a.active {
            color: var(--primary);
            background: var(--gray-100);
        }
        .btn-nav-cta {
            background: linear-gradient(135deg, var(--primary), var(--primary-lighter));
            color: var(--white) !important;
            padding: 9px 20px !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            box-shadow: 0 2px 8px rgba(30,58,95,0.25);
            transition: all 0.2s;
        }
        .btn-nav-cta:hover {
            background: linear-gradient(135deg, var(--primary-light), var(--primary)) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30,58,95,0.35);
        }

        /* Mobile menu */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 22px;
            color: var(--gray-700);
            cursor: pointer;
            padding: 8px;
        }
        @media (max-width: 768px) {
            .mobile-toggle { display: block; }
            .navbar-links {
                display: none;
                position: absolute;
                top: 64px;
                left: 0;
                right: 0;
                background: var(--white);
                flex-direction: column;
                padding: 16px;
                border-bottom: 1px solid var(--gray-200);
                box-shadow: var(--shadow);
            }
            .navbar-links.open { display: flex; }
            .navbar-links a { padding: 12px 16px; width: 100%; }
        }

        /* ─── Main ──────────────────────────────── */
        .main-content {
            max-width: 1100px;
            margin: 0 auto;
            padding: 32px 24px 60px;
        }

        /* ─── Cards ─────────────────────────────── */
        .card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--gray-100);
            font-size: 16px;
            font-weight: 700;
            color: var(--gray-800);
        }
        .card-body { padding: 24px; }

        /* ─── Buttons ───────────────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            line-height: 1.4;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-lighter));
            color: var(--white);
            box-shadow: 0 2px 8px rgba(30,58,95,0.25);
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(30,58,95,0.35);
        }
        .btn-secondary {
            background: var(--gray-100);
            color: var(--gray-700);
        }
        .btn-secondary:hover { background: var(--gray-200); }
        .btn-lg {
            padding: 14px 32px;
            font-size: 15px;
        }

        /* ─── Form ──────────────────────────────── */
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 6px;
        }
        .form-label .required { color: var(--red); }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--gray-300);
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            color: var(--gray-800);
            background: var(--white);
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .form-control:focus {
            border-color: var(--primary-lighter);
            box-shadow: 0 0 0 3px rgba(49,130,206,0.12);
        }
        .form-control.is-invalid { border-color: var(--red); }
        .invalid-feedback {
            color: var(--red);
            font-size: 12px;
            margin-top: 4px;
        }
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }
        textarea.form-control { resize: vertical; min-height: 100px; }

        /* ─── Alerts ─────────────────────────────── */
        .alert {
            padding: 14px 20px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success { background: var(--green-light); color: #065f46; }
        .alert-error { background: var(--red-light); color: #991b1b; }
        .alert-info { background: var(--blue-light); color: #1e40af; }
        .alert-warning { background: var(--yellow-light); color: #92400e; }

        /* ─── Badges ─────────────────────────────── */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-open { background: var(--blue-light); color: var(--blue); }
        .badge-progress { background: var(--yellow-light); color: var(--yellow); }
        .badge-resolved { background: var(--green-light); color: var(--green); }
        .badge-closed { background: var(--gray-200); color: var(--gray-600); }
        .badge-low { background: var(--green-light); color: var(--green); }
        .badge-medium { background: var(--yellow-light); color: var(--yellow); }
        .badge-high { background: #fed7d7; color: #c53030; }
        .badge-critical { background: #c53030; color: var(--white); }

        /* ─── Footer ─────────────────────────────── */
        .footer {
            text-align: center;
            padding: 24px;
            color: var(--gray-400);
            font-size: 13px;
            border-top: 1px solid var(--gray-200);
            margin-top: 40px;
        }

        /* ─── Animations ─────────────────────────── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in { animation: fadeInUp 0.5s ease-out forwards; }

        /* ─── Grid ───────────────────────────────── */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        @media (max-width: 640px) {
            .grid-2 { grid-template-columns: 1fr; }
        }
    </style>
    @yield('styles')
</head>
<body>
    {{-- Navbar --}}
    <nav class="navbar">
        <a href="{{ route('helpdesk.index') }}" class="navbar-brand">
            IT HELP<span>DESK</span>
        </a>
        <button class="mobile-toggle" onclick="document.querySelector('.navbar-links').classList.toggle('open')">
            <i class="fas fa-bars"></i>
        </button>
        <ul class="navbar-links">
            <li><a href="{{ route('helpdesk.index') }}" class="{{ request()->routeIs('helpdesk.index') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('helpdesk.guide') }}" class="{{ request()->routeIs('helpdesk.guide') ? 'active' : '' }}">Panduan</a></li>
            <li><a href="{{ route('helpdesk.track') }}" class="{{ request()->routeIs('helpdesk.track*') ? 'active' : '' }}">Lacak Tiket</a></li>
            <li><a href="{{ route('helpdesk.create') }}" class="btn-nav-cta">Buat Tiket <i class="fas fa-plus" style="font-size:12px"></i></a></li>
        </ul>
    </nav>

    {{-- Flash Messages --}}
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success animate-in"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error animate-in"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif

        @yield('content')
    </div>

    {{-- Footer --}}
    <footer class="footer">
        &copy; {{ date('Y') }} IT Helpdesk — Sistem Laporan Kendala IT
    </footer>

    @yield('scripts')
</body>
</html>

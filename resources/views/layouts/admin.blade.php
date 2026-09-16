<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - GameMarket Indonesia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Admin specific styles over app.css */
        :root {
            --sidebar-width: 260px;
            --topbar-height: 70px;
        }

        body {
            background-color: var(--bg-secondary);
            margin: 0;
            padding: 0;
            display: flex;
        }

        /* Sidebar */
        .admin-sidebar {
            width: var(--sidebar-width);
            background-color: var(--bg-primary);
            border-right: 1px solid var(--border-color);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

        .admin-sidebar__header {
            padding: var(--space-xl) var(--space-lg);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-sidebar__logo {
            font-family: var(--font-heading);
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--text-primary);
            text-decoration: none;
        }
        
        .admin-sidebar__subtitle {
            font-size: 0.8rem;
            color: var(--primary-cyan);
            margin-top: 4px;
        }

        .admin-nav {
            padding: var(--space-md) 0;
            flex: 1;
        }

        .admin-nav__item {
            padding: 0 var(--space-md);
            margin-bottom: 4px;
        }

        .admin-nav__link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: var(--radius-md);
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }

        .admin-nav__link:hover {
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.05);
        }

        .admin-nav__link.active {
            color: var(--bg-primary);
            background: var(--primary-cyan);
            font-weight: 600;
        }
        
        .admin-nav__link.active svg {
            color: var(--bg-primary);
        }

        .admin-sidebar__footer {
            padding: var(--space-lg);
            border-top: 1px solid var(--border-color);
        }

        /* Main Content */
        .admin-main {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .admin-topbar {
            height: var(--topbar-height);
            background-color: var(--bg-primary);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 var(--space-xl);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .admin-topbar__title {
            font-family: var(--font-heading);
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .admin-topbar__actions {
            display: flex;
            align-items: center;
            gap: var(--space-md);
        }

        .admin-content {
            padding: var(--space-xl);
            flex: 1;
            overflow-x: hidden;
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            cursor: pointer;
            padding: 8px;
        }

        /* Forms & Tables styling adjustments for Admin */
        .admin-card {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: var(--space-xl);
            margin-bottom: var(--space-xl);
        }
        
        .admin-table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        th {
            font-weight: 600;
            color: var(--text-secondary);
            background-color: rgba(255,255,255,0.02);
        }
        
        tr:hover td {
            background-color: rgba(255,255,255,0.02);
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: var(--radius-full);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .badge-active, .badge-completed, .badge-paid {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10B981; /* Green */
        }
        
        .badge-inactive {
            background-color: rgba(156, 163, 175, 0.1);
            color: #9CA3AF; /* Gray */
        }
        
        .badge-pending {
            background-color: rgba(245, 158, 11, 0.1);
            color: #F59E0B; /* Yellow */
        }
        
        .badge-waiting_payment {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3B82F6; /* Blue */
        }
        
        .badge-processing {
            background-color: rgba(139, 92, 246, 0.1);
            color: #8B5CF6; /* Purple */
        }
        
        .badge-cancelled, .badge-expired, .badge-failed {
            background-color: rgba(239, 68, 68, 0.1);
            color: #EF4444; /* Red */
        }

        /* Form elements */
        .form-group {
            margin-bottom: var(--space-lg);
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 14px;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            color: var(--text-primary);
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-cyan);
            box-shadow: 0 0 0 2px rgba(0,229,212,0.1);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }
        
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%238D9AAA' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 16px;
        }

        .btn-admin {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            border: none;
        }
        
        .btn-admin-primary {
            background-color: var(--primary-cyan);
            color: var(--bg-primary);
        }
        
        .btn-admin-primary:hover {
            background-color: #00c4b5;
        }
        
        .btn-admin-secondary {
            background-color: rgba(255,255,255,0.1);
            color: var(--text-primary);
        }
        
        .btn-admin-secondary:hover {
            background-color: rgba(255,255,255,0.15);
        }
        
        .btn-admin-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: #EF4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .btn-admin-danger:hover {
            background-color: rgba(239, 68, 68, 0.2);
        }
        
        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: var(--radius-md);
            background: rgba(255,255,255,0.05);
            color: var(--text-secondary);
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }
        
        .btn-icon:hover {
            background: rgba(255,255,255,0.1);
            color: var(--text-primary);
        }
        
        .btn-icon.edit:hover {
            background: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
        }
        
        .btn-icon.delete:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
        }

        .flex-between { display: flex; justify-content: space-between; align-items: center; }
        .flex-gap { display: flex; gap: var(--space-md); align-items: center; }
        
        /* Toast Container */
        #toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .toast {
            padding: 16px 20px;
            border-radius: var(--radius-md);
            color: white;
            font-weight: 500;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            animation: slideInRight 0.3s ease forwards;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 300px;
        }
        
        .toast-success { background: #10B981; }
        .toast-error { background: #EF4444; }
        
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @media (max-width: 1024px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.open {
                transform: translateX(0);
            }
            .admin-main {
                margin-left: 0;
            }
            .mobile-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="sidebar">
        <div class="admin-sidebar__header">
            <i data-lucide="gamepad-2" style="color: var(--primary-cyan);"></i>
            <div>
                <a href="{{ route('home') }}" class="admin-sidebar__logo">GameMarket</a>
                <div class="admin-sidebar__subtitle">Admin Panel</div>
            </div>
        </div>

        <nav class="admin-nav">
            @php
                $current = request()->route()->getName();
            @endphp
            
            <div class="admin-nav__item">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav__link {{ str_starts_with($current, 'admin.dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard"></i> Dashboard
                </a>
            </div>
            
            <div class="admin-nav__item">
                <a href="{{ route('admin.orders.index') }}" class="admin-nav__link {{ str_starts_with($current, 'admin.orders') ? 'active' : '' }}">
                    <i data-lucide="shopping-cart"></i> Pesanan
                </a>
            </div>
            
            <div class="admin-nav__item">
                <a href="{{ route('admin.payments.index') }}" class="admin-nav__link {{ str_starts_with($current, 'admin.payments') ? 'active' : '' }}">
                    <i data-lucide="credit-card"></i> Pembayaran
                </a>
            </div>

            <div class="admin-nav__item" style="margin-top: 15px; margin-bottom: 5px; padding: 0 16px;">
                <small style="color: var(--text-muted); font-weight: 600; text-transform: uppercase; font-size: 0.7rem;">Katalog</small>
            </div>
            
            <div class="admin-nav__item">
                <a href="{{ route('admin.products.index') }}" class="admin-nav__link {{ str_starts_with($current, 'admin.products') ? 'active' : '' }}">
                    <i data-lucide="package"></i> Produk
                </a>
            </div>
            
            <div class="admin-nav__item">
                <a href="{{ route('admin.games.index') }}" class="admin-nav__link {{ str_starts_with($current, 'admin.games') ? 'active' : '' }}">
                    <i data-lucide="swords"></i> Game
                </a>
            </div>
            
            <div class="admin-nav__item">
                <a href="{{ route('admin.categories.index') }}" class="admin-nav__link {{ str_starts_with($current, 'admin.categories') ? 'active' : '' }}">
                    <i data-lucide="folder-tree"></i> Kategori
                </a>
            </div>

            <div class="admin-nav__item" style="margin-top: 15px; margin-bottom: 5px; padding: 0 16px;">
                <small style="color: var(--text-muted); font-weight: 600; text-transform: uppercase; font-size: 0.7rem;">Konten & Promo</small>
            </div>
            
            <div class="admin-nav__item">
                <a href="{{ route('admin.promos.index') }}" class="admin-nav__link {{ str_starts_with($current, 'admin.promos') ? 'active' : '' }}">
                    <i data-lucide="gift"></i> Promo
                </a>
            </div>
            
            <div class="admin-nav__item">
                <a href="{{ route('admin.banners.index') }}" class="admin-nav__link {{ str_starts_with($current, 'admin.banners') ? 'active' : '' }}">
                    <i data-lucide="image"></i> Banner
                </a>
            </div>
            
            <div class="admin-nav__item">
                <a href="{{ route('admin.news.index') }}" class="admin-nav__link {{ str_starts_with($current, 'admin.news') ? 'active' : '' }}">
                    <i data-lucide="newspaper"></i> Berita
                </a>
            </div>

            <div class="admin-nav__item" style="margin-top: 15px; margin-bottom: 5px; padding: 0 16px;">
                <small style="color: var(--text-muted); font-weight: 600; text-transform: uppercase; font-size: 0.7rem;">Sistem</small>
            </div>
            
            <div class="admin-nav__item">
                <a href="{{ route('admin.users.index') }}" class="admin-nav__link {{ str_starts_with($current, 'admin.users') ? 'active' : '' }}">
                    <i data-lucide="users"></i> Users
                </a>
            </div>
            
            <div class="admin-nav__item">
                <a href="{{ route('admin.mockPayment.index') }}" class="admin-nav__link {{ str_starts_with($current, 'admin.mockPayment') ? 'active' : '' }}">
                    <i data-lucide="terminal"></i> Mock Payment
                </a>
            </div>
            
            <div class="admin-nav__item">
                <a href="{{ route('admin.auditLogs.index') }}" class="admin-nav__link {{ str_starts_with($current, 'admin.auditLogs') ? 'active' : '' }}">
                    <i data-lucide="clipboard-list"></i> Audit Log
                </a>
            </div>
        </nav>

        <div class="admin-sidebar__footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="admin-nav__link" style="width: 100%; background: none; border: none; cursor: pointer; color: #EF4444;">
                    <i data-lucide="log-out"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <!-- Topbar -->
        <header class="admin-topbar">
            <div class="flex-gap">
                <button class="mobile-toggle" id="sidebar-toggle">
                    <i data-lucide="menu"></i>
                </button>
                <h1 class="admin-topbar__title">@yield('title')</h1>
            </div>
            <div class="admin-topbar__actions">
                <a href="{{ route('home') }}" target="_blank" class="btn-admin btn-admin-secondary" style="padding: 6px 12px; font-size: 0.8rem;">
                    <i data-lucide="external-link" style="width: 16px; height: 16px;"></i> Lihat Web
                </a>
                <div style="color: var(--text-secondary); font-size: 0.9rem; border-left: 1px solid var(--border-color); padding-left: 15px;">
                    Hi, {{ Auth::user()->name ?? 'Admin' }}
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="admin-content">
            @if(session('success'))
                <div id="flash-success" data-message="{{ session('success') }}"></div>
            @endif
            @if(session('error'))
                <div id="flash-error" data-message="{{ session('error') }}"></div>
            @endif
            
            @if($errors->any())
                <div class="admin-card" style="border-color: #EF4444; background: rgba(239, 68, 68, 0.05); padding: 15px;">
                    <ul style="color: #EF4444; margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <div id="toast-container"></div>

    <script>
        lucide.createIcons();

        // Sidebar toggle
        const toggleBtn = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('open');
            });
        }

        // Toasts
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            
            const icon = type === 'success' ? 'check-circle' : 'alert-circle';
            toast.innerHTML = `<i data-lucide="${icon}"></i> <span>${message}</span>`;
            
            container.appendChild(toast);
            lucide.createIcons({ root: toast });
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Check for flash messages
        const successFlash = document.getElementById('flash-success');
        if (successFlash) showToast(successFlash.dataset.message, 'success');
        
        const errorFlash = document.getElementById('flash-error');
        if (errorFlash) showToast(errorFlash.dataset.message, 'error');
        
        // Confirm Delete
        function confirmDelete(formId, message = 'Apakah Anda yakin ingin menghapus data ini?') {
            if (confirm(message)) {
                document.getElementById(formId).submit();
            }
        }
    </script>
    
    @stack('scripts')
</body>
</html>

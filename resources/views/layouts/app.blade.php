<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f5f7fb;
            color: #111827;
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: 14px;
        }

        .admin-shell {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: 260px;
            background: linear-gradient(180deg, #0c1728 0%, #132238 58%, #0b1628 100%);
            color: #e5e7eb;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 6px 0 24px rgba(15, 23, 42, .14);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            height: 82px;
            padding: 0 24px;
            border-bottom: 1px solid rgba(255, 255, 255, .09);
            font-size: 20px;
            font-weight: 700;
            color: #fff;
        }

        .brand-icon {
            display: grid;
            place-items: center;
            width: 34px;
            height: 34px;
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 8px;
            color: #93c5fd;
        }

        .menu {
            padding: 18px 14px;
        }

        .menu a,
        .menu button {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            min-height: 44px;
            margin-bottom: 6px;
            padding: 10px 14px;
            border: 0;
            border-radius: 8px;
            background: transparent;
            color: #cbd5e1;
            text-decoration: none;
            line-height: 1;
            transition: background .18s ease, color .18s ease;
        }

        .menu a:hover,
        .menu button:hover,
        .menu a.active {
            background: #2563eb;
            color: #fff;
        }

        .menu i {
            width: 18px;
            text-align: center;
            font-size: 16px;
        }

        .menu-badge {
            margin-left: auto;
            min-width: 26px;
            padding: 4px 7px;
            border-radius: 999px;
            background: #f97316;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            text-align: center;
        }

        .menu-divider {
            height: 1px;
            margin: 14px 14px;
            background: rgba(255, 255, 255, .12);
        }

        .main-content {
            width: calc(100% - 260px);
            margin-left: 260px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 82px;
            padding: 0 28px;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .icon-button {
            display: grid;
            place-items: center;
            width: 38px;
            height: 38px;
            border: 0;
            border-radius: 8px;
            background: #f8fafc;
            color: #64748b;
        }

        .search-box {
            position: relative;
            width: min(330px, 38vw);
        }

        .search-box i {
            position: absolute;
            top: 50%;
            left: 13px;
            color: #94a3b8;
            transform: translateY(-50%);
        }

        .search-box input {
            width: 100%;
            height: 40px;
            padding: 0 14px 0 38px;
            border: 1px solid #dfe6f0;
            border-radius: 8px;
            outline: none;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .notification {
            position: relative;
            color: #64748b;
            font-size: 18px;
        }

        .notification span {
            position: absolute;
            top: -9px;
            right: -9px;
            display: grid;
            place-items: center;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            border-radius: 999px;
            background: #ef4444;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
        }

        .admin-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-left: 6px;
        }

        .admin-avatar {
            display: grid;
            place-items: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #dbeafe;
            color: #1d4ed8;
            font-weight: 800;
        }

        .admin-user strong,
        .admin-user span {
            display: block;
            line-height: 1.15;
        }

        .admin-user span {
            margin-top: 3px;
            color: #64748b;
            font-size: 12px;
        }

        .content {
            padding: 26px 28px 18px;
        }

        .content-card {
            background: transparent;
        }

        .admin-alert {
            border: 0;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
        }

        .admin-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 22px;
            padding: 16px 4px 0;
            color: #64748b;
            font-size: 12px;
        }

        @media (max-width: 992px) {
            .sidebar {
                width: 230px;
            }

            .main-content {
                width: calc(100% - 230px);
                margin-left: 230px;
            }

            .topbar {
                padding: 0 18px;
            }

            .search-box {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .admin-shell {
                display: block;
            }

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main-content {
                width: 100%;
                margin-left: 0;
            }

            .topbar {
                height: auto;
                min-height: 72px;
                flex-wrap: wrap;
                gap: 12px;
                padding: 14px 16px;
            }

            .topbar-actions {
                width: 100%;
                justify-content: space-between;
            }

            .content {
                padding: 18px 14px;
            }
        }
    </style>
</head>

<body>
    @php
        $pendingMenuCount =
            auth()->check() && auth()->user()->isAdmin() ? \App\Models\Product::where('status', 'pending')->count() : 0;
    @endphp

    <div class="admin-shell">
        <aside class="sidebar">
            <div class="brand">
                <span class="brand-icon"><i class="bi bi-bag"></i></span>
                Store Admin
            </div>

            <nav class="menu">
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('seller.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') || request()->routeIs('seller.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i>
                    Dashboard
                </a>

                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.user') }}" class="{{ request()->routeIs('admin.user') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        Users
                    </a>
                @endif

                <a href="{{ route('products.index') }}"
                    class="{{ request()->routeIs('products.*') && !request()->routeIs('products.pending') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i>
                    Products
                </a>

                @if (auth()->user()->isAdmin())
                    <a href="{{ route('products.pending') }}"
                        class="{{ request()->routeIs('products.pending') ? 'active' : '' }}">
                        <i class="bi bi-hourglass-split"></i>
                        Pending Products
                        @if ($pendingMenuCount > 0)
                            <span class="menu-badge">{{ $pendingMenuCount }}</span>
                        @endif
                    </a>
                @endif

                <a href="{{ route('categories.index') }}"
                    class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="bi bi-grid"></i>
                    Categories
                </a>

                <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">
                    <i class="bi bi-cart3"></i>
                    Orders
                </a>

                @if (auth()->user()->isAdmin())
                    <a href="{{ route('coupons.index') }}"
                        class="{{ request()->routeIs('coupons.*') ? 'active' : '' }}">
                        <i class="bi bi-percent"></i>
                        Coupons
                    </a>
                @endif

                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.tickets') }}"
                        class="{{ request()->routeIs('admin.tickets') || request()->routeIs('tickets.*') ? 'active' : '' }}">
                        <i class="bi bi-ticket-perforated"></i>
                        Tickets
                    </a>


                    <a href="{{ route('ticket-categories.index') }}"
                        class="{{ request()->routeIs('ticket-categories.*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i>
                        Ticket Categories
                    </a>

                    <a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i>
                    Blog
                </a>
                @endif

                

                <a href="{{ route('profile.edit') }}"
                    class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    Settings
                </a>

                <div class="menu-divider"></div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">
                        <i class="bi bi-box-arrow-left"></i>
                        Logout
                    </button>
                </form>
            </nav>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <div class="topbar-left">
                    <button class="icon-button" type="button" aria-label="Menu">
                        <i class="bi bi-list"></i>
                    </button>

                    <form class="search-box" action="{{ route('products.index') }}" method="GET">
                        <i class="bi bi-search"></i>
                        <input type="search" name="search" placeholder="Search here...">
                    </form>
                </div>

                <div class="topbar-actions">
                    <div class="notification">
                        <i class="bi bi-bell"></i>
                        @if ($pendingMenuCount > 0)
                            <span>{{ $pendingMenuCount }}</span>
                        @endif
                    </div>

                    <div class="notification">
                        <i class="bi bi-envelope"></i>
                        <span>0</span>
                    </div>

                    <div class="admin-user">
                        <div class="admin-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        <div>
                            <strong>{{ auth()->user()->name }}</strong>
                            <span>{{ auth()->user()->isAdmin() ? 'Super Admin' : 'Seller' }}</span>
                        </div>
                        <i class="bi bi-chevron-down text-muted"></i>
                    </div>
                </div>
            </header>

            <section class="content">
                @if (session('success'))
                    <div class="alert alert-success admin-alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger admin-alert">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="content-card">
                    @yield('content')
                </div>

                <footer class="admin-footer">
                    <span>&copy; {{ date('Y') }} Store Admin. All rights reserved.</span>
                    <span>Version 1.0.0</span>
                </footer>

                @yield('scripts')
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f4f6f9;
            font-family: "Segoe UI", sans-serif;
        }

        /* SIDEBAR */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, #111827, #1f2937);
            overflow-y: auto;
            box-shadow: 4px 0 20px rgba(0, 0, 0, .1);
            z-index: 1000;
        }

        .logo {
            padding: 25px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
        }

        .logo h3 {
            color: white;
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }

        .menu {
            padding: 15px;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #d1d5db;
            padding: 14px 18px;
            margin-bottom: 8px;
            border-radius: 12px;
            transition: .3s;
        }

        .menu a:hover {
            background: rgba(255, 255, 255, .08);
            color: white;
            transform: translateX(5px);
        }

        .menu a.active {
            background: #2563eb;
            color: white;
        }

        /* MAIN CONTENT */

        .main-content {
            margin-left: 260px;
            min-height: 100vh;
        }

        /* TOPBAR */

        .topbar {
            background: white;
            height: 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, .05);
        }

        .topbar-title {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
        }

        .user-box {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* PAGE CONTENT */

        .content {
            padding: 30px;
        }

        .content-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .05);
        }

        /* MOBILE */

        @media(max-width:768px) {

            .sidebar {
                width: 220px;
            }

            .main-content {
                margin-left: 220px;
            }

            .topbar {
                padding: 0 15px;
            }

            .topbar-title {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->

    <div class="sidebar">

        <div class="logo">
            <h3>🚀 Admin Panel</h3>
        </div>

        <div class="menu">

            <a href="{{ route('profile.edit') }}">
                <i class="bi bi-speedometer2"></i>
                profile
            </a>

            <a href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>

            <a href="{{ route('products.index') }}">
                <i class="bi bi-box-seam"></i>
                Products
            </a>

            @if (auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('products.pending') }}">
                    <i class="bi bi-hourglass-split"></i>
                    Pending Products
                </a>
            @endif

            <a href="{{ route('categories.index') }}">
                <i class="bi bi-grid"></i>
                Categories
            </a>

            <a href="{{ route('orders.index') }}">
                <i class="bi bi-cart3"></i>
                Orders
            </a>

            <a href="{{ route('blog.index') }}">
                <i class="bi bi-journal-text"></i>
                Blog
            </a>

            <a href="{{ route('admin.tickets') }}">
                <i class="bi bi-ticket-perforated"></i>
                Tickets
            </a>

            @if (auth()->check() && auth()->user()->role === 'admin')
                <a href="/ticket-categories">
                    <i class="bi bi-tags"></i>
                    Ticket Categories
                </a>

                <a href="{{ route('coupons.index') }}">
                    <i class="bi bi-percent"></i>
                    Coupons
                </a>

                <a href="{{ route('admin.user') }}">
                    <i class="bi bi-people"></i>
                    Users
                </a>
            @endif

        </div>

    </div>

    <!-- MAIN CONTENT -->

    <div class="main-content">

        <!-- HEADER -->

        <div class="topbar">

            <div class="topbar-title">
                Store Management
            </div>

            <div class="user-box">

                <span>
                    Welcome,
                    <strong>{{ auth()->user()->name }}</strong>
                </span>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button class="btn btn-danger">
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </button>
                </form>

            </div>

        </div>

        <!-- CONTENT -->

        <div class="content">

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="content-card">
                @yield('content')
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

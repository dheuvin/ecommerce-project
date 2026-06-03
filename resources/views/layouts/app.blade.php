<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .sidebar {
            height: 100vh;
            width: 250px;
            background: black;
            padding: 20px;
        }

        .sidebar a {
            color: #ffffff;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            margin-bottom: 8px;
            border-radius: 5px;
        }

        .sidebar a:hover {
            background: #343a40;
        }
    </style>
</head>

<body class="bg-light">

    <!-- NAVBAR -->

    @include('admin.header')

    <!-- PAGE LAYOUT -->
    <div class="d-flex">

        <!-- SIDEBAR -->
        <div class="sidebar">



            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('blog.index') }}">blog</a>
            <a href="{{ route('admin.tickets') }}">tickets</a>
            <a href="{{ route('categories.index') }}">Categories</a>
            <a href="{{ route('products.index') }}">Products</a>
            <a href="{{ route('orders.index') }}">Orders</a>
            @if (auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('coupons.index') }}">
                    Coupons
                </a>
                <a href={{ route('admin.user') }}>User</a>
            @endif

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-danger w-100 mt-3">Logout</button>
            </form>
        </div>

        <!-- PAGE CONTENT -->
        <div class="flex-grow-1 p-4">
            @yield('content')
        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

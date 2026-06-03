<!DOCTYPE html>
<html>

<head>
    <title>Ecommerce</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom">
        <div class="container">

            <!-- LOGO -->
            <a class="navbar-brand fw-bold fs-3" href="{{ route('shop.index') }}">
                Ecommerce
            </a>

            <!-- MOBILE TOGGLE -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">

                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">


                <!-- CATEGORY DROPDOWNS -->
                <!-- CATEGORY DROPDOWNS -->
                <ul class="navbar-nav me-auto">

                    @foreach ($categories as $category)
                        <li class="nav-item dropdown">

                            <!-- CATEGORY -->
                            <a class="nav-link dropdown-toggle fw-semibold"
                                href="{{ route('category.products', $category->id) }}" role="button"
                                data-bs-toggle="dropdown">

                                {{ $category->name }}

                            </a>

                            <!-- SUBCATEGORY -->
                            <ul class="dropdown-menu">

                                @foreach ($category->children as $subcategory)
                                    <li>

                                        <a class="dropdown-item"
                                            href="{{ route('subcategory.products', $subcategory->id) }}">

                                            {{ $subcategory->name }}

                                        </a>

                                    </li>
                                @endforeach

                            </ul>

                        </li>
                    @endforeach

                </ul>



                <!-- RIGHT SIDE BUTTONS -->
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <a href="{{ route('blog.index') }}" class="btn btn-outline-dark btn-sm">blog</a>

                    @auth

                        @if (auth()->user()->isCustomer())
                            <a href="{{ route('wishlist.index') }}" class="btn btn-outline-dark btn-sm position-relative">

                                Wishlist

                                <span id="wishlist-count-badge" class="badge bg-dark ms-1">

                                    {{ $wishlistCount ?? 0 }}

                                </span>

                            </a>


                            <a href="{{ route('orders.index') }}" class="btn btn-outline-dark btn-sm">

                                Orders

                            </a>

                            <a href="{{ route('cart.index') }}" class="btn btn-dark btn-sm position-relative">

                                Cart

                                <span id="cart-count-badge" class="badge bg-light text-dark ms-1">

                                    {{ $cartItemCount ?? 0 }}

                                </span>

                            </a>
                        @elseif (auth()->user()->isSeller())
                            <a href="{{ route('seller.dashboard') }}" class="btn btn-outline-dark btn-sm">

                                Dashboard

                            </a>
                        @elseif (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark btn-sm">

                                Admin

                            </a>
                        @endif

                        <form action="{{ route('logout') }}" method="POST" class="mb-0">

                            @csrf

                            <button class="btn btn-dark btn-sm">

                                Logout

                            </button>

                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-dark btn-sm">

                            Login

                        </a>

                        <a href="{{ route('register') }}" class="btn btn-dark btn-sm">

                            Register

                        </a>

                    @endauth

                </div>

            </div>

        </div>
    </nav>

    <!-- ALERTS -->
    <div class="container mt-4">

        <div id="ajax-feedback">

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

            @if ($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0 ps-3">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif

        </div>

    </div>

    <!-- PAGE CONTENT -->
    <<main class="flex-grow-1">
        @yield('content')
    </main>

    @include('footer.user')



    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

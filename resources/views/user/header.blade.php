<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top shadow-sm py-3">
    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('shop.index') }}">
            <span class="bg-dark text-white rounded-3 d-flex align-items-center justify-content-center me-2"
                style="width:40px;height:40px;">
                <i class="bi bi-bag-check"></i>
            </span>
            Ecommerce
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">

            <!-- Categories -->
            <ul class="navbar-nav ms-lg-4">
                @foreach ($categories as $category)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-semibold"
                            href="{{ route('category.products', $category->id) }}" data-bs-toggle="dropdown">

                            {{ $category->name }}
                        </a>

                        <ul class="dropdown-menu shadow border-0 rounded-4">
                            <li>
                                <a class="dropdown-item" href="{{ route('category.products', $category->id) }}">
                                    All {{ $category->name }}
                                </a>
                            </li>

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

                {{-- <li class="nav-item">
                        <a class="nav-link fw-semibold" href="{{ route('blog.index') }}">
                            Blogs
                        </a>
                    </li> --}}
            </ul>

            <!-- Search -->
            <form action="{{ route('shop.index') }}" method="GET" class="mx-auto w-100 px-lg-4"
                style="max-width:500px;">

                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-search"></i>
                    </span>

                    <input type="text" name="search" class="form-control border-start-0"
                        placeholder="Search products...">

                    <button class="btn btn-dark">
                        Search
                    </button>
                </div>
            </form>

            <!-- Right Side -->
            <div class="d-flex align-items-center gap-2">

                @auth
                    <a href="{{ route('profile.edit') }}" class="btn btn-light position-relative">
                        <i class="bi bi-person"></i>
                        profile</a>

                    @if (auth()->user()->isCustomer())
                        <!-- Wishlist -->
                        <a href="{{ route('wishlist.index') }}" class="btn btn-light position-relative">

                            <i class="bi bi-heart"></i>

                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $wishlistCount ?? 0 }}
                            </span>
                        </a>

                        <!-- Orders -->
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-dark">
                            Orders
                        </a>

                        <!-- Cart -->
                        <a href="{{ route('cart.index') }}" class="btn btn-dark position-relative">

                            <i class="bi bi-cart"></i>

                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">
                                {{ $cartItemCount ?? 0 }}
                            </span>
                        </a>
                    @elseif(auth()->user()->isSeller())
                        <a href="{{ route('seller.dashboard') }}" class="btn btn-outline-dark">
                            Dashboard
                        </a>
                    @elseif(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark">
                            Admin
                        </a>
                    @endif



                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf

                        <button class="btn btn-danger">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-dark">
                        Login
                    </a>

                    <a href="{{ route('register') }}" class="btn btn-dark">
                        Register
                    </a>

                @endauth

            </div>

        </div>

    </div>
</nav>

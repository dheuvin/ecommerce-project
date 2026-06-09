@php
    $headerCategories = $categories ?? collect();
@endphp

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

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">

            <!-- Search -->
            <form action="{{ route('shop.index') }}" method="GET" class="mx-auto w-100 px-lg-4"
                style="max-width:500px;">

                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-search"></i>
                    </span>

                    <input type="text" name="search" class="form-control border-start-0"
                        value="{{ request('search') }}" placeholder="Search products...">

                    <button type="submit" class="btn btn-dark">
                        Search
                    </button>
                </div>
            </form>

            <!-- Right Side -->
            <div class="d-flex align-items-center gap-2">

                @auth
                    <a href="{{ route('profile.edit') }}" class="btn btn-light position-relative">
                        <i class="bi bi-person"></i>
                        Profile
                    </a>

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
                    @elseif (auth()->user()->isSeller())
                        <a href="{{ route('seller.dashboard') }}" class="btn btn-outline-dark">
                            Dashboard
                        </a>
                    @elseif (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark">
                            Admin
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf

                        <button type="submit" class="btn btn-danger">
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

<div class="category-bar border-bottom py-2">
    <div class="container">
        <button id="filterBtn" type="button" class="btn btn-outline-dark">
            <i class="bi bi-funnel"></i> Filter
        </button>

        <!-- Parent Categories -->
        <div class="parent-scroll d-flex mt-2">
            @foreach ($headerCategories as $category)
                <button type="button" class="btn btn-light parent-category me-2" data-category="{{ $category->id }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        <!-- Sub Categories -->
        <div id="subcategory-container" class="mt-2"></div>
    </div>
</div>

@foreach ($headerCategories as $category)
    <div id="subcats-{{ $category->id }}" class="d-none">
        @foreach ($category->children as $subcategory)
            <a href="{{ route('subcategory.products', $subcategory->id) }}"
                class="btn btn-sm btn-outline-secondary me-2 mb-2">
                {{ $subcategory->name }}
            </a>
        @endforeach
    </div>
@endforeach

<div id="filterPanel" class="filter-panel d-none">
    <form action="{{ route('shop.index') }}" method="GET">
        <h5 class="fw-bold mb-3">Filters</h5>

        @if (request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
        @endif

        <h6>Price</h6>

        <div class="row g-2 mb-3">
            <div class="col-5">
                <input type="number" name="min_price" class="form-control" value="{{ request('min_price') }}"
                    min="0" placeholder="Min">
            </div>

            <div class="col-2 text-center">
                -
            </div>

            <div class="col-5">
                <input type="number" name="max_price" class="form-control" value="{{ request('max_price') }}"
                    min="0" placeholder="Max">
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-sm btn-outline-dark w-100">
                    Apply
                </button>
            </div>
        </div>

        <h6 class="mt-4">Price Range</h6>

        <div class="form-check">
            <input type="radio" id="price-under-500" class="form-check-input" name="price_range" value="0-500"
                @checked(request('price_range') === '0-500')>
            <label class="form-check-label" for="price-under-500">Under Rs. 500</label>
        </div>

        <div class="form-check">
            <input type="radio" id="price-500-1000" class="form-check-input" name="price_range" value="500-1000"
                @checked(request('price_range') === '500-1000')>
            <label class="form-check-label" for="price-500-1000">Rs. 500 - Rs. 1000</label>
        </div>

        <div class="form-check">
            <input type="radio" id="price-1000-5000" class="form-check-input" name="price_range" value="1000-5000"
                @checked(request('price_range') === '1000-5000')>
            <label class="form-check-label" for="price-1000-5000">Rs. 1000 - Rs. 5000</label>
        </div>

        <div class="form-check">
            <input type="radio" id="price-5000-above" class="form-check-input" name="price_range" value="5000-above"
                @checked(request('price_range') === '5000-above')>
            <label class="form-check-label" for="price-5000-above">Above Rs. 5000</label>
        </div>

        <button type="submit" class="btn btn-sm btn-outline-dark w-100 mt-3">
            Apply Range
        </button>

        <a href="{{ route('shop.index') }}" class="btn btn-light border w-100 mt-2">
            Reset Filters
        </a>
    </form>
</div>

<script>
    const filterButton = document.getElementById('filterBtn');
    const filterPanel = document.getElementById('filterPanel');

    if (filterButton && filterPanel) {
        filterButton.addEventListener('click', function() {
            filterPanel.classList.toggle('d-none');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.parent-category');
        const container = document.getElementById('subcategory-container');

        if (!container) {
            return;
        }

        buttons.forEach(btn => {
            btn.addEventListener('click', function() {
                const categoryId = this.dataset.category;
                const source = document.getElementById('subcats-' + categoryId);

                container.innerHTML = source ? source.innerHTML : '';
            });
        });
    });
</script>

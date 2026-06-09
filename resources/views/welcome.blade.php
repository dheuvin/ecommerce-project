@extends('layouts.user')

@section('title', 'Premium Ecommerce')

@section('content')
    @php($featuredCategories = $products->pluck('category')->filter()->unique('id')->take(4))

    
    <section class="position-relative overflow-hidden">
        <div class="container section-pad">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="eyebrow mb-3">Curated marketplace</div>
                    <h1 class="display-4 fw-bold mb-4" style="line-height:1.04;">Premium finds from trusted sellers.</h1>
                    <p class="lead text-secondary mb-4">
                        Discover refined essentials, verified merchants, secure checkout, and fast delivery in one polished
                        shopping experience.
                    </p>
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <a href="#products" class="btn btn-dark btn-lg btn-premium px-4">
                            Shop Collection <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-outline-dark btn-lg btn-premium px-4">Create
                                Account</a>
                        @endguest
                    </div>
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="soft-surface p-3 h-100">
                                <div class="fw-bold">Secure</div>
                                <small class="text-muted">Protected checkout</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="soft-surface p-3 h-100">
                                <div class="fw-bold">Verified</div>
                                <small class="text-muted">Seller quality</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="soft-surface p-3 h-100">
                                <div class="fw-bold">Fast</div>
                                <small class="text-muted">Easy delivery</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="surface overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=1200&auto=format&fit=crop"
                            class="w-100" style="height:min(560px, 70vh); object-fit:cover;"
                            alt="Premium product collection">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-4">
        <div class="container">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">Shop by Category</h4>
            </div>

            <div class="category-wrapper">

                <div class="category-scroll d-flex gap-3">

                    @foreach ($featuredCategories as $category)
                        <a href="{{ route('category.products', $category->id) }}"
                            class="text-decoration-none text-dark category-item">

                            <div class="card category-card text-center shadow-sm border-0">

                                <img src="{{ asset('storage/' . $category->image) }}" class="p-3"
                                    alt="{{ $category->name }}" style="height:90px; width:100%; object-fit:contain;">

                                <div class="card-body p-2">
                                    <small class="fw-semibold">{{ $category->name }}</small>
                                </div>

                            </div>
                        </a>
                    @endforeach

                </div>

            </div>

        </div>
    </section>

    <section class="py-4">
        <div class="container">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="soft-surface p-4 h-100">
                        <i class="bi bi-truck fs-3"></i>
                        <h5 class="fw-bold mt-3 mb-1">Priority Delivery</h5>
                        <p class="text-muted mb-0 small">Reliable shipping with clear order visibility.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="soft-surface p-4 h-100">
                        <i class="bi bi-shield-check fs-3"></i>
                        <h5 class="fw-bold mt-3 mb-1">Secure Checkout</h5>
                        <p class="text-muted mb-0 small">Protected payments and privacy-first account handling.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="soft-surface p-4 h-100">
                        <i class="bi bi-patch-check fs-3"></i>
                        <h5 class="fw-bold mt-3 mb-1">Trusted Sellers</h5>
                        <p class="text-muted mb-0 small">Products sourced from accountable marketplace partners.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="products" class="section-pad">
        <div class="container">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-4 mb-4">
                <div>
                    <div class="eyebrow mb-2">New arrivals</div>
                    <h2 class="fw-bold mb-1">Featured Products</h2>
                    <p class="text-muted mb-0">{{ $products->count() }} products available from our marketplace.</p>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    @foreach ($featuredCategories as $category)
                        <a href="{{ route('category.products', $category->id) }}"
                            class="btn btn-outline-dark btn-premium btn-sm px-3">
                            {{ $category->name }}
                        </a>
                    @endforeach
                    <button class="btn btn-light border btn-premium btn-sm px-3" type="button">
                        <i class="bi bi-sliders me-1"></i> Filters
                    </button>
                    <select class="form-select form-select-sm" style="width:150px;" aria-label="Sort products">
                        <option>Latest</option>
                        <option>Price: Low</option>
                        <option>Price: High</option>
                    </select>
                </div>
            </div>

            @if ($products->isEmpty())
                <div class="soft-surface text-center p-5">
                    <i class="bi bi-search display-5 text-muted"></i>
                    <h4 class="fw-bold mt-3">No products found</h4>
                    <p class="text-muted mb-4">Try another category or return to the main collection.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-dark btn-premium px-4">View All Products</a>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($products as $product)
                        @php($isWishlisted = in_array($product->id, $wishlistProductIds ?? [], true))
                        <div class="col-6 col-lg-4 col-xl-3">
                            <article class="card product-card h-100">
                                <div class="product-media">
                                    <a href="{{ route('product.view', $product) }}" class="d-block h-100">
                                        @if ($product->primary_image_path)
                                            <img src="{{ asset('storage/' . $product->primary_image_path) }}"
                                                alt="{{ $product->name }}">
                                        @else
                                            <div
                                                class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                                                <i class="bi bi-image fs-2 mb-2"></i>
                                                <span class="small fw-semibold">No Image</span>
                                            </div>
                                        @endif
                                    </a>

                                    @auth
                                        @if (auth()->user()->isCustomer())
                                            <form method="POST" action="{{ route('wishlist.toggle', $product) }}"
                                                class="floating-action" data-ajax-wishlist="true"
                                                data-product-id="{{ $product->id }}">
                                                @csrf
                                                <button type="submit"
                                                    class="btn {{ $isWishlisted ? 'btn-dark' : 'btn-light' }} btn-icon shadow-sm"
                                                    aria-label="Wishlist">
                                                    <i class="bi {{ $isWishlisted ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>

                                <div class="card-body d-flex flex-column p-3">
                                    <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                                        <span
                                            class="small text-muted fw-semibold text-truncate">{{ $product->category->name ?? 'Collection' }}</span>
                                        <span class="rating-stars" aria-label="Rating">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star"></i>
                                        </span>
                                    </div>
                                    <h6 class="fw-bold mb-2">
                                        <a href="{{ route('product.view', $product) }}"
                                            class="text-dark text-decoration-none">{{ $product->name }}</a>
                                    </h6>
                                    <small class="text-muted mb-3"><i
                                            class="bi bi-shop me-1"></i>{{ $product->user->name ?? 'Merchant' }}</small>
                                    <div class="d-flex align-items-center justify-content-between mt-auto gap-2">
                                        <span class="price">Rs. {{ number_format($product->price, 2) }}</span>
                                        <span
                                            class="badge {{ $product->stock > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                            {{ $product->stock > 0 ? 'In stock' : 'Sold out' }}
                                        </span>
                                    </div>

                                    <div class="d-grid gap-2 mt-3">
                                        @auth
                                            @if (auth()->user()->isCustomer())
                                                <form method="POST" action="{{ route('cart.add', $product) }}"
                                                    data-ajax-cart="true">
                                                    @csrf
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button class="btn btn-dark btn-premium w-100"
                                                        @disabled($product->stock < 1)>
                                                        <i class="bi bi-bag-plus me-1"></i> Add to Cart
                                                    </button>
                                                </form>
                                            @elseif (auth()->user()->isSeller() && auth()->id() === $product->user_id)
                                                <a href="{{ route('products.edit', $product) }}"
                                                    class="btn btn-dark btn-premium">Edit Product</a>
                                            @elseif (auth()->user()->isAdmin())
                                                <a href="{{ route('products.edit', $product) }}"
                                                    class="btn btn-dark btn-premium">Audit Product</a>
                                            @else
                                                <a href="{{ route('product.view', $product) }}"
                                                    class="btn btn-outline-dark btn-premium">View Details</a>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-dark btn-premium">Sign In to
                                                Buy</a>
                                        @endauth
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <div class="d-flex justify-content-center mt-3">
        {{ $products->links() }}
    </div>
@endsection

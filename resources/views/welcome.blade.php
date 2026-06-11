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
                            class="text-decoration-none text-dark category-item" data-catalog-link>

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
            <div class="position-relative">
                <div id="catalog-loading" class="catalog-loading d-none" aria-live="polite">
                    <div class="spinner-border text-dark" role="status">
                        <span class="visually-hidden">Loading products...</span>
                    </div>
                </div>

                <div id="catalog-results">
                    @include('products.partials.public_grid', [
                        'products' => $products,
                        'wishlistProductIds' => $wishlistProductIds,
                    ])
                </div>
            </div>
        </div>
    </section>
@endsection

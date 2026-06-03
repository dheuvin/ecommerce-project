@extends('layouts.user')

@section('content')
<style>
    /* Premium UI Custom Refinements */
    .hero-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    .hover-lift {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.08) !important;
    }
    .product-card .img-container {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
    }
    .product-card .card-img-top {
        transition: transform 0.5s ease;
    }
    .product-card:hover .card-img-top {
        transform: scale(1.04);
    }
    .badge-category {
        letter-spacing: 1px;
        font-size: 0.65rem;
    }
    .btn-premium {
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .feature-icon-box {
        width: 60px;
        height: 60px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: #f8f9fa;
        margin-bottom: 1.5rem;
        color: #212529;
        font-size: 1.5rem;
    }
</style>

<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden py-5 mb-5">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 col-xl-5">
                <span class="badge bg-dark text-uppercase px-3 py-2 mb-3 rounded-pill badge-category fw-semibold tracking-wider">
                    Multi-Seller Network
                </span>
                <h1 class="display-3 fw-black text-dark tracking-tight mb-4" style="line-height: 1.1; font-weight: 800;">
                    Curated Products. <br><span class="text-muted">Trusted Sellers.</span>
                </h1>
                <p class="lead text-secondary mb-5" style="font-size: 1.15rem;">
                    Experience a seamless multi-vendor marketplace built for speed, security, and exceptional craftsmanship.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#products" class="btn btn-dark btn-lg btn-premium shadow-sm px-4">
                        Explore Collection <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-outline-dark btn-lg btn-premium px-4">
                            Open an Account
                        </a>
                    @endguest
                </div>
            </div>
            <div class="col-lg-6 col-xl-7 ms-auto">
                <div class="position-relative">
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark rounded-4 transform rotate-1 opacity-10" style="z-index: 0; transform: rotate(2deg);"></div>
                    <img
                        src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=1200"
                        class="img-fluid rounded-4 shadow-lg w-100 position-relative"
                        style="height:520px; object-fit:cover; z-index: 1;"
                        alt="Premium Showcase Image"
                    >
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features / Trust Badges Section -->
<section class="py-5 mb-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-light border-0 h-100 transition-all">
                    <div class="feature-icon-box shadow-sm">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Priority Delivery</h5>
                    <p class="text-secondary small mb-0">Expedited and fully traceable shipping infrastructure worldwide.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-light border-0 h-100 transition-all">
                    <div class="feature-icon-box shadow-sm">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Escrow-Level Security</h5>
                    <p class="text-secondary small mb-0">Encrypted merchant checkout processing securing your funds and data.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-light border-0 h-100 transition-all">
                    <div class="feature-icon-box shadow-sm">
                        <i class="bi bi-patch-check"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Verified Merchants Only</h5>
                    <p class="text-secondary small mb-0">Every boutique storefront undergoes strict quality control auditing.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Products Collection Section -->
<section id="products" class="py-4">
    <div class="container">
        <div class="d-flex align-items-end justify-content-between mb-5">
            <div>
                <h2 class="fw-bold tracking-tight m-0" style="font-weight: 700;">The New Arrivals</h2>
                <p class="text-muted m-0 mt-1">Discover fresh drops from boutique sellers across the platform</p>
            </div>
            <div class="d-none d-md-block">
                <span class="text-muted small fw-medium">Showing {{ $products->count() }} results</span>
            </div>
        </div>

        @if ($products->isEmpty())
            <div class="text-center py-5 rounded-4 bg-light border">
                <i class="bi bi-box-seam display-4 text-muted mb-3 d-block"></i>
                <h5 class="fw-bold text-secondary">No additions found</h5>
                <p class="text-muted small">Check back soon for new vendor collections.</p>
            </div>
        @else
            <div class="row g-4">
                @foreach ($products as $product)
                    @php($isWishlisted = in_array($product->id, $wishlistProductIds ?? [], true))
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card border-0 bg-transparent h-100 product-card hover-lift">

                            <!-- Product Image Wrapper -->
                            <div class="img-container shadow-sm bg-light">
                                <a href="{{ route('product.view', $product) }}" class="d-block text-decoration-none">
                                    @if ($product->primary_image_path)
                                        <img
                                            src="{{ asset('storage/' . $product->primary_image_path) }}"
                                            class="card-img-top"
                                            style="height:340px; object-fit:cover;"
                                            alt="{{ $product->name }}"
                                        >
                                    @else
                                        <div class="d-flex flex-column align-items-center justify-content-center text-muted" style="height:340px;">
                                            <i class="bi bi-image text-muted opacity-50 mb-2" style="font-size: 2rem;"></i>
                                            <span class="small text-uppercase tracking-wider fw-semibold" style="font-size: 0.75rem;">No Image</span>
                                        </div>
                                    @endif
                                </a>
                            </div>

                            <!-- Product Details Body -->
                            <div class="card-body px-1 pt-3 pb-0 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-uppercase text-muted fw-bold badge-category">
                                        {{ $product->category->name ?? 'General Collection' }}
                                    </span>
                                    <span class="text-muted" style="font-size: 0.8rem;">
                                        <i class="bi bi-shop me-1"></i> {{ $product->user->name ?? 'Merchant' }}
                                    </span>
                                </div>

                                <h6 class="fw-bold text-dark mb-2 text-truncate">
                                    <a href="{{ route('product.view', $product) }}" class="text-reset text-decoration-none">
                                        {{ $product->name }}
                                    </a>
                                </h6>

                                <div class="d-flex align-items-center justify-content-between mt-auto pt-2">
                                    <span class="fw-bold text-dark fs-5">
                                        Rs. {{ number_format($product->price, 2) }}
                                    </span>
                                </div>

                                <!-- Dynamic Action Drawer -->
                                <div class="pt-3">
                                    @auth
                                        @if (auth()->user()->isCustomer())
                                            <div class="d-grid gap-2">
                                                <form
                                                    method="POST"
                                                    action="{{ route('wishlist.toggle', $product) }}"
                                                    data-ajax-wishlist="true"
                                                    data-product-id="{{ $product->id }}"
                                                >
                                                    @csrf
                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-premium w-100 {{ $isWishlisted ? 'btn-dark' : 'btn-outline-dark' }}"
                                                        data-active-text="Saved"
                                                        data-inactive-text="Wishlist"
                                                    >
                                                        {{ $isWishlisted ? 'Saved' : 'Wishlist' }}
                                                    </button>
                                                </form>

                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('product.view', $product) }}" class="btn btn-outline-dark btn-sm btn-premium w-50">
                                                        Details
                                                    </a>

                                                    <form method="POST" action="{{ route('cart.add', $product) }}" class="w-50" data-ajax-cart="true">
                                                        @csrf
                                                        <input type="hidden" name="quantity" value="1">
                                                        <button class="btn btn-dark btn-sm btn-premium w-100 d-flex align-items-center justify-content-center gap-2" @disabled($product->stock < 1)>
                                                            <i class="bi bi-bag"></i> Add
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @elseif (auth()->user()->isSeller() && auth()->id() === $product->user_id)
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('product.view', $product) }}" class="btn btn-outline-dark btn-sm btn-premium w-50">
                                                    Details
                                                </a>
                                                <a href="{{ route('products.edit', $product) }}" class="btn btn-dark btn-sm btn-premium w-50">
                                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                                </a>
                                            </div>
                                        @elseif (auth()->user()->isAdmin())
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('product.view', $product) }}" class="btn btn-outline-dark btn-sm btn-premium w-50">
                                                    View
                                                </a>
                                                <a href="{{ route('products.edit', $product) }}" class="btn btn-danger btn-sm btn-premium w-50">
                                                    <i class="bi bi-shield-lock me-1"></i> Audit
                                                </a>
                                            </div>
                                        @else
                                            <a href="{{ route('product.view', $product) }}" class="btn btn-outline-dark btn-sm btn-premium w-100">
                                                View Product
                                            </a>
                                        @endif
                                    @else
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('product.view', $product) }}" class="btn btn-outline-dark btn-sm btn-premium w-50">
                                                Details
                                            </a>
                                            <a href="{{ route('login') }}" class="btn btn-dark btn-sm btn-premium w-50 text-center">
                                                Sign In
                                            </a>
                                        </div>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection

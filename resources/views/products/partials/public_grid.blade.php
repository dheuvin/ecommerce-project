<div class="d-flex flex-column flex-lg-row justify-content-between gap-4 mb-4">
    <div>
        <div class="eyebrow mb-2">New arrivals</div>
        <h2 class="fw-bold mb-1">Featured Products</h2>
        <p class="text-muted mb-0" data-product-count-text>
            {{ $products->total() }} {{ \Illuminate\Support\Str::plural('product', $products->total()) }} available from
            our marketplace.
        </p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <button class="btn btn-light border btn-premium btn-sm px-3" type="button" data-open-filters>
            <i class="bi bi-sliders me-1"></i> Filters
        </button>
    </div>
</div>

@if ($products->isEmpty())
    <div class="soft-surface text-center p-5">
        <i class="bi bi-search display-5 text-muted"></i>
        <h4 class="fw-bold mt-3">No products found</h4>
        <p class="text-muted mb-4">Try another search or adjust the filters.</p>
        <a href="{{ route('shop.index') }}" class="btn btn-dark btn-premium px-4" data-catalog-link>View All
            Products</a>
    </div>
@else
    <div class="row g-4">
        @foreach ($products as $product)
            @php
                $isWishlisted = in_array($product->id, $wishlistProductIds ?? [], true);
                $totalStock = $product->variants->sum('stock');
            @endphp
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
                                    class="floating-action" data-ajax-wishlist="true" data-product-id="{{ $product->id }}">
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
                            <span class="small text-muted fw-semibold text-truncate">
                                {{ $product->category->name ?? 'Collection' }}
                            </span>
                            <span class="rating-stars" aria-label="Rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star"></i>
                            </span>
                        </div>
                        <h6 class="fw-bold mb-2">
                            <a href="{{ route('product.view', $product) }}" class="text-dark text-decoration-none">
                                {{ $product->name }}
                            </a>
                        </h6>
                        <small class="text-muted mb-3">
                            <i class="bi bi-shop me-1"></i>{{ $product->user->name ?? 'Merchant' }}
                        </small>
                        <div class="d-flex align-items-center justify-content-between mt-auto gap-2">
                            <span class="price">Rs. {{ number_format($product->price, 2) }}</span>
                            <span
                                class="badge {{ $totalStock > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                {{ $totalStock > 0 ? 'In Stock (' . $totalStock . ')' : 'Sold Out' }}
                            </span>
                        </div>

                        <div class="d-grid gap-2 mt-3">
                            @auth
                                @if (auth()->user()->isCustomer())
                                    <form method="POST" action="{{ route('cart.add', $product) }}" data-ajax-cart="true">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button class="btn btn-dark btn-premium w-100" @disabled($totalStock < 1)>
                                            <i class="bi bi-bag-plus me-1"></i> Add to Cart
                                        </button>
                                    </form>
                                @elseif (auth()->user()->isSeller() && auth()->id() === $product->user_id)
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-dark btn-premium">Edit
                                        Product</a>
                                @elseif (auth()->user()->isAdmin())
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-dark btn-premium">Audit
                                        Product</a>
                                @else
                                    <a href="{{ route('product.view', $product) }}"
                                        class="btn btn-outline-dark btn-premium">View Details</a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-dark btn-premium">Sign In to Buy</a>
                            @endauth
                        </div>
                    </div>
                </article>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4" data-catalog-pagination>
        {{ $products->links() }}
    </div>
@endif

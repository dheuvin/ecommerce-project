@extends('layouts.user')

@section('title', $product->name)

@section('content')
    @php
        $isWishlisted = in_array($product->id, $wishlistProductIds ?? [], true);
        $averageRating = $product->reviews->count() ? round($product->reviews->avg('rating'), 1) : null;
        $totalStock = $product->variants->sum('stock');
    @endphp

    <section class="section-pad">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6">
                    <div class="position-sticky" style="top: 104px;">
                        <div class="surface overflow-hidden">
                            @if ($product->primary_image_path)
                                <img src="{{ asset('storage/' . $product->primary_image_path) }}" class="w-100"
                                    style="aspect-ratio:1 / 1; object-fit:contain; background:#f7f8fb;"
                                    alt="{{ $product->name }}">
                            @else
                                <div class="d-flex flex-column align-items-center justify-content-center text-muted"
                                    style="aspect-ratio:1 / 1; background:#f7f8fb;">
                                    <i class="bi bi-image fs-1 mb-2"></i>
                                    <span class="fw-semibold">No Image</span>
                                </div>
                            @endif
                        </div>

                        @if ($product->images->count())
                            <div class="d-flex gap-2 mt-3 flex-wrap">
                                @foreach ($product->images as $image)
                                    <img src="{{ asset('storage/' . $image->image) }}" width="84" height="84"
                                        class="rounded-4 border bg-light" style="object-fit:cover;"
                                        alt="{{ $product->name }} gallery image">
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="eyebrow mb-2">{{ $product->category->name ?? 'Product' }}</div>
                    <h1 class="display-6 fw-bold mb-3">{{ $product->name }}</h1>

                    <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                        <span class="rating-stars">
                            @for ($i = 1; $i <= 5; $i++)
                                <i
                                    class="bi {{ $averageRating && $i <= round($averageRating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                        </span>
                        <span class="text-muted small">{{ $product->reviews->count() }} reviews</span>
                        <span class="text-muted small">SKU: {{ $product->sku }}</span>
                        <span class="text-muted small">Seller: {{ $product->user->name ?? 'N/A' }}</span>
                    </div>

                    <div class="soft-surface p-4 mb-4">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <div class="text-muted small fw-semibold mb-1">Price</div>
                                <div class="display-6 price">Rs. {{ number_format($product->price, 2) }}</div>
                            </div>
                            <span
                                class="badge rounded-pill px-3 py-2 {{ $product->stock > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                <span
                                    class="badge rounded-pill px-3 py-2 {{ $totalStock > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                    {{ $totalStock > 0 ? 'In Stock (' . $totalStock . ')' : 'Out of Stock' }}
                                </span>
                            </span>
                        </div>
                    </div>
                    {{-- @if ($product->variants->count())
                        <div class="mb-3">
                            <h6 class="fw-bold mb-2">Select Size</h6>

                            <div class="d-flex gap-2 flex-wrap">
                                @foreach ($product->variants as $variant)
                                    @php
                                        $size = $variant->size ?? $variant->name; // fallback
                                        $isOut = $variant->stock <= 0;
                                    @endphp

                                    <label class="border rounded px-3 py-2 {{ $isOut ? 'opacity-50' : '' }}">
                                        <input type="radio" name="variant_id" value="{{ $variant->id }}"
                                            @if ($isOut) disabled @endif>

                                        <span>{{ $size }}</span>

                                        <small class="d-block text-muted">
                                            {{ $isOut ? 'Out of stock' : 'In stock' }}
                                        </small>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif --}}

                    <div class="row g-3 mb-4">
                        <div class="col-sm-4">
                            <div class="soft-surface p-3 h-100">
                                <i class="bi bi-shield-check"></i>
                                <div class="fw-bold mt-2">Secure</div>
                                <small class="text-muted">Safe checkout</small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="soft-surface p-3 h-100">
                                <i class="bi bi-truck"></i>
                                <div class="fw-bold mt-2">Delivery</div>
                                <small class="text-muted">Trackable orders</small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="soft-surface p-3 h-100">
                                <i class="bi bi-arrow-repeat"></i>
                                <div class="fw-bold mt-2">Support</div>
                                <small class="text-muted">Easy assistance</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-3 align-items-end mb-5">
    @auth
        @if (auth()->user()->isCustomer())
            <form action="{{ route('wishlist.toggle', $product) }}" method="POST"
                data-ajax-wishlist="true" data-product-id="{{ $product->id }}" class="m-0">
                @csrf
                <button class="btn {{ $isWishlisted ? 'btn-dark' : 'btn-outline-dark' }} btn-premium px-4 py-2 d-inline-flex align-items-center transition-all">
                    <i class="bi {{ $isWishlisted ? 'bi-heart-fill text-danger' : 'bi-heart' }} me-2"></i>
                    <span>{{ $isWishlisted ? 'Saved' : 'Wishlist' }}</span>
                </button>
            </form>

            @if ($totalStock > 0)
                <form action="{{ route('cart.add', $product) }}" method="POST" data-ajax-cart="true" class="w-100 mt-2 m-0">
                    @csrf
                    <input type="hidden" name="quantity" value="1">

                    @if ($product->variants->count())
                        <div class="mb-4 w-100">
                            <h6 class="text-uppercase tracking-wider fw-bold text-muted small mb-3">Select Size</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($product->variants as $variant)
                                    @php
                                        $isOut = $variant->stock <= 0;
                                    @endphp

                                    <label class="position-relative border rounded p-3 text-center cursor-pointer transition-all size-box d-flex flex-column justify-content-center align-items-center {{ $isOut ? 'opacity-50 bg-light' : 'hover-shadow-sm' }}" style="min-width: 75px; min-height: 65px;">
                                        <input type="radio" name="variant_id" value="{{ $variant->id }}"
                                            class="d-none size-radio" {{ $isOut ? 'disabled' : '' }} required>

                                        <span class="fw-bold d-block text-dark">{{ $variant->size }}</span>

                                        @if ($isOut)
                                            <small class="text-danger fw-semibold mt-1" style="font-size: 0.7rem;">Out</small>
                                        @else
                                            <small class="text-muted mt-1" style="font-size: 0.7rem;">{{ $variant->stock }} left</small>
                                        @endif
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <button class="btn btn-dark btn-premium px-5 py-2 d-inline-flex align-items-center shadow-sm">
                        <i class="bi bi-cart-plus me-2 fs-5"></i>
                        <span>Add to Cart</span>
                    </button>
                </form>
            @endif
        @endif
    @else
        <a href="{{ route('login') }}" class="btn btn-dark btn-premium px-4 py-2 d-inline-flex align-items-center shadow-sm">
            <i class="bi bi-box-arrow-in-right me-2"></i>
            Sign In to Buy
        </a>
    @endauth

    @auth
        @if (auth()->user()->isSeller() && auth()->id() === $product->user_id)
            <a href="{{ route('products.edit', $product) }}"
                class="btn btn-outline-primary btn-premium px-4 py-2 d-inline-flex align-items-center">
                <i class="bi bi-pencil-square me-2"></i>
                Edit Product
            </a>
        @endif
    @endauth

    <a href="{{ url()->previous() }}" class="btn btn-light border bg-white text-muted btn-premium px-4 py-2 d-inline-flex align-items-center hover-bg-light">
        <i class="bi bi-arrow-left me-2"></i>
        Back
    </a>
</div>

                    <div class="mb-5">
                        <h5 class="fw-bold">About this product</h5>
                        <p class="text-muted lh-lg mb-0">{{ $product->description ?: 'No description added.' }}</p>
                    </div>

                    <div class="surface p-4">
                        <button class="btn btn-link text-dark fw-bold fs-5 p-0 text-decoration-none" type="button"
                            data-bs-toggle="collapse" data-bs-target="#reviewBox" aria-expanded="true">
                            Customer Reviews <i class="bi bi-chevron-down ms-1"></i>
                        </button>

                        <a href="{{ route('reviews.index') }}" class="btn btn-outline-dark btn-sm">
                            View All
                        </a>

                        <div class="collapse show mt-4" id="reviewBox">
                            @auth
                                <div class="soft-surface p-4 mb-4">
                                    <h6 class="fw-bold mb-3">Write your review</h6>
                                    <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                                        @csrf
                                        <label class="form-label">Rating</label>
                                        <div id="star-rating" class="fs-3 rating-stars mb-2">
                                            <i class="bi bi-star" data-value="1"></i>
                                            <i class="bi bi-star" data-value="2"></i>
                                            <i class="bi bi-star" data-value="3"></i>
                                            <i class="bi bi-star" data-value="4"></i>
                                            <i class="bi bi-star" data-value="5"></i>
                                        </div>
                                        <input type="hidden" name="rating" id="rating-value" required>
                                        <textarea name="comment" class="form-control mb-3" rows="3" placeholder="Share what stood out..."></textarea>
                                        <button class="btn btn-dark btn-premium px-4">Submit Review</button>
                                    </form>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-dark btn-premium btn-sm mb-3">Login to write
                                    review</a>
                            @endauth

                            @forelse($product->reviews as $review)
                                <div class="py-3 @if (!$loop->last) border-bottom @endif">
                                    <div class="d-flex justify-content-between gap-3">
                                        <strong>{{ $review->user->name }}</strong>
                                        <small class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                                    </div>
                                    <div class="rating-stars my-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                    </div>
                                    <p class="mb-0 text-muted">{{ $review->comment }}</p>
                                </div>
                            @empty

                                <div class="soft-surface text-center p-4">
                                    <i class="bi bi-chat-square-heart fs-2 text-muted"></i>
                                    <p class="text-muted mt-2 mb-0">No reviews yet. Be the first to share feedback.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5 pt-4">
                <div class="d-flex justify-content-between align-items-end gap-3 mb-4">
                    <div>
                        <div class="eyebrow mb-2">Recommended</div>
                        <h3 class="fw-bold mb-0">More from this category</h3>
                    </div>
                    <a href="{{ route('shop.index') }}" class="btn btn-outline-dark btn-premium btn-sm px-3">View All</a>
                </div>

                <div class="row g-4">
                    @forelse($relatedProducts as $item)
                        <div class="col-6 col-lg-3">
                            <a href="{{ route('product.view', $item) }}" class="text-decoration-none text-dark">
                                <article class="card product-card h-100">
                                    <div class="product-media">
                                        @if ($item->primary_image_path)
                                            <img src="{{ asset('storage/' . $item->primary_image_path) }}"
                                                alt="{{ $item->name }}">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                                No Image</div>
                                        @endif
                                    </div>
                                    <div class="card-body p-3">
                                        <h6 class="fw-bold text-truncate mb-1">{{ $item->name }}</h6>
                                        <div class="price">Rs. {{ number_format($item->price, 2) }}</div>
                                    </div>
                                </article>
                            </a>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="soft-surface p-4 text-muted">No related products found.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('#star-rating i');
            const input = document.getElementById('rating-value');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    input.value = value;

                    stars.forEach(item => {
                        if (item.getAttribute('data-value') <= value) {
                            item.classList.remove('bi-star');
                            item.classList.add('bi-star-fill');
                        } else {
                            item.classList.remove('bi-star-fill');
                            item.classList.add('bi-star');
                        }
                    });
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[data-ajax-cart]');
    const button = form.querySelector('button');
    const radios = form.querySelectorAll('input[name="variant_id"]');

    function check() {
        const selected = [...radios].some(r => r.checked);
        button.disabled = !selected;
        button.style.opacity = selected ? '1' : '0.5';
    }

    radios.forEach(r => r.addEventListener('change', check));
    check();
});
    </script>
@endsection

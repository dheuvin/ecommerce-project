@extends('layouts.user')

@section('content')

    @php($isWishlisted = in_array($product->id, $wishlistProductIds ?? [], true))

    <div class="container mt-4">

        <div class="row g-5">

            <!-- LEFT IMAGE SECTION -->
            <div class="col-md-5">

                <div class="position-sticky" style="top: 20px;">

                    <div class="border rounded p-3 bg-white">

                        @if ($product->primary_image_path)
                            <img src="{{ asset('storage/' . $product->primary_image_path) }}" class="img-fluid"
                                style="height:450px; object-fit:contain;">
                        @else
                            <div class="d-flex align-items-center justify-content-center" style="height:450px;">
                                No Image
                            </div>
                        @endif

                    </div>

                    @if ($product->images->count())
                        <div class="d-flex gap-2 mt-3 flex-wrap">

                            @foreach ($product->images as $image)
                                <img src="{{ asset('storage/' . $image->image) }}" width="70" height="70"
                                    class="border rounded" style="object-fit:cover;">
                            @endforeach

                        </div>
                    @endif

                </div>
            </div>

            <!-- RIGHT PRODUCT SECTION -->
            <div class="col-md-7">

                <h2 class="fw-bold mb-2">{{ $product->name }}</h2>

                <p class="text-muted mb-1">
                    Seller: <strong>{{ $product->user->name ?? 'N/A' }}</strong>
                </p>

                <p class="text-muted mb-1">
                    SKU: <strong>{{ $product->sku }}</strong>
                </p>

                <p class="text-muted mb-3">
                    Category: <strong>{{ $product->category->name ?? 'N/A' }}</strong>
                </p>

                <!-- PRICE BOX -->
                <div class="bg-light p-3 rounded mb-3">
                    <h3 class="text-success fw-bold mb-0">
                        ₹ {{ number_format($product->price, 2) }}
                    </h3>

                    @if ($product->stock > 0)
                        <span class="text-success fw-semibold">
                            In Stock ({{ $product->stock }})
                        </span>
                    @else
                        <span class="text-danger fw-semibold">
                            Out of Stock
                        </span>
                    @endif
                </div>

                <!-- ACTION BUTTONS -->
                <div class="d-flex gap-2 flex-wrap mb-4">

                    @auth
                        @if (auth()->user()->isCustomer())
                            <form action="{{ route('wishlist.toggle', $product) }}" method="POST">
                                @csrf
                                <button class="btn {{ $isWishlisted ? 'btn-dark' : 'btn-outline-dark' }}">
                                    {{ $isWishlisted ? 'Saved' : 'Wishlist' }}
                                </button>
                            </form>

                            @if ($product->stock > 0)
                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button class="btn btn-warning fw-bold">
                                        Add to Cart
                                    </button>
                                </form>
                            @endif
                        @endif
                    @endauth

                    @auth
                        @if (auth()->user()->isSeller() && auth()->id() === $product->user_id)
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">
                                Edit
                            </a>
                        @endif
                    @endauth

                    <a href="{{ url()->previous() }}" class="btn btn-secondary">
                        Back
                    </a>

                </div>

                <!-- DESCRIPTION -->
                <div class="mb-4">
                    <h5 class="fw-bold">About this product</h5>
                    <p class="text-muted">
                        {{ $product->description ?: 'No description added.' }}
                    </p>
                </div>

                <!-- REVIEW SECTION -->
                <!-- REVIEW SECTION (AMAZON STYLE COLLAPSIBLE) -->
                <div class="border-top pt-4 mt-4">

                    <!-- CLICKABLE TITLE -->
                    <button class="btn btn-link text-dark fw-bold fs-5 p-0 text-decoration-none" type="button"
                        data-bs-toggle="collapse" data-bs-target="#reviewBox" aria-expanded="false">

                        Customer Reviews ▼

                    </button>

                    <!-- COLLAPSIBLE AREA -->
                    <div class="collapse mt-3" id="reviewBox">

                        <!-- REVIEW FORM -->
                        @auth

                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body">

                                    <h6 class="fw-bold mb-2">Write your review</h6>

                                    <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                                        @csrf

                                        <!-- STAR RATING -->
                                        <label class="form-label">Rating</label>

                                        <div id="star-rating" class="fs-3 text-warning mb-2">
                                            <i class="bi bi-star" data-value="1"></i>
                                            <i class="bi bi-star" data-value="2"></i>
                                            <i class="bi bi-star" data-value="3"></i>
                                            <i class="bi bi-star" data-value="4"></i>
                                            <i class="bi bi-star" data-value="5"></i>
                                        </div>

                                        <input type="hidden" name="rating" id="rating-value" required>

                                        <!-- COMMENT -->
                                        <textarea name="comment" class="form-control mb-2" rows="3" placeholder="Write your review..."></textarea>

                                        <button class="btn btn-dark btn-sm">
                                            Submit Review
                                        </button>

                                    </form>

                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-warning btn-sm mb-3">
                                Login to write review
                            </a>
                        @endauth

                        <!-- REVIEW LIST -->
                        @forelse($product->reviews as $review)
                            <div class="border-bottom py-3">

                                <div class="d-flex justify-content-between">

                                    <strong>{{ $review->user->name }}</strong>

                                    <small class="text-muted">
                                        {{ $review->created_at->format('d M Y') }}
                                    </small>

                                </div>

                                <div class="text-warning">
                                    {{ str_repeat('⭐', $review->rating) }}
                                </div>

                                <p class="mb-0 text-muted">
                                    {{ $review->comment }}
                                </p>

                            </div>

                        @empty

                            <p class="text-muted mt-2">No reviews yet.</p>
                        @endforelse
                        <!-- RELATED PRODUCTS SECTION -->
                        <div class="mt-5">

                            <h4 class="fw-bold mb-3">
                                More from this category
                            </h4>

                            <div class="row g-3">

                                @forelse($relatedProducts as $item)
                                    <div class="col-6 col-md-3">

                                        <a href="{{ route('products.show', $item->id) }}"
                                            class="text-decoration-none text-dark">

                                            <div class="card border-0 shadow-sm h-100">

                                                <img src="{{ asset('storage/' . $item->primary_image_path) }}"
                                                    class="card-img-top" style="height:180px; object-fit:cover;">

                                                <div class="card-body p-2">

                                                    <h6 class="mb-1 text-truncate">
                                                        {{ $item->name }}
                                                    </h6>

                                                    <div class="text-success fw-bold">
                                                        ₹ {{ number_format($item->price, 2) }}
                                                    </div>

                                                </div>

                                            </div>

                                        </a>

                                    </div>

                                @empty

                                    <p class="text-muted">No related products found.</p>
                                @endforelse

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const stars = document.querySelectorAll('#star-rating i');
            const input = document.getElementById('rating-value');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    let value = this.getAttribute('data-value');
                    input.value = value;

                    stars.forEach(s => {
                        if (s.getAttribute('data-value') <= value) {
                            s.classList.remove('bi-star');
                            s.classList.add('bi-star-fill');
                        } else {
                            s.classList.remove('bi-star-fill');
                            s.classList.add('bi-star');
                        }
                    });
                });
            });

        });
    </script>

@endsection

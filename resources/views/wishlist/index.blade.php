@extends('layouts.user')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Your Wishlist</h2>
        <a href="{{ route('shop.index') }}" class="btn btn-outline-dark">Continue Shopping</a>
    </div>

    @if ($wishlists->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <h4 class="mb-2">Wishlist is empty</h4>
                <p class="text-muted">Save products here to revisit them later.</p>
            </div>
        </div>
    @else
        <div class="row g-4" id="wishlist-items">
            @foreach ($wishlists as $wishlist)
                @php($product = $wishlist->product)
                @if ($product)
                    <div class="col-md-6 col-lg-4" data-wishlist-card="{{ $product->id }}">
                        <div class="card h-100 border-0 shadow-sm">
                            @if ($product->primary_image_path)
                                <img src="{{ asset('storage/' . $product->primary_image_path) }}" class="card-img-top" style="height:260px; object-fit:cover;">
                            @endif

                            <div class="card-body d-flex flex-column">
                                <div class="text-muted small mb-1">{{ $product->category->name ?? 'Category' }}</div>
                                <h5 class="fw-bold">{{ $product->name }}</h5>
                                <div class="text-muted small mb-3">Seller: {{ $product->user->name ?? 'N/A' }}</div>
                                <div class="fw-bold fs-5 mb-3">Rs. {{ number_format($product->price, 2) }}</div>

                                <div class="mt-auto d-flex gap-2">
                                    <a href="{{ route('product.view', $product) }}" class="btn btn-outline-dark w-50">View</a>

                                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="w-50"
                                        data-ajax-wishlist="true" data-product-id="{{ $product->id }}">
                                        @csrf
                                        <button class="btn btn-dark w-100">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>
@endsection

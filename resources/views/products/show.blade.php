@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row g-4">
        <div class="col-md-5">
            <div class="card shadow-sm">
                @if ($product->primary_image_path)
                    <img
                        src="{{ asset('storage/' . $product->primary_image_path) }}"
                        class="img-fluid"
                        style="height:350px; object-fit:cover; border-radius:8px;"
                    >
                @else
                    <div class="d-flex align-items-center justify-content-center bg-light" style="height:350px;">
                        No Image
                    </div>
                @endif
            </div>

            @if ($product->images->isNotEmpty())
                <div class="d-flex gap-2 mt-3 flex-wrap">
                    @foreach ($product->images as $image)
                        <img
                            src="{{ asset('storage/' . $image->image) }}"
                            width="80"
                            height="80"
                            style="object-fit:cover; border-radius:6px; border:1px solid #ddd;"
                        >
                    @endforeach
                </div>
            @endif
        </div>

        <div class="col-md-7">
            <h2 class="fw-bold">{{ $product->name }}</h2>

            <p class="text-muted mb-1">SKU: <strong>{{ $product->sku }}</strong></p>
            <p class="mb-1">Category: <strong>{{ $product->category->name ?? 'N/A' }}</strong></p>
            <p class="mb-1">Seller: <strong>{{ $product->user->name ?? 'N/A' }}</strong></p>

            <h3 class="text-success fw-bold mt-3">Rs. {{ number_format($product->price, 2) }}</h3>

            <p>
                @if ($product->stock > 0)
                    <span class="badge bg-success">In Stock ({{ $product->stock }})</span>
                @else
                    <span class="badge bg-danger">Out of Stock</span>
                @endif
            </p>

            <p>
                @if ($product->status)
                    <span class="badge bg-primary">Active</span>
                @else
                    <span class="badge bg-secondary">Inactive</span>
                @endif
            </p>

            <hr>

            <p class="text-muted">
                {{ $product->description ?: 'No description added.' }}
            </p>


            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('products.edit', $product) }}" class="btn btn-primary px-4">
                    Edit Product
                </a>

                <form action="{{ route('products.destroy', $product) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger px-4" onclick="return confirm('Delete this product?')">
                        Delete Product
                    </button>
                </form>

                <a href="{{ route('products.index') }}" class="btn btn-secondary px-4">
                    Back
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

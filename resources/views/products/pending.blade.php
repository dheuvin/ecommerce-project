@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h3 class="mb-4">Pending Products Review</h3>

    @forelse($products as $product)

<div class="card mb-3 p-3 shadow-sm">

    <h5>{{ $product->name }}</h5>

    <p><strong>Seller:</strong> {{ $product->user->name ?? '-' }}</p>
    <p><strong>Price:</strong> ₹{{ number_format($product->price, 2) }}</p>
    <p><strong>Category:</strong> {{ $product->category->name ?? '-' }}</p>

    <div class="d-flex gap-2">

        <form method="POST" action="{{ route('products.approve', $product->id) }}">
            @csrf
            <button type="submit" class="btn btn-success btn-sm">
                Approve
            </button>
        </form>

        <form method="POST" action="{{ route('products.reject', $product->id) }}" class="d-flex gap-2">
            @csrf

            <input type="text"
                   name="note"
                   class="form-control form-control-sm"
                   placeholder="Reject reason"
                   required>

            <button type="submit" class="btn btn-danger btn-sm">
                Reject
            </button>
        </form>

        <a href="{{ route('product.view', $product->id) }}"
           class="btn btn-primary btn-sm">
            View
        </a>

    </div>

</div>

@empty
    <p class="text-muted">No pending products found.</p>
@endforelse

</div>

@endsection

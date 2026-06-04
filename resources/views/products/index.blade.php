@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h3>Products</h3>
        <a href="{{ route('products.create') }}" class="btn btn-primary">+ Add Product</a>
    </div>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>SKU</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        @if ($product->primary_image_path)
                            <img src="{{ asset('storage/' . $product->primary_image_path) }}" width="60" class="rounded">
                        @else
                            <span>No Image</span>
                        @endif
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td>Rs. {{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        @if ($product->status == 'draft')
                            <span class="badge bg-secondary">Draft</span>
                        @elseif($product->status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($product->status == 'active')
                            <span class="badge bg-success">Approved</span>
                        @elseif($product->status == 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>
                    <td class="d-flex gap-2 flex-wrap">

                        <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm">
                            View
                        </a>

                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        {{-- ✅ SUBMIT FOR REVIEW --}}
                        @if ($product->status == 'draft' || $product->status == 'rejected')
                            <form action="{{ route('seller.products.submit', $product) }}" method="POST">
                                @csrf
                                <button class="btn btn-secondary btn-sm">
                                    Submit for Review
                                </button>
                            </form>
                        @endif

                        {{-- DELETE --}}
                        <form action="{{ route('products.destroy', $product) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?')">
                                Delete
                            </button>
                        </form>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-4">No products found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

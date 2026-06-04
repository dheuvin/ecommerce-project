@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h2 class="h4 mb-3">Admin Dashboard</h2>
                <p class="text-muted mb-4">
                    Review categories and products across all sellers.
                </p>

                <div class="d-flex gap-2">
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-primary">
                        Categories
                    </a>

                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        Products
                    </a>

                    <a href="{{ route('products.pending') }}" class="btn btn-warning">
                        Pending Products
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

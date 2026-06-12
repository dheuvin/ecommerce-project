@extends('layouts.user')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">Customer Reviews</h2>

    @forelse($reviews as $review)
        <div class="card mb-3 shadow-sm border-0">
            <div class="card-body">

                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">
                            {{ $review->user->name ?? 'Anonymous User' }}
                        </h5>

                        <small class="text-muted">
                            {{ $review->created_at->format('d M Y') }}
                        </small>
                    </div>

                    <div>
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                ⭐
                            @else
                                ☆
                            @endif
                        @endfor
                    </div>
                </div>

                <hr>

                <h6 class="fw-bold">
                    Product:
                    {{ $review->product->name ?? 'N/A' }}
                </h6>

                <p class="mt-2 mb-0">
                    {{ $review->comment }}
                </p>

            </div>
        </div>
    @empty
        <div class="alert alert-info">
            No reviews found.
        </div>
    @endforelse

</div>
@endsection

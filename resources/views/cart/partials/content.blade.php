@if (! isset($cart) || $cart->items->isEmpty())
    <div class="text-center border rounded bg-white p-5 shadow-sm">
        <h2 class="fw-bold">Your cart is empty</h2>
        <p class="text-muted mt-3">
            Start adding products to continue shopping.
        </p>

        <a href="{{ route('shop.index') }}" class="btn btn-dark mt-3">
            Start Shopping
        </a>
    </div>
@else
    <div class="row g-4">
        <div class="col-lg-8">
            <h1 class="mb-4">Shopping Cart</h1>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    @foreach ($cart->items as $item)
                        @php
                            $product = $item->product;
                            $availableStock = $product?->stock ?? 0;
                            $rowTotal = $item->price * $item->quantity;
                        @endphp

                        <div class="d-flex flex-column flex-md-row gap-3 py-3 @if (! $loop->last) border-bottom @endif">
                            <div style="width: 110px;">
                                @if ($product?->primary_image_path)
                                    <img
                                        src="{{ asset('storage/' . $product->primary_image_path) }}"
                                        class="img-fluid rounded border"
                                        style="height:110px; width:110px; object-fit:cover;"
                                    >
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light rounded border text-muted" style="height:110px; width:110px;">
                                        No Image
                                    </div>
                                @endif
                            </div>

                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <h5 class="mb-1">{{ $product?->name ?? 'Product removed' }}</h5>
                                        <p class="text-muted mb-1">Rs. {{ number_format($item->price, 2) }} each</p>
                                    </div>

                                    <strong>Rs. {{ number_format($rowTotal, 2) }}</strong>
                                </div>

                                <div class="d-flex align-items-center gap-2 mt-3 flex-wrap">
                                    <form method="POST" action="{{ route('cart.update', $item) }}" data-ajax-cart="true">
                                        @csrf
                                        <input type="hidden" name="quantity" value="{{ max($item->quantity - 1, 1) }}">
                                        <button type="submit" class="btn btn-outline-secondary btn-sm" @disabled($item->quantity <= 1)>
                                            -
                                        </button>
                                    </form>

                                    <span class="px-2 fw-semibold">{{ $item->quantity }}</span>

                                    <form method="POST" action="{{ route('cart.update', $item) }}" data-ajax-cart="true">
                                        @csrf
                                        <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                        <button
                                            type="submit"
                                            class="btn btn-outline-secondary btn-sm"
                                            @disabled(! $product || $item->quantity >= $availableStock)
                                        >
                                            +
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('cart.remove', $item) }}" data-ajax-cart="true">
                                        @csrf
                                        <button class="btn btn-link btn-sm text-danger p-0">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4>Order Summary</h4>

                    <div class="d-flex justify-content-between mt-4">
                        <span>Items</span>
                        <strong>{{ $summary['items_count'] }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <span>Subtotal</span>
                        <strong>Rs. {{ number_format($summary['subtotal'], 2) }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <span>Shipping</span>
                        <span class="text-muted">Calculated at checkout</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between fs-5">
                        <strong>Total</strong>
                        <strong>Rs. {{ number_format($summary['total'], 2) }}</strong>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn btn-dark w-100 mt-4">
                        Proceed to Checkout
                    </a>

                    <form method="POST" action="{{ route('cart.clear') }}" class="mt-3" data-ajax-cart="true">
                        @csrf
                        <button class="btn btn-outline-danger w-100">
                            Clear Cart
                        </button>
                    </form>

                    <a href="{{ route('shop.index') }}" class="btn btn-link w-100 mt-2">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

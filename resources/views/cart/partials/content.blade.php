@if (!isset($cart) || $cart->items->isEmpty())
    <div class="soft-surface text-center p-5">
        <i class="bi bi-bag-heart display-4 text-muted"></i>
        <h2 class="fw-bold mt-3">Your cart is empty</h2>
        <p class="text-muted mt-2 mb-4">Start with our latest premium products and build your order.</p>
        <a href="{{ route('shop.index') }}" class="btn btn-dark btn-premium px-4">
            Start Shopping <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
@else
    <div class="row g-4 align-items-start">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-end gap-3 mb-4">
                <div>
                    <div class="eyebrow mb-2">Cart</div>
                    <h1 class="fw-bold mb-0">Shopping Bag</h1>
                </div>
                <a href="{{ route('shop.index') }}" class="btn btn-outline-dark btn-premium btn-sm px-3">Continue
                    Shopping</a>
            </div>

            <div class="surface p-3 p-md-4">
                @foreach ($cart->items as $item)
                    @php
                        $product = $item->product;
                        $availableStock = $item->variant?->stock ?? 0;
                        $selectedSize = $item->variant?->size ?? 'N/A';
                        $rowTotal = $item->price * $item->quantity;
                    @endphp

                    <div
                        class="d-flex flex-column flex-md-row gap-3 py-3 @if (!$loop->last) border-bottom @endif">
                        <div class="flex-shrink-0" style="width: 112px;">
                            @if ($product?->primary_image_path)
                                <img src="{{ asset('storage/' . $product->primary_image_path) }}"
                                    class="rounded-4 border bg-light"
                                    style="height:112px; width:112px; object-fit:cover;" alt="{{ $product->name }}">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light rounded-4 border text-muted"
                                    style="height:112px; width:112px;">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                        </div>

                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <h5 class="fw-bold mb-1">{{ $product?->name ?? 'Product removed' }}</h5>
                                    <p class="mb-2">
    <strong>Size:</strong> {{ $selectedSize }}
</p>
                                    <p class="text-muted mb-2">Rs. {{ number_format($item->price, 2) }} each</p>
                                    <span class="badge bg-success-subtle text-success rounded-pill">Ready to ship</span>
                                </div>
                                <strong class="price text-nowrap">Rs. {{ number_format($rowTotal, 2) }}</strong>
                            </div>

                            <div class="d-flex align-items-center gap-2 mt-3 flex-wrap">
                                <div class="d-flex align-items-center border rounded-pill overflow-hidden">
                                    <form method="POST" action="{{ route('cart.update', $item) }}"
                                        data-ajax-cart="true">
                                        @csrf
                                        <input type="hidden" name="quantity" value="{{ max($item->quantity - 1, 1) }}">
                                        <button type="submit" class="btn btn-light border-0 rounded-0"
                                            @disabled($item->quantity <= 1) aria-label="Decrease quantity">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                    </form>

                                    <span class="px-3 fw-bold">{{ $item->quantity }}</span>

                                    <form method="POST" action="{{ route('cart.update', $item) }}"
                                        data-ajax-cart="true">
                                        @csrf
                                        <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                        <button type="submit" class="btn btn-light border-0 rounded-0"
                                            @disabled(!$product || $item->quantity >= $availableStock) aria-label="Increase quantity">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </form>
                                </div>

                                <form method="POST" action="{{ route('cart.remove', $item) }}" data-ajax-cart="true">
                                    @csrf
                                    <button class="btn btn-link btn-sm text-danger text-decoration-none">
                                        <i class="bi bi-trash me-1"></i> Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-lg-4">
            <div class="surface p-4 position-sticky" style="top: 104px;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="fw-bold mb-0">Order Summary</h4>
                    <i class="bi bi-shield-lock fs-4 text-success"></i>
                </div>

                <div class="soft-surface p-3 mb-3">
                    <div class="fw-bold">Secure checkout</div>
                    <small class="text-muted">Your order details stay protected.</small>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <span class="text-muted">Items</span>
                    <strong>{{ $summary['items_count'] }}</strong>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <span class="text-muted">Subtotal</span>
                    <strong>Rs. {{ number_format($summary['subtotal'], 2) }}</strong>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <span class="text-muted">Shipping</span>
                    <span class="text-muted">At checkout</span>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <span class="text-muted">Tax</span>
                    <strong>Rs. {{ number_format($summary['tax_total'] ?? 0, 2) }}</strong>
                </div>

                <hr>

                <div class="d-flex justify-content-between fs-5">
                    <strong>Total</strong>
                    <strong>Rs. {{ number_format($summary['total'], 2) }}</strong>
                </div>

                <a href="{{ route('checkout.index') }}" class="btn btn-dark btn-premium w-100 mt-4 py-3">
                    Proceed to Checkout <i class="bi bi-arrow-right ms-1"></i>
                </a>

                <form method="POST" action="{{ route('cart.clear') }}" class="mt-3" data-ajax-cart="true">
                    @csrf
                    <button class="btn btn-outline-danger btn-premium w-100">Clear Cart</button>
                </form>
            </div>
        </div>
    </div>
@endif

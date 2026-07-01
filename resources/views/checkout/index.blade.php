@extends('layouts.user')

@section('title', 'Checkout')

@section('content')
<div class="container section-pad">
    <div class="d-flex flex-column flex-lg-row justify-content-between gap-4 mb-4">
        <div>
            <div class="eyebrow mb-2">Checkout</div>
            <h1 class="fw-bold mb-1">Complete Your Order</h1>
            <p class="text-muted mb-0">A clean, secure flow for delivery and payment details.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill bg-dark px-3 py-2">1 Bag</span>
            <span class="text-muted">/</span>
            <span class="badge rounded-pill bg-dark px-3 py-2">2 Details</span>
            <span class="text-muted">/</span>
            <span class="badge rounded-pill bg-light text-dark border px-3 py-2">3 Done</span>
        </div>
    </div>

    <div class="row g-4 align-items-start">
        <div class="col-lg-7">
            <div class="surface p-4">
                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf

                    <h4 class="fw-bold mb-3">Contact</h4>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', auth()->user()->name) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="customer_email" class="form-control" value="{{ old('customer_email', auth()->user()->email) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="customer_phone" class="form-control" value="{{ old('customer_phone') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Postal Code</label>
                            <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code') }}" required>
                        </div>
                    </div>

                    <h4 class="fw-bold mb-3">Delivery</h4>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label">Address Line 1</label>
                            <input type="text" name="address_line_1" class="form-control" value="{{ old('address_line_1') }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Address Line 2</label>
                            <input type="text" name="address_line_2" class="form-control" value="{{ old('address_line_2') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">State</label>
                            <input type="text" name="state" class="form-control" value="{{ old('state') }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Order Notes</label>
                            <textarea name="notes" rows="3" class="form-control" placeholder="Delivery instructions or gift notes">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <h4 class="fw-bold mb-3">Payment</h4>
                    <div class="soft-surface p-3 mb-4">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select">
                            <option value="cash_on_delivery" @selected(old('payment_method', 'cash_on_delivery') === 'cash_on_delivery')>
                                Cash on Delivery
                            </option>
                             <option value="wallet">Wallet</option>
                        </select>
                        <div class="form-text">Online gateway support can plug into this same checkout flow.</div>
                    </div>

                    <button type="submit" class="btn btn-dark btn-premium btn-lg w-100 py-3">
                        Place Order <i class="bi bi-lock ms-1"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="surface p-4 position-sticky" style="top: 104px;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="fw-bold mb-0">Order Summary</h4>
                    <i class="bi bi-bag-check fs-4"></i>
                </div>

                @foreach ($cart->items as $item)
                    <div class="d-flex justify-content-between gap-3 py-3 border-bottom">
                        <div>
                            <div class="fw-semibold">{{ $item->product->name }}</div>
                            <small class="text-muted">{{ $item->quantity }} x Rs. {{ number_format($item->price, 2) }}</small>
                        </div>
                        <div class="fw-bold text-nowrap">Rs. {{ number_format($item->price * $item->quantity, 2) }}</div>
                    </div>
                @endforeach

                <form action="{{ route('checkout.coupon.apply') }}" method="POST" class="mt-4">
                    @csrf
                    <label class="form-label fw-semibold">Coupon Code</label>
                    <div class="input-group">
                        <input type="text" name="code" class="form-control" placeholder="Enter coupon">
                        <button class="btn btn-outline-dark btn-premium px-4">Apply</button>
                    </div>
                </form>

                @if ($coupon)
                    <div class="d-flex justify-content-between align-items-center mt-3 p-3 bg-success-subtle rounded-4">
                        <div>
                            <div class="fw-bold">{{ $coupon->code }}</div>
                            <small class="text-muted">Applied to this order</small>
                        </div>

                        <form action="{{ route('checkout.coupon.remove') }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-outline-danger btn-premium">Remove</button>
                        </form>
                    </div>
                @endif

                <div class="d-flex justify-content-between mt-4">
                    <span class="text-muted">Subtotal</span>
                    <strong>Rs. {{ number_format($summary['subtotal'], 2) }}</strong>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <span class="text-muted">Discount</span>
                    <strong class="text-success">- Rs. {{ number_format($summary['discount_total'], 2) }}</strong>
                </div>

                <hr>

                <div class="d-flex justify-content-between fs-5">
                    <strong>Total</strong>
                    <strong>Rs. {{ number_format($summary['total'], 2) }}</strong>
                </div>

                <div class="soft-surface p-3 mt-4">
                    <div class="d-flex gap-2">
                        <i class="bi bi-shield-check text-success"></i>
                        <small class="text-muted">Secure checkout, verified seller fulfillment, and order support after purchase.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

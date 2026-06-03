@extends('layouts.user')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="mb-4">Checkout</h2>

                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
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
                                <textarea name="notes" rows="4" class="form-control">{{ old('notes') }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Payment Method</label>
                                <select name="payment_method" class="form-select">
                                    <option value="cash_on_delivery" @selected(old('payment_method', 'cash_on_delivery') === 'cash_on_delivery')>
                                        Cash on Delivery
                                    </option>
                                </select>
                                <div class="form-text">
                                    COD works now. The live online gateway can plug into this flow next.
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-dark mt-4">
                            Place Order
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="mb-3">Order Summary</h4>

                    @foreach ($cart->items as $item)
                        <div class="d-flex justify-content-between gap-3 py-2 border-bottom">
                            <div>
                                <div class="fw-semibold">{{ $item->product->name }}</div>
                                <small class="text-muted">{{ $item->quantity }} x Rs. {{ number_format($item->price, 2) }}</small>
                            </div>

                            <div class="fw-semibold">
                                Rs. {{ number_format($item->price * $item->quantity, 2) }}
                            </div>
                        </div>
                    @endforeach

                    <form action="{{ route('checkout.coupon.apply') }}" method="POST" class="mt-4">
                        @csrf
                        <label class="form-label">Coupon Code</label>
                        <div class="input-group">
                            <input type="text" name="code" class="form-control" placeholder="Enter coupon">
                            <button class="btn btn-outline-dark">Apply</button>
                        </div>
                    </form>

                    @if ($coupon)
                        <div class="d-flex justify-content-between align-items-center mt-3 p-3 bg-light rounded">
                            <div>
                                <div class="fw-semibold">{{ $coupon->code }}</div>
                                <small class="text-muted">Applied to this order</small>
                            </div>

                            <form action="{{ route('checkout.coupon.remove') }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-outline-danger">Remove</button>
                            </form>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mt-4">
                        <span>Subtotal</span>
                        <strong>Rs. {{ number_format($summary['subtotal'], 2) }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <span>Discount</span>
                        <strong class="text-success">- Rs. {{ number_format($summary['discount_total'], 2) }}</strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between fs-5">
                        <strong>Total</strong>
                        <strong>Rs. {{ number_format($summary['total'], 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

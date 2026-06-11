    @extends(auth()->check() && auth()->user()->isCustomer()
    ? 'layouts.user'
    : 'layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-1">
                Order {{ $order->order_number }}
            </h2>

            <p class="text-muted mb-0">
                Placed
                {{ $order->placed_at?->format('d M Y h:i A') }}
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">

            <a
                href="{{ route('orders.invoice', $order) }}"
                class="btn btn-dark"
                target="_blank"
            >
                View Invoice
            </a>

            <a
                href="{{ route('orders.invoice.download', $order) }}"
                class="btn btn-outline-dark"
            >
                Download Invoice
            </a>

            <a
                href="{{ route('orders.index') }}"
                class="btn btn-outline-secondary"
            >
                Back to Orders
            </a>

        </div>

    </div>

    <div class="row g-4">

        {{-- LEFT SIDE --}}

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h4 class="mb-4">
                        Order Items
                    </h4>

                    @foreach ($order->items as $item)

                        <div class="border rounded p-3 mb-3">

                            <div class="d-flex justify-content-between">

                                <div>

                                    <h5 class="mb-1">
                                        {{ $item->product_name }}
                                    </h5>

                                    <div class="text-muted small">
                                        SKU:
                                        {{ $item->product_sku ?: 'N/A' }}
                                    </div>

                                    <div class="text-muted small">
                                        Quantity:
                                        {{ $item->quantity }}
                                    </div>

                                    <div class="text-muted small">
                                        Price:
                                        Rs.
                                        {{ number_format($item->price, 2) }}
                                    </div>

                                </div>

                                <div class="text-end">

                                    <div class="fw-bold fs-5">
                                        Rs.
                                        {{ number_format($item->line_total, 2) }}
                                    </div>

                                </div>

                            </div>

                            <hr>

                            <div class="row">

                                <div class="col-md-4">

                                    <div class="text-muted small">
                                        Platform Commission
                                    </div>

                                    <div class="fw-semibold text-danger">

                                        {{ $item->platform_commission_percent }}%

                                        (
                                        Rs.
                                        {{ number_format(
                                            $item->platform_commission_amount,
                                            2
                                        ) }}
                                        )

                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- <div class="text-muted small">
                                        Seller Earning
                                    </div>

                                    <div class="fw-semibold text-success">

                                        Rs.
                                        {{ number_format(
                                            $item->seller_earning,
                                            2
                                        ) }}

                                    </div> --}}

                                </div>

                                <div class="col-md-4">

                                    <div class="text-muted small">
                                        Final Item Total
                                    </div>

                                    <div class="fw-semibold">

                                        Rs.
                                        {{ number_format(
                                            $item->line_total,
                                            2
                                        ) }}

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

        {{-- RIGHT SIDE --}}

        <div class="col-lg-4">

            {{-- ORDER SUMMARY --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body">

                    <h4 class="mb-3">
                        Order Summary
                    </h4>

                    <div class="d-flex justify-content-between">
                        <span>Status</span>
                        <strong>
                            {{ ucfirst($order->status) }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <span>Payment Method</span>

                        <strong>
                            {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <span>Payment Status</span>

                        <strong>
                            {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <span>Invoice</span>

                        <strong>
                            {{ $order->invoice_generated_at
                                ? 'Generated'
                                : 'Pending generation' }}
                        </strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <span>Subtotal</span>

                        <strong>
                            Rs.
                            {{ number_format($order->subtotal, 2) }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <span>Discount</span>

                        <strong class="text-success">

                            - Rs.
                            {{ number_format(
                                $order->discount_total,
                                2
                            ) }}

                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mt-2">

                        <span>
                            Platform Revenue
                        </span>

                        <strong class="text-danger">

                            Rs.
                            {{ number_format(
                                $order->items->sum(
                                    'platform_commission_amount'
                                ),
                                2
                            ) }}

                        </strong>

                    </div>

                    <div class="d-flex justify-content-between mt-2">

                        {{-- <span>
                            Seller Earnings
                        </span>

                        <strong class="text-success">

                            Rs.
                            {{ number_format(
                                $order->items->sum(
                                    'seller_earning'
                                ),
                                2
                            ) }} --}}

                        </strong>

                    </div>

                    <hr>

                    <div class="d-flex justify-content-between fs-5">

                        <strong>Total</strong>

                        <strong>

                            Rs.
                            {{ number_format($order->total, 2) }}

                        </strong>

                    </div>

                    @if ($order->coupon)

                        <div class="mt-3 text-muted small">

                            Coupon Applied:
                            <strong>
                                {{ $order->coupon->code }}
                            </strong>

                        </div>

                    @endif

                </div>

            </div>

            {{-- SHIPPING DETAILS --}}

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h4 class="mb-3">
                        Shipping Details
                    </h4>

                    <div class="fw-semibold">
                        {{ $order->customer_name }}
                    </div>

                    <div>
                        {{ $order->customer_email }}
                    </div>

                    @if ($order->customer_phone)

                        <div>
                            {{ $order->customer_phone }}
                        </div>

                    @endif

                    <div class="mt-3">

                        {{ $order->address_line_1 }}

                        <br>

                        @if ($order->address_line_2)

                            {{ $order->address_line_2 }}

                            <br>

                        @endif

                        {{ $order->city }},
                        {{ $order->state }}
                        {{ $order->postal_code }}

                    </div>

                    @if ($order->notes)

                        <div class="mt-3">

                            <strong>
                                Notes:
                            </strong>

                            {{ $order->notes }}

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


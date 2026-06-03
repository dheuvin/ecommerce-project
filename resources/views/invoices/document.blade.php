<style>
    .invoice-shell {
        max-width: 900px;
        margin: 0 auto;
        background: #fff;
        color: #1f2937;
        font-family: Arial, Helvetica, sans-serif;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        gap: 24px;
        padding: 32px;
        background: #111827;
        color: #fff;
    }

    .invoice-section {
        padding: 32px;
    }

    .invoice-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 24px;
    }

    .invoice-muted {
        color: #6b7280;
        font-size: 14px;
    }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 16px;
    }

    .invoice-table th,
    .invoice-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
        vertical-align: top;
    }

    .invoice-table th:last-child,
    .invoice-table td:last-child {
        text-align: right;
    }

    .invoice-summary {
        margin-left: auto;
        width: 320px;
    }

    .invoice-summary-row {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        padding: 10px 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .invoice-summary-row.total {
        font-size: 20px;
        font-weight: 700;
        border-bottom: 0;
    }

    @media (max-width: 768px) {
        .invoice-header,
        .invoice-grid {
            grid-template-columns: 1fr;
            display: block;
        }

        .invoice-summary {
            width: 100%;
        }
    }
</style>

<div class="invoice-shell">
    <div class="invoice-header">
        <div>
            <div style="font-size: 28px; font-weight: 700;">Invoice</div>
            <div class="invoice-muted" style="color: #d1d5db; margin-top: 8px;">
                Order {{ $order->order_number }}
            </div>
        </div>

        <div style="text-align: right;">
            <div style="font-size: 22px; font-weight: 700;">Ecommerce Store</div>
            <div class="invoice-muted" style="color: #d1d5db; margin-top: 8px;">
                Generated {{ $order->invoice_generated_at?->format('d M Y h:i A') ?? now()->format('d M Y h:i A') }}
            </div>
        </div>
    </div>

    <div class="invoice-section">
        <div class="invoice-grid">
            <div>
                <div style="font-weight: 700; margin-bottom: 10px;">Billed To</div>
                <div>{{ $order->customer_name }}</div>
                <div class="invoice-muted">{{ $order->customer_email }}</div>
                @if ($order->customer_phone)
                    <div class="invoice-muted">{{ $order->customer_phone }}</div>
                @endif
                <div class="invoice-muted" style="margin-top: 10px;">
                    {{ $order->address_line_1 }}<br>
                    @if ($order->address_line_2)
                        {{ $order->address_line_2 }}<br>
                    @endif
                    {{ $order->city }}, {{ $order->state }} {{ $order->postal_code }}
                </div>
            </div>

            <div>
                <div style="font-weight: 700; margin-bottom: 10px;">Order Details</div>
                <div class="invoice-muted">Status: {{ ucfirst($order->status) }}</div>
                <div class="invoice-muted">Payment Method: {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</div>
                <div class="invoice-muted">Payment Status: {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</div>
                <div class="invoice-muted">Placed: {{ $order->placed_at?->format('d M Y h:i A') }}</div>
                @if ($order->coupon)
                    <div class="invoice-muted">Coupon: {{ $order->coupon->code }}</div>
                @endif
            </div>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Seller</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Line Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>
                            <div style="font-weight: 700;">{{ $item->product_name }}</div>
                            <div class="invoice-muted">SKU: {{ $item->product_sku ?: 'N/A' }}</div>
                        </td>
                        <td>{{ $item->seller?->name ?? 'Seller' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rs. {{ number_format($item->price, 2) }}</td>
                        <td>Rs. {{ number_format($item->line_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="invoice-summary" style="margin-top: 24px;">
            <div class="invoice-summary-row">
                <span>Subtotal</span>
                <strong>Rs. {{ number_format($order->subtotal, 2) }}</strong>
            </div>

            <div class="invoice-summary-row">
                <span>Discount</span>
                <strong>- Rs. {{ number_format($order->discount_total, 2) }}</strong>
            </div>

            <div class="invoice-summary-row total">
                <span>Total</span>
                <span>Rs. {{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        @if ($order->notes)
            <div style="margin-top: 24px;">
                <div style="font-weight: 700; margin-bottom: 10px;">Customer Notes</div>
                <div class="invoice-muted">{{ $order->notes }}</div>
            </div>
        @endif
    </div>
</div>

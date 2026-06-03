@extends(auth()->check() && auth()->user()->isCustomer() ? 'layouts.user' : 'layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Orders</h2>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Total</th>
                        <th>Invoice</th>
                        <th>Placed</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->user->name ?? $order->customer_name }}</td>
                            <td><span class="badge bg-dark">{{ ucfirst($order->status) }}</span></td>
                            <td>{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</td>
                            <td>Rs. {{ number_format($order->total, 2) }}</td>
                            <td>{{ $order->invoice_generated_at ? 'Ready' : 'Pending' }}</td>
                            <td>{{ $order->placed_at?->format('d M Y h:i A') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-dark">
                                        View
                                    </a>

                                    <a href="{{ route('orders.invoice', $order) }}" class="btn btn-sm btn-dark" target="_blank">
                                        Invoice
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $orders->links() }}
    </div>
</div>
@endsection

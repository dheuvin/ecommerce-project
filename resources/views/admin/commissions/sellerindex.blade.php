@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card p-3 shadow-sm">
                <h6>Total Sales</h6>
                <h4>₹{{ number_format($totalSales, 2) }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow-sm">
                <h6>Platform Fees</h6>
                <h4>₹{{ number_format($totalPlatformFee, 2) }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow-sm">
                <h6>Your Earnings</h6>
                <h4 class="text-success">₹{{ number_format($totalEarnings, 2) }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow-sm">
                <h6>Completed Orders</h6>
                <h4>{{ $totalOrders }}</h4>
            </div>
        </div>

    </div>

    {{-- Orders Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">My Commission Records</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Line Total</th>
                        <th>Platform Fee</th>
                        <th>Earning</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>#{{ $item->order_id }}</td>
                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ number_format($item->line_total, 2) }}</td>
                            <td>₹{{ number_format($item->platform_commission_amount, 2) }}</td>
                            <td class="text-success">
                                ₹{{ number_format($item->seller_earning, 2) }}
                            </td>
                            <td>{{ $item->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                No commission records found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $items->links() }}
        </div>
    </div>

</div>
@endsection

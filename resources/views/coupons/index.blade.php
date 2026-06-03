@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Coupons</h3>
    <a href="{{ route('coupons.create') }}" class="btn btn-primary">+ Create Coupon</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Min Order</th>
                    <th>Usage</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon->code }}</td>
                        <td>{{ ucfirst($coupon->type) }}</td>
                        <td>{{ $coupon->type === 'percentage' ? rtrim(rtrim(number_format($coupon->value, 2), '0'), '.') . '%' : 'Rs. ' . number_format($coupon->value, 2) }}</td>
                        <td>Rs. {{ number_format($coupon->min_order_amount, 2) }}</td>
                        <td>{{ $coupon->used_count }} / {{ $coupon->usage_limit ?? 'Unlimited' }}</td>
                        <td>
                            @if ($coupon->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('coupons.edit', $coupon) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('coupons.destroy', $coupon) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this coupon?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">No coupons found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $coupons->links() }}
</div>
@endsection

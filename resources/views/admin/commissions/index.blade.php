@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Commission Report</h4>
        </div>

        <div class="alert alert-info">
            This Month Commission :
            ₹{{ number_format($monthlyCommission, 2) }}
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <thead>
                    <tr>
                        <th>id</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Commission %</th>
                        <th>Commission Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($commissions as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $item->product_name }}</td>

                            <td>{{ $item->quantity }}</td>

                            <td>₹{{ number_format($item->price, 2) }}</td>

                            <td>{{ $item->platform_commission_percent }}%</td>

                            <td>
                                ₹{{ number_format($item->platform_commission_amount, 2) }}
                            </td>

                            <td>
                                {{ $item->created_at->format('d M Y') }}
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="text-center">
                                No commission found
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

            {{ $commissions->links() }}

        </div>
    </div>
@endsection

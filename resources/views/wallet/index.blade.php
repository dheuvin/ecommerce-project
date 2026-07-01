@extends('layouts.user')

@section('sidebar')
    @include('user.sidebar')
@endsection

@section('content')

    <div class="container mt-5">

        <div class="row">

            {{-- WALLET BALANCE CARD --}}
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <h5 class="text-muted">Wallet Balance</h5>
                        <h2 class="text-success">
                            ₹ {{ $wallet->balance ?? 0 }}
                        </h2>

                        <a href="{{ route('wallet.addMoneyForm') }}" class="btn btn-primary mt-3 w-100">
                            Add Money
                        </a>
                    </div>
                </div>
            </div>

            {{-- TRANSACTION HISTORY --}}
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Transaction History</h5>
                    </div>

                    <div class="card-body p-0">

                        @if ($transaction->count() > 0)
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($transaction as $key => $t)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>

                                            <td>
                                                @if ($t->type == 'credit')
                                                    <span class="badge bg-success">Credit</span>
                                                @else
                                                    <span class="badge bg-danger">Debit</span>
                                                @endif
                                            </td>

                                            <td>
                                                ₹ {{ $t->amount }}
                                            </td>

                                            <td>
                                                {{ $t->description ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $t->created_at->format('d M Y, h:i A') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="p-4 text-center text-muted">
                                No transactions found
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection

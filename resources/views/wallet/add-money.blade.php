@extends('layouts.user')

@section('sidebar')
    @include('user.sidebar')
@endsection
@section('content')


    <div class="container mt-5">

        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Add Money to Wallet</h4>
                    </div>

                    <div class="card-body">

                        {{-- Success Message --}}
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- Error Message --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('wallet.addMoney') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Enter Amount</label>
                                <input type="number" name="amount" class="form-control" placeholder="Enter amount"
                                    min="1" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description (optional)</label>
                                <input type="text" name="description" class="form-control"
                                    placeholder="e.g. Add money via UPI">
                            </div>

                            <div class="d-grid gap-2 mt-3">

                                <button type="submit" class="btn btn-success w-100">
                                    Add Money
                                </button>

                                <a href="{{ url()->previous() }}" class="btn btn-secondary w-100">
                                    ← Back
                                </a>

                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection

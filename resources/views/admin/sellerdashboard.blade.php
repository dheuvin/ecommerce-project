@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h2 class="fw-bold mb-4">
        Dashboard Overview
    </h2>

    <div class="row g-4">

        <div class="col-md-3">
            <div class="card border-0 shadow bg-primary text-white">
                <div class="card-body">
                    <h6>Total Users</h6>
                    <h2>{{ $totalUsers }}</h2>
                    <small>Registered Users</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow bg-success text-white">
                <div class="card-body">
                    <h6>Total Sellers</h6>
                    <h2>{{ $totalSellers }}</h2>
                    <small>Active Sellers</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow bg-warning">
                <div class="card-body">
                    <h6>Total Products</h6>
                    <h2>{{ $totalProducts }}</h2>
                    <small>Products Listed</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow bg-danger text-white">
                <div class="card-body">
                    <h6>Pending Products</h6>
                    <h2>{{ $pendingProducts }}</h2>
                    <small>Waiting Approval</small>
                </div>
            </div>
        </div>

    </div>

    <div class="row mt-4">

        <div class="col-lg-8">

            <div class="card border-0 shadow">
                <div class="card-header fw-bold">
                    Sales Analytics
                </div>

                <div class="card-body">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

        </div>

        <div class="col-lg-4">

            <div class="card border-0 shadow">
                <div class="card-header fw-bold">
                    Quick Actions
                </div>

                <div class="card-body d-grid gap-2">

                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        Products
                    </a>

                    <a href="{{ route('categories.index') }}" class="btn btn-success">
                        Categories
                    </a>

                    <a href="{{ route('orders.index') }}" class="btn btn-warning">
                        Orders
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('salesChart'), {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Sales',
            data: [10, 25, 15, 30, 20, 40]
        }]
    }
});
</script>

@endsection

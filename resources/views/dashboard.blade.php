@extends('layouts.app')

@section('content')

<h2 class="fw-bold mb-4">Dashboard Overview</h2>

<div class="row g-4">

    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body">
                <h6>Total Users</h6>
                <h2>{{ $totalUsers }}</h2>
                <small>Registered Users</small>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body">
                <h6>Total Sellers</h6>
                <h2>{{ $totalSellers }}</h2>
                <small>Active Sellers</small>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm bg-warning text-dark">
            <div class="card-body">
                <h6>Total Products</h6>
                <h2>{{ $totalProducts }}</h2>
                <small>Products Listed</small>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm bg-danger text-white">
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
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                Sales Analytics
            </div>

            <div class="card-body">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                Quick Actions
            </div>

            <div class="card-body d-grid gap-2">

                <a href="{{ route('products.create') }}"
                    class="btn btn-primary">
                    Add Product
                </a>

                <a href="{{ route('products.pending') }}"
                    class="btn btn-warning">
                    Pending Products
                </a>

                <a href="{{ route('orders.index') }}"
                    class="btn btn-success">
                    Orders
                </a>

                <a href="{{ route('categories.index') }}"
                    class="btn btn-info text-white">
                    Categories
                </a>

            </div>
        </div>
    </div>

</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white fw-bold">
        Recent System Activity
    </div>

    <div class="card-body">

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Module</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>Products</td>
                    <td><span class="badge bg-success">Running</span></td>
                </tr>

                <tr>
                    <td>Orders</td>
                    <td><span class="badge bg-success">Running</span></td>
                </tr>

                <tr>
                    <td>Users</td>
                    <td><span class="badge bg-success">Running</span></td>
                </tr>

                <tr>
                    <td>Tickets</td>
                    <td><span class="badge bg-primary">Active</span></td>
                </tr>
            </tbody>
        </table>

    </div>
</div>

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun'],
        datasets: [{
            label: 'Sales',
            data: [12,19,15,25,22,35],
            borderWidth: 3,
            tension: .4
        }]
    }
});
</script>

@endsection

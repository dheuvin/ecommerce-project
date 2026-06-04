@extends('layouts.app')

@section('content')
@php
    $salesMax = max($salesSeries->max('total'), 1);
    $salesPoints = $salesSeries->values()->map(function ($item, $index) use ($salesSeries, $salesMax) {
        $count = max($salesSeries->count() - 1, 1);
        $x = 22 + (($index / $count) * 556);
        $y = 160 - (($item['total'] / $salesMax) * 130);

        return round($x, 1).','.round($y, 1);
    })->implode(' ');
    $salesArea = '22,170 '.$salesPoints.' 578,170';

    $statusTotal = max(array_sum($orderStatusCounts), 1);
    $completedPct = round(($orderStatusCounts['Completed'] / $statusTotal) * 100);
    $processingPct = round(($orderStatusCounts['Processing'] / $statusTotal) * 100);
    $pendingPct = round(($orderStatusCounts['Pending'] / $statusTotal) * 100);
    $cancelledPct = max(0, 100 - $completedPct - $processingPct - $pendingPct);
@endphp

<style>
    .dashboard-head {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 22px;
    }

    .dashboard-title h1 {
        margin: 0 0 8px;
        font-size: 26px;
        font-weight: 800;
    }

    .breadcrumb-line {
        color: #64748b;
        font-size: 13px;
    }

    .date-pill {
        align-self: flex-start;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        color: #475569;
        font-size: 13px;
    }

    .metric-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 20px;
    }

    .metric-card,
    .panel-card {
        border: 1px solid #eef2f7;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .06);
    }

    .metric-card {
        display: flex;
        align-items: center;
        gap: 18px;
        min-height: 116px;
        padding: 20px;
    }

    .metric-icon {
        display: grid;
        flex: 0 0 auto;
        place-items: center;
        width: 58px;
        height: 58px;
        border-radius: 14px;
        color: #fff;
        font-size: 28px;
    }

    .metric-icon.blue {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }

    .metric-icon.green {
        background: linear-gradient(135deg, #22c55e, #16a34a);
    }

    .metric-icon.orange {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
    }

    .metric-icon.red {
        background: linear-gradient(135deg, #fb7185, #ef4444);
    }

    .metric-label {
        margin-bottom: 5px;
        color: #475569;
        font-size: 13px;
    }

    .metric-value {
        margin-bottom: 5px;
        color: #0f172a;
        font-size: 24px;
        font-weight: 800;
        line-height: 1;
    }

    .metric-trend {
        color: #16a34a;
        font-size: 12px;
        font-weight: 600;
    }

    .metric-trend.down {
        color: #ef4444;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(320px, 1fr);
        gap: 18px;
        margin-bottom: 20px;
    }

    .bottom-grid {
        display: grid;
        grid-template-columns: 1.1fr 1fr;
        gap: 18px;
    }

    .panel-card {
        padding: 18px;
    }

    .panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .panel-head h2 {
        margin: 0;
        font-size: 16px;
        font-weight: 800;
    }

    .small-select,
    .panel-action {
        height: 34px;
        border: 1px solid #dbe4ef;
        border-radius: 7px;
        background: #fff;
        color: #334155;
        font-size: 12px;
    }

    .panel-action {
        display: inline-flex;
        align-items: center;
        padding: 0 12px;
        border-color: #2563eb;
        background: #2563eb;
        color: #fff;
        text-decoration: none;
    }

    .sales-chart {
        width: 100%;
        height: 235px;
    }

    .chart-labels {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        margin: -4px 18px 0 36px;
        color: #475569;
        font-size: 12px;
    }

    .order-summary {
        display: grid;
        grid-template-columns: 170px 1fr;
        gap: 18px;
        align-items: center;
        min-height: 230px;
    }

    .donut {
        display: grid;
        place-items: center;
        width: 160px;
        height: 160px;
        border-radius: 50%;
        background:
            radial-gradient(circle, #fff 0 48%, transparent 49%),
            conic-gradient(
                #22c55e 0 {{ $completedPct }}%,
                #3b82f6 {{ $completedPct }}% {{ $completedPct + $processingPct }}%,
                #f59e0b {{ $completedPct + $processingPct }}% {{ $completedPct + $processingPct + $pendingPct }}%,
                #ef4444 {{ $completedPct + $processingPct + $pendingPct }}% 100%
            );
    }

    .donut strong {
        display: block;
        font-size: 24px;
        line-height: 1;
        text-align: center;
    }

    .donut span {
        color: #64748b;
        font-size: 12px;
    }

    .legend-row {
        display: grid;
        grid-template-columns: 12px 1fr auto;
        gap: 9px;
        align-items: center;
        margin-bottom: 15px;
        color: #334155;
        font-size: 13px;
    }

    .legend-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .admin-table {
        margin: 0;
        font-size: 13px;
    }

    .admin-table th {
        padding: 10px 0;
        color: #475569;
        font-weight: 700;
        border-bottom: 1px solid #e5e7eb;
    }

    .admin-table td {
        padding: 10px 0;
        border-bottom: 1px solid #edf2f7;
        vertical-align: middle;
    }

    .status-pill {
        display: inline-flex;
        padding: 5px 9px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-confirmed,
    .status-completed {
        background: #dcfce7;
        color: #15803d;
    }

    .status-processing {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-pending {
        background: #fef3c7;
        color: #b45309;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #dc2626;
    }

    @media (max-width: 1200px) {
        .metric-grid,
        .dashboard-grid,
        .bottom-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .dashboard-head,
        .order-summary {
            display: block;
        }

        .date-pill {
            margin-top: 14px;
        }

        .metric-grid,
        .dashboard-grid,
        .bottom-grid {
            grid-template-columns: 1fr;
        }

        .donut {
            margin: 10px auto 22px;
        }
    }
</style>

<div class="dashboard-head">
    <div class="dashboard-title">
        <h1>Dashboard Overview</h1>
        <div class="breadcrumb-line">Home / Dashboard</div>
    </div>

    <div class="date-pill">
        <i class="bi bi-calendar3"></i>
        {{ now()->subDays(6)->format('d M Y') }} - {{ now()->format('d M Y') }}
        <i class="bi bi-chevron-down"></i>
    </div>
</div>

<div class="metric-grid">
    <div class="metric-card">
        <div class="metric-icon blue"><i class="bi bi-people"></i></div>
        <div>
            <div class="metric-label">Total Users</div>
            <div class="metric-value">{{ number_format($totalUsers) }}</div>
            <div class="metric-trend">+2.5% from last month</div>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon green"><i class="bi bi-shop"></i></div>
        <div>
            <div class="metric-label">Total Sellers</div>
            <div class="metric-value">{{ number_format($totalSellers) }}</div>
            <div class="metric-trend">+8.2% from last month</div>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon orange"><i class="bi bi-box"></i></div>
        <div>
            <div class="metric-label">Total Products</div>
            <div class="metric-value">{{ number_format($totalProducts) }}</div>
            <div class="metric-trend">+15.3% from last month</div>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon red"><i class="bi bi-hourglass-split"></i></div>
        <div>
            <div class="metric-label">Pending Products</div>
            <div class="metric-value">{{ number_format($pendingProducts) }}</div>
            <div class="metric-trend down">{{ number_format($openTickets) }} open tickets</div>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="panel-card">
        <div class="panel-head">
            <h2>Sales Overview</h2>
            <button class="small-select px-3" type="button">This Week <i class="bi bi-chevron-down ms-1"></i></button>
        </div>

        <svg class="sales-chart" viewBox="0 0 600 210" preserveAspectRatio="none" role="img" aria-label="Weekly sales chart">
            <defs>
                <linearGradient id="salesFill" x1="0" x2="0" y1="0" y2="1">
                    <stop offset="0" stop-color="#2563eb" stop-opacity=".24" />
                    <stop offset="1" stop-color="#2563eb" stop-opacity=".02" />
                </linearGradient>
            </defs>

            @foreach ([30, 60, 90, 120, 150, 180] as $line)
                <line x1="22" y1="{{ $line }}" x2="578" y2="{{ $line }}" stroke="#e5e7eb" stroke-width="1" />
            @endforeach

            <polygon points="{{ $salesArea }}" fill="url(#salesFill)" />
            <polyline points="{{ $salesPoints }}" fill="none" stroke="#2563eb" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />

            @foreach (explode(' ', $salesPoints) as $point)
                @php
                    [$x, $y] = explode(',', $point);
                @endphp
                <circle cx="{{ $x }}" cy="{{ $y }}" r="3.5" fill="#2563eb" />
            @endforeach
        </svg>

        <div class="chart-labels">
            @foreach ($salesSeries as $item)
                <span>{{ $item['label'] }}</span>
            @endforeach
        </div>
    </div>

    <div class="panel-card">
        <div class="panel-head">
            <h2>Orders Summary</h2>
        </div>

        <div class="order-summary">
            <div class="donut">
                <div>
                    <strong>{{ number_format($totalOrders) }}</strong>
                    <span>Total Orders</span>
                </div>
            </div>

            <div>
                @foreach ($orderStatusCounts as $label => $count)
                    @php
                        $dotColor = [
                            'Completed' => '#22c55e',
                            'Processing' => '#3b82f6',
                            'Pending' => '#f59e0b',
                            'Cancelled' => '#ef4444',
                        ][$label];
                    @endphp
                    <div class="legend-row">
                        <span class="legend-dot" style="background: {{ $dotColor }}"></span>
                        <span>{{ $label }}</span>
                        <span>{{ number_format($count) }} ({{ round(($count / $statusTotal) * 100) }}%)</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="bottom-grid">
    <div class="panel-card">
        <div class="panel-head">
            <h2>Recent Orders</h2>
            <a class="panel-action" href="{{ route('orders.index') }}">View All Orders</a>
        </div>

        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentOrders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ optional($order->placed_at ?? $order->created_at)->format('d M Y') }}</td>
                            <td>Rs. {{ number_format($order->total, 2) }}</td>
                            <td>
                                <span class="status-pill status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted text-center py-4">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel-card">
        <div class="panel-head">
            <h2>Top Selling Products</h2>
            <a class="panel-action" href="{{ route('products.index') }}">View All Products</a>
        </div>

        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Sold</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topProducts as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ number_format($item->sold_count) }}</td>
                            <td>Rs. {{ number_format($item->revenue, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-muted text-center py-4">No sales data found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

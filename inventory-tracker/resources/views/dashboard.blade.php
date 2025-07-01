@extends('layouts.inventory')

@section('title', 'Dashboard - Inventory Tracker')

@push('head-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="container my-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4"><i class="bi bi-speedometer2"></i> Dashboard</h1>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Items</h6>
                            <h3 class="mb-0">{{ $totalItems }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-box fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Categories</h6>
                            <h3 class="mb-0">{{ $totalCategories }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-tags fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Low Stock</h6>
                            <h3 class="mb-0">{{ $lowStockCount }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-exclamation-triangle fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-white bg-orange" style="background-color: #fd7e14 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Expiring Soon</h6>
                            <h3 class="mb-0">{{ $expiringCount }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-clock fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Expired</h6>
                            <h3 class="mb-0">{{ $expiredCount }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-x-circle fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Value</h6>
                            <h3 class="mb-0">${{ number_format($totalValue, 0) }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-currency-dollar fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-pie-chart"></i> Items by Category</h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="bi bi-bar-chart"></i> Stock Levels</h5>
                </div>
                <div class="card-body">
                    <canvas id="stockChart" width="400" height="200"></canvas>
                </div>
            </div>
            <div class="card col">
                <div class="card-header">
                    <h5>
                        <i class="bi bi-clock text-warning"></i> Expiring Items
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($expiringItems as $item) 
                            @php
                                $expirationDate = \Carbon\Carbon::parse($item->expiration_date);
                                $today = \Carbon\Carbon::today();
                                $daysUntilExpiry = $today->diffInDays($expirationDate, false);
                            @endphp
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $item->name }}</h6>
                                    <small class="text-muted">{{ $item->category_name }} • {{ $expirationDate->format('M j') }}</small>
                                </div>
                                <span class="badge bg-{{ $daysUntilExpiry <= 1 ? 'danger' : ($daysUntilExpiry <= 3 ? 'warning' : 'info') }} rounded-pill">
                                    {{ $daysUntilExpiry == 0 ? 'Today' : $daysUntilExpiry . 'd' }}
                                </span>
                            </div>
                        @empty
                            <div class="list-group-item">
                                <p class="text-muted mb-0">No expiring items.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('items.expiring') }}" class="btn btn-outline-warning btn-sm">
                            View All Expiring
                        </a>
                    </div>
                </div>
            </div> 
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-clock-history"></i> Recently Added Items</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($recentItems as $item)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $item->name }}</h6>
                                    <small class="text-muted">{{ $item->category_name }} • Qty: {{ $item->quantity }}</small>
                                </div>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($item->added_date)->diffForHumans() }}</small>
                            </div>
                        @empty
                            <div class="list-group-item">
                                <p class="text-muted mb-0">No recent items found.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('items.index') }}" class="btn btn-outline-primary btn-sm">
                            View All Items
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>
                        <i class="bi bi-exclamation-triangle text-warning"></i> Low Stock Alerts
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($lowStockItems as $item)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $item->name }}</h6>
                                    <small class="text-muted">{{ $item->category_name }}</small>
                                </div>
                                <span class="badge bg-{{ $item->quantity <= 1 ? 'danger' : 'warning' }} rounded-pill">
                                    {{ $item->quantity }} left
                                </span>
                            </div>
                        @empty
                            <div class="list-group-item">
                                <p class="text-muted mb-0">No low stock items.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('items.index', ['filter' => 'low_stock']) }}" class="btn btn-outline-warning btn-sm">
                            View All Low Stock
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($categoryChart['labels'] ?? ['No Data']) !!},
            datasets: [{
                data: {!! json_encode($categoryChart['data'] ?? [0]) !!},
                backgroundColor: [
                    '#0d6efd',
                    '#198754', 
                    '#ffc107',
                    '#dc3545',
                    '#6f42c1',
                    '#fd7e14',
                    '#20c997',
                    '#6610f2'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Stock Chart
    const stockCtx = document.getElementById('stockChart').getContext('2d');
    new Chart(stockCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($stockChart['labels'] ?? ['No Data']) !!},
            datasets: [{
                label: 'Stock Quantity',
                data: {!! json_encode($stockChart['data'] ?? [0]) !!},
                backgroundColor: '#0d6efd'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush

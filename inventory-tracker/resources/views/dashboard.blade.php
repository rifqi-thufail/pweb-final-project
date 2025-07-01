@extends('layouts.inventory')

@section('title', 'Dashboard - Inventory Tracker')

@push('head-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@push('styles')
<style>
    .dashboard-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: none !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    
    .dashboard-card:active {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .dashboard-card .card-body {
        position: relative;
        overflow: hidden;
    }
    
    .dashboard-card .card-body::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transition: left 0.5s;
    }
    
    .dashboard-card:hover .card-body::before {
        left: 100%;
    }
    
    .dashboard-card .card-title {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    /* Add a subtle pulse animation for cards with alerts */
    .dashboard-card.bg-warning:hover,
    .dashboard-card.bg-danger:hover {
        animation: pulse 1s infinite;
    }
    
    .dashboard-card.bg-orange:hover {
        animation: pulse 1s infinite;
    }
    
    @keyframes pulse {
        0% {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        50% {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
        }
        100% {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
    }
    
    /* Add a click indicator */
    .dashboard-card .card-body::after {
        content: '→';
        position: absolute;
        top: 10px;
        right: 10px;
        opacity: 0;
        transition: opacity 0.3s ease;
        font-size: 1.2rem;
        font-weight: bold;
    }
    
    .dashboard-card:hover .card-body::after {
        opacity: 0.7;
    }
</style>
@endpush

@section('content')
<div class="container my-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4"><i class="bi bi-speedometer2"></i> Dashboard</h1>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4 g-sm-4 ">
        <div class="col-md-3">
            <div class="card text-white bg-primary dashboard-card" onclick="window.location.href='{{ route('items.index') }}'">
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
        <div class="col-md-3">
            <div class="card text-white bg-success dashboard-card" onclick="window.location.href='{{ route('categories.index') }}'">
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
            <div class="card text-white bg-warning dashboard-card" onclick="window.location.href='{{ route('items.index', ['filter' => 'low_stock']) }}'">
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
            <div class="card text-white bg-orange dashboard-card" style="background-color: #fd7e14 !important;" onclick="window.location.href='{{ route('items.index', ['filter' => 'expiring_soon']) }}'">
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
            <div class="card text-white bg-danger dashboard-card" onclick="window.location.href='{{ route('items.index', ['filter' => 'expired']) }}'">
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
    </div>

    <!-- Charts Row -->
    <div class="row mb-4 g-sm-4">
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
        <div class="col-md-6 d-flex flex-column">
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="bi bi-bar-chart"></i> Stock Levels</h5>
                </div>
                <div class="card-body">
                    <canvas id="stockChart" width="400" height="200"></canvas>
                </div>
            </div>
            <div class="card flex-fill">
                <div class="card-header">
                    <h5>
                        <i class="bi bi-clock text-warning"></i> Expiring Items
                    </h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="list-group list-group-flush flex-grow-1">
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
    <div class="row g-sm-4">
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

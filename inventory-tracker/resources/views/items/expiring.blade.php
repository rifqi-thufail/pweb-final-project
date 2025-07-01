@extends('layouts.inventory')

@section('title', 'Expiring Items - Inventory Tracker')

@section('content')
<div class="container my-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-clock-history text-warning"></i> Items Expiring Soon</h1>
                <div>
                    <a href="{{ route('items.index') }}" class="btn btn-secondary me-2">
                        <i class="bi bi-arrow-left"></i> Back to Items
                    </a>
                    <a href="{{ route('items.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add New Item
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Days -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text">Show items expiring within</span>
                <select class="form-select" id="daysFilter">
                    <option value="3" {{ $days == 3 ? 'selected' : '' }}>3 days</option>
                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>7 days</option>
                    <option value="14" {{ $days == 14 ? 'selected' : '' }}>14 days</option>
                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>30 days</option>
                </select>
            </div>
        </div>
        <div class="col-md-8">
            <div class="alert alert-warning mb-0">
                <i class="bi bi-info-circle"></i>
                <strong>{{ $items->count() }}</strong> items expiring within {{ $days }} days
            </div>
        </div>
    </div>

    <!-- Expiring Items Table -->
    <div class="card">
        <div class="card-body">
            @if($items->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Expiration Date</th>
                                <th>Days Until Expiry</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                @php
                                    $expirationDate = \Carbon\Carbon::parse($item->expiration_date);
                                    $today = \Carbon\Carbon::today();
                                    $daysUntilExpiry = $today->diffInDays($expirationDate, false);
                                @endphp
                                <tr class="{{ $daysUntilExpiry <= 3 ? 'table-warning' : '' }}">
                                    <td>
                                        <div>
                                            <h6 class="mb-1">{{ $item->name }}</h6>
                                            @if($item->description)
                                                <small class="text-muted">{{ Str::limit($item->description, 50) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $item->category_name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold {{ $item->quantity == 0 ? 'text-danger' : ($item->quantity <= 5 ? 'text-warning' : '') }}">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $expirationDate->format('Y-m-d') }}</span>
                                        <br>
                                        <small class="text-muted">{{ $expirationDate->format('l, M j') }}</small>
                                    </td>
                                    <td>
                                        @if($daysUntilExpiry <= 1)
                                            <span class="badge bg-danger fs-6">
                                                <i class="bi bi-exclamation-triangle"></i>
                                                {{ $daysUntilExpiry == 0 ? 'Today' : $daysUntilExpiry . ' day' }}
                                            </span>
                                        @elseif($daysUntilExpiry <= 3)
                                            <span class="badge bg-warning fs-6">
                                                <i class="bi bi-clock"></i>
                                                {{ $daysUntilExpiry }} days
                                            </span>
                                        @else
                                            <span class="badge bg-info fs-6">
                                                <i class="bi bi-calendar"></i>
                                                {{ $daysUntilExpiry }} days
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('items.show', $item->id) }}" class="btn btn-outline-info" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('items.edit', $item->id) }}" class="btn btn-outline-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-check-circle text-success fs-1 d-block mb-3"></i>
                    <h4 class="text-success">Great news!</h4>
                    <p class="text-muted mb-0">No items are expiring within {{ $days }} days.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('daysFilter').addEventListener('change', function() {
        const days = this.value;
        window.location.href = `{{ route('items.expiring') }}?days=${days}`;
    });
</script>
@endpush

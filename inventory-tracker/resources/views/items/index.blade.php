@extends('layouts.inventory')

@section('title', 'Items - Inventory Tracker')

@section('content')
<div class="container my-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-box"></i> Items</h1>
                <div class="header-actions">
                    <a href="{{ route('items.expiring') }}" class="btn btn-warning mx-2">
                        <i class="bi bi-clock"></i> View Expiring Items
                    </a>
                    <a href="{{ route('items.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add New Item
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Search items..." 
                       id="searchInput" value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-2">
            <select class="form-select" id="categoryFilter">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="stockFilter">
                <option value="">Stock Levels</option>
                <option value="in_stock" {{ request('filter') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                <option value="low_stock" {{ request('filter') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                <option value="out_of_stock" {{ request('filter') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="expirationFilter">
                <option value="">Expiration Status</option>
                <option value="expiring_soon" {{ request('filter') == 'expiring_soon' ? 'selected' : '' }}>Expiring Soon (7 days)</option>
                <option value="expired" {{ request('filter') == 'expired' ? 'selected' : '' }}>Expired</option>
                <option value="expiring_this_week" {{ request('filter') == 'expiring_this_week' ? 'selected' : '' }}>Expiring This Week</option>
            </select>
        </div>
    </div>

    <!-- Items Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Added Date</th>
                            <th>Expiration</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex">
                                        <div>
                                            <h6 class="mb-1">{{ $item->name }}</h6>
                                            @if($item->description)
                                                <small class="text-muted">{{ Str::limit($item->description, 50) }}</small>
                                            @endif
                                        </div>
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
                                    @if($item->quantity == 0)
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @elseif($item->quantity <= 5)
                                        <span class="badge bg-warning">Low Stock</span>
                                    @else
                                        <span class="badge bg-success">In Stock</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->added_date)->format('Y-m-d') }}</td>
                                <td>
                                    @if($item->expiration_date)
                                        @php
                                            $expirationDate = \Carbon\Carbon::parse($item->expiration_date);
                                            $today = \Carbon\Carbon::today();
                                            $daysUntilExpiry = $today->diffInDays($expirationDate, false);
                                        @endphp
                                        
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">{{ $expirationDate->format('Y-m-d') }}</span>
                                            @if($daysUntilExpiry < 0)
                                                <span class="badge bg-danger" title="Expired {{ abs($daysUntilExpiry) }} days ago">
                                                    <i class="bi bi-exclamation-triangle"></i> Expired
                                                </span>
                                            @elseif($daysUntilExpiry <= 7)
                                                <span class="badge bg-warning" title="Expires in {{ $daysUntilExpiry }} days">
                                                    <i class="bi bi-clock"></i> {{ $daysUntilExpiry }}d
                                                </span>
                                            @else
                                                <span class="badge bg-success" title="Expires in {{ $daysUntilExpiry }} days">
                                                    <i class="bi bi-check-circle"></i> {{ $daysUntilExpiry }}d
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">
                                            <i class="bi bi-dash"></i> No expiration
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
                                        <button class="btn btn-outline-danger" title="Delete" 
                                                onclick="confirmDelete({{ $item->id }}, '{{ $item->name }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-box fs-1 d-block mb-2"></i>
                                        <p class="mb-0">No items found</p>
                                        <a href="{{ route('items.create') }}" class="btn btn-primary btn-sm mt-2">
                                            <i class="bi bi-plus-circle"></i> Add First Item
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($items->hasPages())
                <div class="mt-4">
                    {{ $items->appends(request()->query())->links('custom-pagination') }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete <strong id="deleteItemName"></strong>? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Header button styling */
    .header-actions {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }
    
    .header-actions .btn {
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        transition: all 0.2s ease-in-out;
    }
    
    .header-actions .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .pagination .page-link {
        border-radius: 8px;
        margin: 0 2px;
        border: 1px solid #dee2e6;
        color: #6c757d;
        transition: all 0.2s ease-in-out;
    }
    
    .pagination .page-link:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
        color: #495057;
        transform: translateY(-1px);
    }
    
    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.25);
    }
    
    .pagination .page-item.disabled .page-link {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
    }
    
    /* Responsive header adjustments */
    @media (max-width: 576px) {
        .header-actions {
            flex-direction: column;
            gap: 0.5rem;
            width: 100%;
        }
        
        .header-actions .btn {
            width: 100%;
            justify-content: center;
        }
        
        .d-flex.justify-content-between.align-items-center {
            flex-direction: column;
            align-items: stretch !important;
            gap: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function confirmDelete(itemId, itemName) {
        document.getElementById('deleteItemName').textContent = itemName;
        document.getElementById('deleteForm').action = `/items/${itemId}`;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function() {
        debounce(() => {
            filterItems();
        }, 500)();
    });

    // Filter functionality with mutual exclusion
    document.getElementById('categoryFilter').addEventListener('change', filterItems);
    document.getElementById('stockFilter').addEventListener('change', function() {
        // Clear expiration filter when stock filter is selected
        if (this.value) {
            document.getElementById('expirationFilter').value = '';
        }
        filterItems();
    });
    document.getElementById('expirationFilter').addEventListener('change', function() {
        // Clear stock filter when expiration filter is selected
        if (this.value) {
            document.getElementById('stockFilter').value = '';
        }
        filterItems();
    });

    function filterItems() {
        const search = document.getElementById('searchInput').value;
        const category = document.getElementById('categoryFilter').value;
        const stock = document.getElementById('stockFilter').value;
        const expiration = document.getElementById('expirationFilter').value;
        
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (category) params.append('category', category);
        
        // Handle both stock and expiration filters (mutually exclusive)
        if (stock) {
            params.append('filter', stock);
        } else if (expiration) {
            params.append('filter', expiration);
        }
        
        const url = new URL(window.location.href);
        url.search = params.toString();
        window.location.href = url.toString();
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
</script>
@endpush

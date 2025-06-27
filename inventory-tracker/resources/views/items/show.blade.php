@extends('layouts.inventory')

@section('title', 'Item Details - Inventory Tracker')

@section('content')
<div class="container my-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-eye"></i> Item Details</h1>
                <div>
                    <a href="{{ route('items.edit', $item->id) }}" class="btn btn-warning me-2">
                        <i class="bi bi-pencil"></i> Edit Item
                    </a>
                    <a href="{{ route('items.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Items
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-info-circle"></i> Item Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Name:</td>
                                    <td>{{ $item->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Category:</td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $item->category_name }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Quantity:</td>
                                    <td>
                                        <span class="fw-bold fs-5 {{ $item->quantity == 0 ? 'text-danger' : ($item->quantity <= 5 ? 'text-warning' : 'text-success') }}">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Status:</td>
                                    <td>
                                        @if($item->quantity == 0)
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @elseif($item->quantity <= 5)
                                            <span class="badge bg-warning">Low Stock</span>
                                        @else
                                            <span class="badge bg-success">In Stock</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Added Date:</td>
                                    <td>{{ \Carbon\Carbon::parse($item->added_date)->format('F j, Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Added By:</td>
                                    <td>{{ Auth::user()->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Last Updated:</td>
                                    <td>{{ \Carbon\Carbon::parse($item->updated_at)->format('F j, Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Item ID:</td>
                                    <td><code>#ITM{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</code></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <h6 class="fw-bold">Description:</h6>
                            <p class="text-muted">
                                {{ $item->description ?: 'No description provided.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-activity"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-success" onclick="showUpdateQuantityModal()">
                            <i class="bi bi-plus-minus"></i> Update Quantity
                        </button>
                        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit Details
                        </a>
                        <button class="btn btn-danger" onclick="confirmDelete({{ $item->id }}, '{{ $item->name }}')">
                            <i class="bi bi-trash"></i> Delete Item
                        </button>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6><i class="bi bi-graph-up"></i> Stock Status</h6>
                </div>
                <div class="card-body">
                    @php
                        $stockPercentage = min(100, ($item->quantity / max(15, $item->quantity)) * 100);
                        $progressColor = $item->quantity == 0 ? 'danger' : ($item->quantity <= 5 ? 'warning' : 'success');
                    @endphp
                    <div class="progress mb-3">
                        <div class="progress-bar bg-{{ $progressColor }}" role="progressbar" 
                             style="width: {{ $stockPercentage }}%" 
                             aria-valuenow="{{ $stockPercentage }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <small class="text-muted">
                        Current stock level: {{ number_format($stockPercentage, 0) }}% ({{ $item->quantity }} items)
                    </small>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Min. Stock Level:</small>
                        <small class="fw-bold">5</small>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Max. Stock Level:</small>
                        <small class="fw-bold">15</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Quantity Modal -->
<div class="modal fade" id="updateQuantityModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Quantity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('items.update-quantity', $item->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="currentQuantity" class="form-label">Current Quantity</label>
                        <input type="number" class="form-control" id="currentQuantity" 
                               value="{{ $item->quantity }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="newQuantity" class="form-label">New Quantity</label>
                        <input type="number" class="form-control" id="newQuantity" 
                               name="quantity" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="updateReason" class="form-label">Reason for Update</label>
                        <select class="form-select" id="updateReason" name="reason">
                            <option value="stock_in">Stock In</option>
                            <option value="stock_out">Stock Out</option>
                            <option value="adjustment">Inventory Adjustment</option>
                            <option value="damaged">Damaged Items</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Update Quantity</button>
                </div>
            </form>
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

@push('scripts')
<script>
    function showUpdateQuantityModal() {
        const modal = new bootstrap.Modal(document.getElementById('updateQuantityModal'));
        modal.show();
    }

    function confirmDelete(itemId, itemName) {
        document.getElementById('deleteItemName').textContent = itemName;
        document.getElementById('deleteForm').action = `/items/${itemId}`;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>
@endpush

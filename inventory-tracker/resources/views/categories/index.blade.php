@extends('layouts.inventory')

@section('title', 'Categories - Inventory Tracker')

@section('content')
<div class="container my-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-tags"></i> Categories</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="bi bi-plus-circle"></i> Add New Category
                </button>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="row">
        @forelse($categories as $category)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title text-primary">
                                    <i class="bi bi-tag"></i> {{ $category->name }}
                                </h5>
                                <p class="card-text text-muted">
                                    {{ $category->description ?: 'No description provided' }}
                                </p>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                        type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="editCategory({{ $category->id }})">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" 
                                           onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}')">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-primary fs-6">{{ $category->items_count }} items</span>
                            <a href="{{ route('items.index', ['category' => $category->id]) }}" 
                               class="btn btn-sm btn-outline-primary">
                                View Items
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-tags fs-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">No categories found</h5>
                    <p class="text-muted">Create your first category to organize your inventory items.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="bi bi-plus-circle"></i> Add First Category
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Categories Statistics -->
    @if($categories->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-bar-chart"></i> Category Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Category</th>
                                        <th>Items Count</th>
                                        <th>Total Quantity</th>
                                        <th>Low Stock Items</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">
                                                    <i class="bi bi-tag"></i> {{ $category->name }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-bold">{{ $category->items_count }}</span>
                                                @if($category->items_count == 0)
                                                    <small class="text-muted d-block">No items</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-bold text-primary">{{ number_format($category->total_quantity) }}</span>
                                                @if($category->total_quantity > 0)
                                                    <small class="text-muted d-block">
                                                        {{ number_format($category->total_quantity / ($category->items_count ?: 1), 1) }} avg per item
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($category->low_stock_count > 0)
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-exclamation-triangle"></i> {{ $category->low_stock_count }}
                                                    </span>
                                                @else
                                                    <span class="text-success">
                                                        <i class="bi bi-check-circle"></i> None
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('items.index', ['category' => $category->id]) }}" 
                                                       class="btn btn-outline-info" title="View Items">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <button class="btn btn-outline-warning" title="Edit"
                                                            onclick="editCategory({{ $category->id }})">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-outline-danger" title="Delete"
                                                            onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Category Name *</label>
                        <input type="text" class="form-control" id="categoryName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="categoryDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="categoryDescription" name="description" rows="3" placeholder="Optional description for this category"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editCategoryName" class="form-label">Category Name *</label>
                        <input type="text" class="form-control" id="editCategoryName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCategoryDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editCategoryDescription" name="description" rows="3" placeholder="Optional description for this category"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Category Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the category <strong id="deleteCategoryName"></strong>? 
                This action cannot be undone and will affect all items in this category.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteCategoryForm" method="POST" style="display: inline;">
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
    function editCategory(categoryId) {
        // Fetch category data and populate edit modal
        fetch(`/categories/${categoryId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data); // Debug log
            if (data.category) {
                // Populate the form fields with the category data
                document.getElementById('editCategoryName').value = data.category.name;
                document.getElementById('editCategoryDescription').value = data.category.description || '';
                
                // Set the form action to update this specific category
                document.getElementById('editCategoryForm').action = `/categories/${categoryId}`;
                
                // Show the modal
                const editModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
                editModal.show();
            } else {
                console.error('No category data in response:', data);
                alert('Error loading category data: Invalid response format');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert(`Error loading category data: ${error.message}`);
        });
    }

    function deleteCategory(categoryId, categoryName) {
        document.getElementById('deleteCategoryName').textContent = categoryName;
        document.getElementById('deleteCategoryForm').action = `/categories/${categoryId}`;
        
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteCategoryModal'));
        deleteModal.show();
    }
</script>
@endpush

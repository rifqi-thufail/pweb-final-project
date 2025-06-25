@extends('layouts.inventory')

@section('title', 'Inventory Management Tips - Inventory Tracker')

@section('content')
<div class="container my-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">
                <i class="bi bi-lightbulb text-warning"></i> Inventory Management Tips
            </h1>
            <p class="lead text-muted">
                Learn best practices for effective inventory management and organization.
            </p>
        </div>
    </div>

    <!-- Tips Sections -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Getting Started -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-play-circle"></i> Getting Started
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>
                                <i class="bi bi-1-circle text-primary"></i> Set Up Categories
                            </h6>
                            <p class="small text-muted">
                                Create meaningful categories that reflect your business needs. Common categories include:
                            </p>
                            <ul class="small">
                                <li>Electronics</li>
                                <li>Furniture</li>
                                <li>Stationery</li>
                                <li>Food & Beverages</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>
                                <i class="bi bi-2-circle text-primary"></i> Define Stock Levels
                            </h6>
                            <p class="small text-muted">
                                Establish minimum and maximum stock levels for each item to avoid stockouts and overstocking.
                            </p>
                            <div class="alert alert-info small">
                                <i class="bi bi-info-circle"></i> <strong>Tip:</strong> Use the 80/20 rule - 20% of items typically account for 80% of sales.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Best Practices -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-star"></i> Best Practices</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="bestPracticesAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                    <i class="bi bi-clock-history me-2"></i> Regular Audits
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#bestPracticesAccordion">
                                <div class="accordion-body">
                                    <p>Conduct regular physical inventory audits to ensure accuracy:</p>
                                    <ul>
                                        <li><strong>Weekly:</strong> Check high-value or fast-moving items</li>
                                        <li><strong>Monthly:</strong> Audit 25% of total inventory</li>
                                        <li><strong>Quarterly:</strong> Complete inventory count</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                    <i class="bi bi-diagram-3 me-2"></i> Organization Systems
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#bestPracticesAccordion">
                                <div class="accordion-body">
                                    <p>Implement systematic organization methods:</p>
                                    <ul>
                                        <li><strong>ABC Analysis:</strong> Categorize items by value and importance</li>
                                        <li><strong>FIFO (First In, First Out):</strong> For perishable items</li>
                                        <li><strong>Location Coding:</strong> Assign specific locations to items</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                    <i class="bi bi-graph-up me-2"></i> Data Analysis
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#bestPracticesAccordion">
                                <div class="accordion-body">
                                    <p>Use data to make informed decisions:</p>
                                    <ul>
                                        <li>Track inventory turnover rates</li>
                                        <li>Monitor seasonal trends</li>
                                        <li>Analyze slow-moving items</li>
                                        <li>Review supplier performance</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Common Mistakes -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-exclamation-triangle"></i> Common Mistakes to Avoid
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item border-0 px-0">
                                    <h6 class="text-danger">
                                        <i class="bi bi-x-circle"></i> Overstocking
                                    </h6>
                                    <small class="text-muted">Tying up cash in excess inventory</small>
                                </div>
                                <div class="list-group-item border-0 px-0">
                                    <h6 class="text-danger">
                                        <i class="bi bi-x-circle"></i> Poor Record Keeping
                                    </h6>
                                    <small class="text-muted">Inaccurate or outdated inventory data</small>
                                </div>
                                <div class="list-group-item border-0 px-0">
                                    <h6 class="text-danger">
                                        <i class="bi bi-x-circle"></i> Ignoring Trends
                                    </h6>
                                    <small class="text-muted">Not adjusting for seasonal variations</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item border-0 px-0">
                                    <h6 class="text-danger">
                                        <i class="bi bi-x-circle"></i> Manual Processes
                                    </h6>
                                    <small class="text-muted">Relying on spreadsheets instead of systems</small>
                                </div>
                                <div class="list-group-item border-0 px-0">
                                    <h6 class="text-danger">
                                        <i class="bi bi-x-circle"></i> No Backup Plans
                                    </h6>
                                    <small class="text-muted">Lack of contingency for supply disruptions</small>
                                </div>
                                <div class="list-group-item border-0 px-0">
                                    <h6 class="text-danger">
                                        <i class="bi bi-x-circle"></i> Inconsistent Naming
                                    </h6>
                                    <small class="text-muted">Different names for the same items</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technology Tips -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-gear"></i> Using This System Effectively
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center mb-3">
                                <i class="bi bi-speedometer2 fs-1 text-info"></i>
                                <h6>Dashboard</h6>
                                <p class="small text-muted">Monitor key metrics and get quick insights into your inventory status.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center mb-3">
                                <i class="bi bi-tags fs-1 text-success"></i>
                                <h6>Categories</h6>
                                <p class="small text-muted">Organize items logically with custom categories, colors, and icons.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center mb-3">
                                <i class="bi bi-search fs-1 text-warning"></i>
                                <h6>Search & Filter</h6>
                                <p class="small text-muted">Quickly find items using powerful search and filtering options.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Sidebar -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6><i class="bi bi-lightning"></i> Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('items.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Add New Item
                        </a>
                        <a href="{{ route('categories.index') }}" class="btn btn-success btn-sm">
                            <i class="bi bi-tags"></i> Manage Categories
                        </a>
                        <a href="{{ route('items.index', ['filter' => 'low_stock']) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-exclamation-triangle"></i> Check Low Stock
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-info btn-sm">
                            <i class="bi bi-speedometer2"></i> View Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6><i class="bi bi-bookmark"></i> Quick Tips</h6>
                </div>
                <div class="card-body">
                    <div class="small">
                        <div class="mb-3">
                            <strong>💡 Daily Habit:</strong> Check your dashboard each morning to stay on top of inventory levels.
                        </div>
                        <div class="mb-3">
                            <strong>🔍 Search Tip:</strong> Use the search bar to quickly find items by name or description.
                        </div>
                        <div class="mb-3">
                            <strong>📊 Analysis:</strong> Review the category charts to understand your inventory distribution.
                        </div>
                        <div class="mb-0">
                            <strong>⚠️ Alerts:</strong> Set up low stock alerts to avoid running out of critical items.
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6><i class="bi bi-question-circle"></i> Need Help?</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">
                        If you have questions about inventory management or need assistance with this system, 
                        feel free to reach out to our support team.
                    </p>
                    <div class="d-grid">
                        <button class="btn btn-outline-primary btn-sm" onclick="alert('Support feature coming soon!')">
                            <i class="bi bi-envelope"></i> Contact Support
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

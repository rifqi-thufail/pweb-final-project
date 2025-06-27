<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the dashboard.
     */
    public function index()
    {
        // Get total counts
        $totalItems = DB::table('items')->count();
        $totalCategories = DB::table('categories')->count();
        
        // Get low stock items (quantity <= 5)
        $lowStockCount = DB::table('items')->where('quantity', '<=', 5)->count();
        
        // Get total value (if you want to add price field later)
        $totalValue = 0; // Placeholder for now
        
        // Get items by category for chart
        $itemsByCategory = DB::table('items')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->select('categories.name as category_name', DB::raw('COUNT(*) as count'))
            ->groupBy('categories.id', 'categories.name')
            ->get();
        
        // Get recent items (last 5)
        $recentItems = DB::table('items')
            ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
            ->select('items.*', 'categories.name as category_name')
            ->orderBy('items.created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get low stock items for alerts
        $lowStockItems = DB::table('items')
            ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
            ->select('items.*', 'categories.name as category_name')
            ->where('items.quantity', '<=', 5)
            ->orderBy('items.quantity', 'asc')
            ->limit(5)
            ->get();
        
        // Get stock levels by category for chart
        $stockByCategory = DB::table('items')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->select('categories.name as category_name', DB::raw('SUM(items.quantity) as total_stock'))
            ->groupBy('categories.id', 'categories.name')
            ->get();

        // Prepare chart data
        $categoryChart = [
            'labels' => $itemsByCategory->pluck('category_name')->toArray() ?: ['No Data'],
            'data' => $itemsByCategory->pluck('count')->toArray() ?: [0]
        ];

        $stockChart = [
            'labels' => $stockByCategory->pluck('category_name')->toArray() ?: ['No Data'],
            'data' => $stockByCategory->pluck('total_stock')->toArray() ?: [0]
        ];

        return view('dashboard', compact(
            'totalItems',
            'totalCategories', 
            'lowStockCount',
            'totalValue',
            'recentItems',
            'lowStockItems',
            'categoryChart',
            'stockChart'
        ));
    }
}

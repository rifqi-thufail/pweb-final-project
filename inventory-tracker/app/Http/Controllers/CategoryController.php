<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of categories.
     */
    public function index()
    {
        // Get categories with item counts and statistics
        $categories = DB::table('categories')
            ->leftJoin('items', 'categories.id', '=', 'items.category_id')
            ->select(
                'categories.*', 
                DB::raw('COUNT(items.id) as items_count'),
                DB::raw('COALESCE(SUM(items.quantity), 0) as total_quantity'),
                DB::raw('COUNT(CASE WHEN items.quantity <= 5 AND items.quantity > 0 THEN 1 END) as low_stock_count')
            )
            ->groupBy('categories.id', 'categories.name', 'categories.description', 'categories.created_at', 'categories.updated_at')
            ->orderBy('categories.name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $categoryId = DB::table('categories')->insertGetId([
            'name' => $request->name,
            'description' => $request->description,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($request->ajax()) {
            $category = DB::table('categories')->where('id', $categoryId)->first();
            return response()->json([
                'success' => 'Category created successfully.',
                'category' => $category
            ]);
        }

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category.
     */
    public function show($id)
    {
        $category = DB::table('categories')->where('id', $id)->first();
        
        if (!$category) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Category not found'], 404);
            }
            abort(404);
        }

        // If this is an AJAX request, return JSON data
        if (request()->ajax()) {
            return response()->json(['category' => $category]);
        }

        // Get items in this category for the regular view
        $items = DB::table('items')
            ->leftJoin('users', 'items.user_id', '=', 'users.id')
            ->select('items.*', 'users.name as user_name')
            ->where('items.category_id', $id)
            ->orderBy('items.created_at', 'desc')
            ->get();

        return view('categories.show', compact('category', 'items'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit($id)
    {
        $category = DB::table('categories')->where('id', $id)->first();
        
        if (!$category) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Category not found'], 404);
            }
            abort(404);
        }

        if (request()->ajax()) {
            return response()->json(['category' => $category]);
        }

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updated = DB::table('categories')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'description' => $request->description,
                'updated_at' => now(),
            ]);

        if (!$updated) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Category not found'], 404);
            }
            abort(404);
        }

        if ($request->ajax()) {
            $category = DB::table('categories')->where('id', $id)->first();
            return response()->json([
                'success' => 'Category updated successfully.',
                'category' => $category
            ]);
        }

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category.
     */
    public function destroy($id)
    {
        // Check if category has items
        $itemCount = DB::table('items')->where('category_id', $id)->count();
        
        if ($itemCount > 0) {
            // Set category_id to null for all items in this category
            DB::table('items')
                ->where('category_id', $id)
                ->update(['category_id' => null]);
        }

        $deleted = DB::table('categories')->where('id', $id)->delete();

        if (!$deleted) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Category not found'], 404);
            }
            abort(404);
        }

        if (request()->ajax()) {
            return response()->json(['success' => 'Category deleted successfully.']);
        }

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully. Items in this category have been uncategorized.');
    }

    /**
     * Get category data for AJAX requests.
     */
    public function getData($id)
    {
        $category = DB::table('categories')->where('id', $id)->first();
        
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        return response()->json(['category' => $category]);
    }
}

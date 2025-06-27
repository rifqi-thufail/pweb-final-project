<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of items.
     */
    public function index(Request $request)
    {
        $query = DB::table('items')
            ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
            ->leftJoin('users', 'items.user_id', '=', 'users.id')
            ->select('items.*', 'categories.name as category_name', 'users.name as user_name');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('items.name', 'LIKE', "%{$search}%")
                  ->orWhere('items.description', 'LIKE', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('items.category_id', $request->category);
        }

        // Stock filter
        if ($request->filled('filter')) {
            switch ($request->filter) {
                case 'low_stock':
                    $query->where('items.quantity', '<=', 5);
                    break;
                case 'out_of_stock':
                    $query->where('items.quantity', '=', 0);
                    break;
                case 'in_stock':
                    $query->where('items.quantity', '>', 5);
                    break;
            }
        }

        $items = $query->orderBy('items.created_at', 'desc')->paginate(10);
        
        // Get categories for filter dropdown
        $categories = DB::table('categories')->orderBy('name')->get();

        return view('items.index', compact('items', 'categories'));
    }

    /**
     * Show the form for creating a new item.
     */
    public function create()
    {
        $categories = DB::table('categories')->orderBy('name')->get();
        return view('items.create', compact('categories'));
    }

    /**
     * Store a newly created item.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'added_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::table('items')->insert([
            'name' => $request->name,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
            'added_date' => $request->added_date,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('items.index')
            ->with('success', 'Item created successfully.');
    }

    /**
     * Display the specified item.
     */
    public function show($id)
    {
        $item = DB::table('items')
            ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
            ->leftJoin('users', 'items.user_id', '=', 'users.id')
            ->select('items.*', 'categories.name as category_name', 'users.name as user_name')
            ->where('items.id', $id)
            ->first();

        if (!$item) {
            abort(404);
        }

        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit($id)
    {
        $item = DB::table('items')->where('id', $id)->first();
        
        if (!$item) {
            abort(404);
        }

        $categories = DB::table('categories')->orderBy('name')->get();
        
        return view('items.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified item.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'added_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updated = DB::table('items')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'description' => $request->description,
                'quantity' => $request->quantity,
                'category_id' => $request->category_id,
                'added_date' => $request->added_date,
                'updated_at' => now(),
            ]);

        if (!$updated) {
            abort(404);
        }

        return redirect()->route('items.index')
            ->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified item.
     */
    public function destroy($id)
    {
        $deleted = DB::table('items')->where('id', $id)->delete();

        if (!$deleted) {
            abort(404);
        }

        return redirect()->route('items.index')
            ->with('success', 'Item deleted successfully.');
    }

    /**
     * Update item quantity via AJAX.
     */
    public function updateQuantity(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid quantity'], 400);
        }

        $updated = DB::table('items')
            ->where('id', $id)
            ->update([
                'quantity' => $request->quantity,
                'updated_at' => now(),
            ]);

        if (!$updated) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        return response()->json(['success' => 'Quantity updated successfully']);
    }
}

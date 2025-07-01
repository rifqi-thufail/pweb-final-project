<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TipsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Authentication Routes
Auth::routes();

// Root route - always show landing page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Protected Routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard Route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home'); // Keep for compatibility

    // Items Routes
    Route::resource('items', ItemController::class);
    Route::patch('/items/{item}/quantity', [ItemController::class, 'updateQuantity'])->name('items.update-quantity');
    Route::get('/items-expiring', [ItemController::class, 'expiring'])->name('items.expiring');
    Route::get('/api/items/expiring-count', [ItemController::class, 'expiringCount'])->name('api.items.expiring-count');

    // Categories Routes
    Route::resource('categories', CategoryController::class);

    // Tips Route (Static Page)
    Route::get('/tips', [TipsController::class, 'index'])->name('tips');
});

<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\GoodsController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/user', [AuthController::class, 'user'])->name('user');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Inventory routes (for all authenticated users)
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/receipt', [InventoryController::class, 'printReceipt'])->name('inventory.receipt');
    Route::post('/inventory/receipt/custom', [InventoryController::class, 'printCustomReceipt'])->name('inventory.receipt.custom');
    Route::get('/inventory/receipt/{receiptId}', [InventoryController::class, 'showReceipt'])->name('inventory.receipt.show');
    Route::get('/inventory/vendor/{vendorId}/goods', [InventoryController::class, 'getVendorGoods'])->name('inventory.vendor.goods');
    
    // Vendor goods management routes
    Route::prefix('vendor/goods')->group(function () {
        Route::get('/', [GoodsController::class, 'index'])->name('vendor.goods.index');
        Route::get('/create', [GoodsController::class, 'create'])->name('vendor.goods.create');
        Route::post('/', [GoodsController::class, 'store'])->name('vendor.goods.store');
        Route::get('/{good}/edit', [GoodsController::class, 'edit'])->name('vendor.goods.edit');
        Route::put('/{good}', [GoodsController::class, 'update'])->name('vendor.goods.update');
        Route::delete('/{good}', [GoodsController::class, 'destroy'])->name('vendor.goods.destroy');
    });
    
    // Category routes
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
});
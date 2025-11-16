<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Inventory\CategoryController;
use App\Http\Controllers\Inventory\GoodsController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Admin\AdminController;

Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

// Admin routes
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('admin.dashboard');
    
    // Stats endpoint
    Route::get('/stats', [AdminController::class, 'getStats'])->name('admin.stats');
    
    // Users management
    Route::get('/users', function () {
        return Inertia::render('Admin/Users');
    })->name('admin.users');
    Route::get('/users/list', [AdminController::class, 'getUsers'])->name('admin.users.list');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    
    // Vendors management
    Route::get('/vendors', function () {
        return Inertia::render('Admin/Vendors');
    })->name('admin.vendors');
    Route::get('/vendors/list', [AdminController::class, 'getVendors'])->name('admin.vendors.list');
    Route::post('/vendors', [AdminController::class, 'storeVendor'])->name('admin.vendors.store');
    Route::put('/vendors/{id}', [AdminController::class, 'updateVendor'])->name('admin.vendors.update');
    Route::delete('/vendors/{id}', [AdminController::class, 'deleteVendor'])->name('admin.vendors.delete');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
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
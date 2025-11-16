<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

// Admin API routes
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/stats', [AdminController::class, 'getStats']);
    
    // Users management
    Route::get('/users', [AdminController::class, 'getUsers']);
    Route::post('/users', [AdminController::class, 'storeUser']);
    Route::put('/users/{id}', [AdminController::class, 'updateUser']);
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
    
    // Vendors management
    Route::get('/vendors', [AdminController::class, 'getVendors']);
    Route::post('/vendors', [AdminController::class, 'storeVendor']);
    Route::put('/vendors/{id}', [AdminController::class, 'updateVendor']);
    Route::delete('/vendors/{id}', [AdminController::class, 'deleteVendor']);
});

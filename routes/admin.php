<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TrendingProductController;
use App\Modules\User\Controllers\UserController;
use App\Modules\User\Controllers\RoleController;
use App\Modules\Ecommerce\Brand\Controllers\BrandController;
use App\Modules\Ecommerce\Order\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use Illuminate\Support\Facades\Route;

/**
 * ModuleName: Admin Panel
 * Purpose: Admin routes for dashboard, user, role, category, and brand management
 * 
 * @author AI Assistant
 * @date 2025-11-04
 */

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management Routes
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
        ->name('users.toggle-status');

    // Role Management Routes
    Route::resource('roles', RoleController::class);
    
    // Category Management Routes
    Route::resource('categories', CategoryController::class);
    Route::post('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])
        ->name('categories.toggle-status');
    Route::post('categories/{category}/duplicate', [CategoryController::class, 'duplicate'])
        ->name('categories.duplicate');
    
    // Brand Management Routes
    Route::resource('brands', BrandController::class);
    Route::post('brands/{brand}/toggle-status', [BrandController::class, 'toggleStatus'])
        ->name('brands.toggle-status');
    Route::post('brands/{brand}/toggle-featured', [BrandController::class, 'toggleFeatured'])
        ->name('brands.toggle-featured');
    Route::post('brands/{brand}/duplicate', [BrandController::class, 'duplicate'])
        ->name('brands.duplicate');
    
    // Order Management Routes (explicit routes to avoid authorization issues)
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::post('orders/{order}/update-status', [OrderController::class, 'updateStatus'])
        ->name('orders.update-status');
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])
        ->name('orders.cancel');
    Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])
        ->name('orders.invoice');
    
    // Customer Management Routes
    Route::post('customers/{id}/update-info', [CustomerController::class, 'updateInfo'])
        ->name('customers.update-info');
    
    // Trending Products Management Routes
    Route::get('trending-products/search', [TrendingProductController::class, 'searchProducts'])->name('trending-products.search');
    Route::get('trending-products', [TrendingProductController::class, 'index'])->name('trending-products.index');
    Route::post('trending-products', [TrendingProductController::class, 'store'])->name('trending-products.store');
    Route::post('trending-products/update-order', [TrendingProductController::class, 'updateOrder'])->name('trending-products.update-order');
    Route::post('trending-products/{trendingProduct}/toggle-status', [TrendingProductController::class, 'toggleStatus'])->name('trending-products.toggle-status');
    Route::delete('trending-products/{trendingProduct}', [TrendingProductController::class, 'destroy'])->name('trending-products.destroy');
});

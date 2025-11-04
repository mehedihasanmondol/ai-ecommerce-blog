<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Modules\User\Controllers\UserController;
use App\Modules\User\Controllers\RoleController;
use App\Modules\Ecommerce\Category\Controllers\CategoryController;
use App\Modules\Ecommerce\Brand\Controllers\BrandController;
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
});

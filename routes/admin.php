<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Modules\User\Controllers\UserController;
use App\Modules\User\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

/**
 * ModuleName: Admin Panel
 * Purpose: Admin routes for dashboard, user and role management
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
});

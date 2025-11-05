<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Modules\Ecommerce\Order\Controllers\Customer\OrderController as CustomerOrderController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Dashboard (Protected)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }
        return view('admin.dashboard');
    })->name('dashboard');

    // Product Management Routes
    Route::get('/products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
    Route::get('/products/{product}/edit', [\App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
    Route::get('/products/{product}/images', function(\App\Modules\Ecommerce\Product\Models\Product $product) {
        return view('admin.product.images', compact('product'));
    })->name('products.images');
    
    // Product Attributes Routes
    Route::resource('attributes', \App\Modules\Ecommerce\Product\Controllers\AttributeController::class)->except(['show']);
});

// Customer Order Routes (Protected)
Route::middleware(['auth'])->prefix('my')->name('customer.')->group(function () {
    Route::get('orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/cancel', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('orders/{order}/invoice', [CustomerOrderController::class, 'invoice'])->name('orders.invoice');
});

// Public Order Tracking
Route::get('track-order', [CustomerOrderController::class, 'track'])->name('orders.track');
Route::post('track-order', [CustomerOrderController::class, 'track']);

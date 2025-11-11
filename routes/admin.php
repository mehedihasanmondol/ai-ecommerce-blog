<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TrendingProductController;
use App\Http\Controllers\Admin\BestSellerProductController;
use App\Http\Controllers\Admin\NewArrivalProductController;
use App\Http\Controllers\Admin\FooterManagementController;
use App\Http\Controllers\Admin\ProductQuestionController;
use App\Http\Controllers\Admin\ReviewController;
use App\Modules\User\Controllers\UserController;
use App\Modules\User\Controllers\RoleController;
use App\Modules\Ecommerce\Brand\Controllers\BrandController;
use App\Modules\Ecommerce\Order\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DeliveryZoneController;
use App\Http\Controllers\Admin\DeliveryMethodController;
use App\Http\Controllers\Admin\DeliveryRateController;
use App\Modules\Blog\Controllers\Admin\TickMarkController;
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
    Route::get('orders/{order}/edit', function (\App\Modules\Ecommerce\Order\Models\Order $order) {
        return view('admin.orders.edit-livewire', compact('order'));
    })->name('orders.edit');
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
    
    // Best Seller Products Management Routes
    Route::get('best-seller-products/search', [BestSellerProductController::class, 'searchProducts'])->name('best-seller-products.search');
    Route::get('best-seller-products', [BestSellerProductController::class, 'index'])->name('best-seller-products.index');
    Route::post('best-seller-products', [BestSellerProductController::class, 'store'])->name('best-seller-products.store');
    Route::post('best-seller-products/update-order', [BestSellerProductController::class, 'updateOrder'])->name('best-seller-products.update-order');
    Route::post('best-seller-products/{bestSellerProduct}/toggle-status', [BestSellerProductController::class, 'toggleStatus'])->name('best-seller-products.toggle-status');
    Route::delete('best-seller-products/{bestSellerProduct}', [BestSellerProductController::class, 'destroy'])->name('best-seller-products.destroy');
    
    // New Arrival Products Management Routes
    Route::get('new-arrival-products/search', [NewArrivalProductController::class, 'searchProducts'])->name('new-arrival-products.search');
    Route::get('new-arrival-products', [NewArrivalProductController::class, 'index'])->name('new-arrival-products.index');
    Route::post('new-arrival-products', [NewArrivalProductController::class, 'store'])->name('new-arrival-products.store');
    Route::post('new-arrival-products/update-order', [NewArrivalProductController::class, 'updateOrder'])->name('new-arrival-products.update-order');
    Route::post('new-arrival-products/{newArrivalProduct}/toggle-status', [NewArrivalProductController::class, 'toggleStatus'])->name('new-arrival-products.toggle-status');
    Route::delete('new-arrival-products/{newArrivalProduct}', [NewArrivalProductController::class, 'destroy'])->name('new-arrival-products.destroy');
    
    // Product Q&A Management Routes
    Route::resource('product-questions', ProductQuestionController::class);
    Route::post('questions/{id}/approve', [ProductQuestionController::class, 'approve'])->name('questions.approve');
    Route::post('questions/{id}/reject', [ProductQuestionController::class, 'reject'])->name('questions.reject');
    Route::post('answers/{id}/approve', [ProductQuestionController::class, 'approveAnswer'])->name('answers.approve');
    Route::post('answers/{id}/reject', [ProductQuestionController::class, 'rejectAnswer'])->name('answers.reject');
    Route::post('answers/{id}/best', [ProductQuestionController::class, 'markBestAnswer'])->name('answers.best');
    
    // Product Review Management Routes
    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('reviews/pending', [ReviewController::class, 'pending'])->name('reviews.pending');
    Route::get('reviews/{id}', [ReviewController::class, 'show'])->name('reviews.show');
    Route::post('reviews/{id}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('reviews/{id}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('reviews/bulk-approve', [ReviewController::class, 'bulkApprove'])->name('reviews.bulk-approve');
    Route::post('reviews/bulk-delete', [ReviewController::class, 'bulkDelete'])->name('reviews.bulk-delete');
    
    // Footer Management Routes
    Route::get('footer-management', [FooterManagementController::class, 'index'])->name('footer-management.index');
    Route::post('footer-management/settings', [FooterManagementController::class, 'updateSettings'])->name('footer-management.update-settings');
    Route::post('footer-management/links', [FooterManagementController::class, 'storeLink'])->name('footer-management.store-link');
    Route::put('footer-management/links/{link}', [FooterManagementController::class, 'updateLink'])->name('footer-management.update-link');
    Route::delete('footer-management/links/{link}', [FooterManagementController::class, 'deleteLink'])->name('footer-management.delete-link');
    Route::post('footer-management/blog-posts', [FooterManagementController::class, 'storeBlogPost'])->name('footer-management.store-blog');
    Route::delete('footer-management/blog-posts/{blogPost}', [FooterManagementController::class, 'deleteBlogPost'])->name('footer-management.delete-blog');
    
    // Blog Tick Marks Management Routes
    Route::prefix('blog')->name('blog.')->group(function () {
        Route::resource('tick-marks', TickMarkController::class);
        Route::patch('tick-marks/{tick_mark}/toggle-active', [TickMarkController::class, 'toggleActive'])
            ->name('tick-marks.toggle-active');
        Route::post('tick-marks/update-sort-order', [TickMarkController::class, 'updateSortOrder'])
            ->name('tick-marks.update-sort-order');
    });
    
    // Delivery Management Routes
    Route::prefix('delivery')->name('delivery.')->group(function () {
        // Delivery Zones
        Route::resource('zones', DeliveryZoneController::class);
        Route::post('zones/{zone}/toggle-status', [DeliveryZoneController::class, 'toggleStatus'])
            ->name('zones.toggle-status');
        
        // Delivery Methods
        Route::resource('methods', DeliveryMethodController::class);
        Route::post('methods/{method}/toggle-status', [DeliveryMethodController::class, 'toggleStatus'])
            ->name('methods.toggle-status');
        
        // Delivery Rates
        Route::resource('rates', DeliveryRateController::class);
        Route::post('rates/{rate}/toggle-status', [DeliveryRateController::class, 'toggleStatus'])
            ->name('rates.toggle-status');
    });
});

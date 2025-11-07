<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Blog\Controllers\Admin\PostController;
use App\Modules\Blog\Controllers\Admin\BlogCategoryController;
use App\Modules\Blog\Controllers\Admin\TagController;
use App\Modules\Blog\Controllers\Admin\CommentController;
use App\Modules\Blog\Controllers\Frontend\BlogController;

/**
 * Blog Management Routes
 * 
 * Admin Routes: /admin/blog/*
 * Frontend Routes: /blog/* and /{slug}
 * 
 * Following .windsurfrules:
 * - Blog posts accessible at: domain.com/{slug} (NO /blog prefix)
 * - Category archives: domain.com/blog/category/{slug}
 * - Tag archives: domain.com/blog/tag/{slug}
 */

// ============================================
// ADMIN BLOG ROUTES
// ============================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    
    Route::prefix('blog')->name('blog.')->group(function () {
        
        // Posts Management
        Route::resource('posts', PostController::class);
        Route::post('posts/{post}/publish', [PostController::class, 'publish'])->name('posts.publish');
        
        // TinyMCE Image Upload
        Route::post('upload-image', [PostController::class, 'uploadImage'])->name('upload-image');
        
        // Categories Management
        Route::resource('categories', BlogCategoryController::class)->except(['show']);
        
        // Tags Management
        Route::resource('tags', TagController::class)->except(['show']);
        
        // Comments Moderation
        Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
        Route::post('comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
        Route::post('comments/{comment}/spam', [CommentController::class, 'spam'])->name('comments.spam');
        Route::post('comments/{comment}/trash', [CommentController::class, 'trash'])->name('comments.trash');
        Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    });
});

// ============================================
// FRONTEND BLOG ROUTES
// ============================================

// Blog Index (listing page)
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');

// Category Archive
Route::get('/blog/category/{slug}', [BlogController::class, 'category'])->name('blog.category');

// Tag Archive
Route::get('/blog/tag/{slug}', [BlogController::class, 'tag'])->name('blog.tag');

// Search Results
Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');

// Comment Submission
Route::post('/blog/{post}/comments', [BlogController::class, 'storeComment'])->name('blog.comments.store');

// ============================================
// SINGLE POST ROUTE (NO /blog PREFIX)
// ============================================
// NOTE: This route should be added to web.php at the END
// to avoid conflicts with other routes
// Route::get('/{slug}', [BlogController::class, 'show'])->name('blog.show');

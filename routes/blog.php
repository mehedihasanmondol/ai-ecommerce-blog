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
        
        // Posts Management (Livewire)
        Route::get('posts', function() {
            return view('admin.blog.posts.index-livewire');
        })->name('posts.index');
        Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
        Route::post('posts', [PostController::class, 'store'])->name('posts.store');
        Route::get('posts/{post}', [PostController::class, 'show'])->name('posts.show');
        Route::get('posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('posts/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
        Route::post('posts/{post}/publish', [PostController::class, 'publish'])->name('posts.publish');
        
        // TinyMCE Image Upload
        Route::post('upload-image', [PostController::class, 'uploadImage'])->name('upload-image');
        
        // Tick Mark Management
        Route::get('tick-marks/stats', [PostController::class, 'tickMarkStats'])->name('tick-marks.stats');
        Route::post('posts/{post}/toggle-verification', [PostController::class, 'toggleVerification'])->name('posts.toggle-verification');
        Route::post('posts/{post}/toggle-editor-choice', [PostController::class, 'toggleEditorChoice'])->name('posts.toggle-editor-choice');
        Route::post('posts/{post}/toggle-trending', [PostController::class, 'toggleTrending'])->name('posts.toggle-trending');
        Route::post('posts/{post}/toggle-premium', [PostController::class, 'togglePremium'])->name('posts.toggle-premium');
        Route::post('posts/bulk-update-tick-marks', [PostController::class, 'bulkUpdateTickMarks'])->name('posts.bulk-update-tick-marks');
        
        // Categories Management
        Route::resource('categories', BlogCategoryController::class)->except(['show']);
        
        // Tags Management
        Route::resource('tags', TagController::class)->except(['show']);
        
        // Comments Moderation (Livewire)
        Route::get('comments', function() {
            return view('admin.blog.comments.index-livewire');
        })->name('comments.index');
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

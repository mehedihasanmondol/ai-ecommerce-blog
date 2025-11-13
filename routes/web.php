<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CouponController;
use App\Modules\Ecommerce\Order\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\PaymentController;

// Public Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', \App\Livewire\Shop\ProductList::class)->name('shop');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/remove-multiple', [CartController::class, 'removeMultiple'])->name('cart.remove-multiple');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/calculate-shipping', [CheckoutController::class, 'calculateShipping'])->name('checkout.calculate-shipping');
Route::get('/checkout/zone-methods', [CheckoutController::class, 'getZoneMethods'])->name('checkout.zone-methods');
Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.place-order');

// Wishlist Routes
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');
Route::post('/wishlist/move-to-cart', [WishlistController::class, 'moveToCart'])->name('wishlist.move-to-cart');
Route::get('/wishlist/count', [WishlistController::class, 'count'])->name('wishlist.count');

// Coupon Routes
Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');

// Promotional Banner Routes
Route::post('/promo-banners/dismiss', function(\Illuminate\Http\Request $request) {
    $dismissedBanners = session()->get('dismissed_banners', []);
    $dismissedBanners[] = $request->input('banner_id');
    session()->put('dismissed_banners', $dismissedBanners);
    return response()->json(['success' => true]);
})->name('promo-banners.dismiss');

// Public Category Routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', \App\Livewire\Shop\ProductList::class)->name('categories.show');

// Public Brand Routes
Route::get('/brands', [\App\Http\Controllers\BrandController::class, 'index'])->name('brands.index');
Route::get('/brands/{slug}', \App\Livewire\Shop\ProductList::class)->name('brands.show');

// Authentication Routes (must be before catch-all product route)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Search Routes
Route::get('/search', \App\Livewire\Search\SearchResults::class)->name('search.results');

// Blog Routes (must be before catch-all product route)
require __DIR__.'/blog.php';

// Public Product and Blog Post Routes (must be last to avoid conflicts)
// This route handles both products and blog posts by slug
// Named 'products.show' as primary, but works for both products and blog posts
Route::get('/{slug}', function($slug) {
    // Try to find product first
    $product = \App\Modules\Ecommerce\Product\Models\Product::where('slug', $slug)->first();
    if ($product) {
        return app(\App\Http\Controllers\ProductController::class)->show($slug);
    }
    
    // Then try to find blog post
    $post = \App\Modules\Blog\Models\Post::where('slug', $slug)->published()->first();
    if ($post) {
        return app(\App\Modules\Blog\Controllers\Frontend\BlogController::class)->show($slug);
    }
    
    // Neither found
    abort(404);
})->where('slug', '[a-z0-9-]+')->name('products.show');

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
    
    // Homepage Settings Routes
    Route::get('/homepage-settings', [\App\Http\Controllers\Admin\HomepageSettingController::class, 'index'])->name('homepage-settings.index');
    Route::put('/homepage-settings', [\App\Http\Controllers\Admin\HomepageSettingController::class, 'update'])->name('homepage-settings.update');
    
    // Hero Slider Routes
    Route::post('/homepage-settings/slider', [\App\Http\Controllers\Admin\HomepageSettingController::class, 'storeSlider'])->name('homepage-settings.slider.store');
    Route::put('/homepage-settings/slider/{slider}', [\App\Http\Controllers\Admin\HomepageSettingController::class, 'updateSlider'])->name('homepage-settings.slider.update');
    Route::delete('/homepage-settings/slider/{slider}', [\App\Http\Controllers\Admin\HomepageSettingController::class, 'destroySlider'])->name('homepage-settings.slider.destroy');
    Route::post('/homepage-settings/slider/reorder', [\App\Http\Controllers\Admin\HomepageSettingController::class, 'reorderSliders'])->name('homepage-settings.slider.reorder');
    
    // Secondary Menu Routes
    Route::get('/secondary-menu', [\App\Http\Controllers\Admin\SecondaryMenuController::class, 'index'])->name('secondary-menu.index');
    Route::post('/secondary-menu', [\App\Http\Controllers\Admin\SecondaryMenuController::class, 'store'])->name('secondary-menu.store');
    Route::put('/secondary-menu/{secondaryMenu}', [\App\Http\Controllers\Admin\SecondaryMenuController::class, 'update'])->name('secondary-menu.update');
    Route::delete('/secondary-menu/{secondaryMenu}', [\App\Http\Controllers\Admin\SecondaryMenuController::class, 'destroy'])->name('secondary-menu.destroy');
    Route::post('/secondary-menu/reorder', [\App\Http\Controllers\Admin\SecondaryMenuController::class, 'reorder'])->name('secondary-menu.reorder');
    
    // Sale Offers Routes
    Route::get('/sale-offers', [\App\Http\Controllers\Admin\SaleOfferController::class, 'index'])->name('sale-offers.index');
    Route::post('/sale-offers', [\App\Http\Controllers\Admin\SaleOfferController::class, 'store'])->name('sale-offers.store');
    Route::delete('/sale-offers/{saleOffer}', [\App\Http\Controllers\Admin\SaleOfferController::class, 'destroy'])->name('sale-offers.destroy');
    Route::post('/sale-offers/reorder', [\App\Http\Controllers\Admin\SaleOfferController::class, 'reorder'])->name('sale-offers.reorder');
    Route::patch('/sale-offers/{saleOffer}/toggle', [\App\Http\Controllers\Admin\SaleOfferController::class, 'toggleStatus'])->name('sale-offers.toggle');
    
    // Category Management Routes
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    
    // Payment Gateway Management Routes
    Route::get('/payment-gateways', [\App\Http\Controllers\Admin\PaymentGatewayController::class, 'index'])->name('payment-gateways.index');
    Route::get('/payment-gateways/{gateway}/edit', [\App\Http\Controllers\Admin\PaymentGatewayController::class, 'edit'])->name('payment-gateways.edit');
    Route::put('/payment-gateways/{gateway}', [\App\Http\Controllers\Admin\PaymentGatewayController::class, 'update'])->name('payment-gateways.update');
    Route::patch('/payment-gateways/{gateway}/toggle', [\App\Http\Controllers\Admin\PaymentGatewayController::class, 'toggleStatus'])->name('payment-gateways.toggle');
});

// Customer Dashboard and Profile Routes (Protected)
Route::middleware(['auth'])->prefix('my')->name('customer.')->group(function () {
    // Dashboard
    Route::get('dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
    
    // Profile Management
    Route::get('profile', [CustomerController::class, 'profile'])->name('profile');
    Route::put('profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
    
    // Address Management
    Route::get('addresses', [CustomerController::class, 'addresses'])->name('addresses.index');
    
    // Account Settings
    Route::get('settings', [CustomerController::class, 'settings'])->name('settings');
    Route::put('password', [CustomerController::class, 'updatePassword'])->name('password.update');
    Route::put('preferences', [CustomerController::class, 'updatePreferences'])->name('preferences.update');
    Route::delete('account', [CustomerController::class, 'deleteAccount'])->name('account.delete');
    
    // Order Management
    Route::get('orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/cancel', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('orders/{order}/invoice', [CustomerOrderController::class, 'invoice'])->name('orders.invoice');
});

// Public Order Tracking
Route::get('track-order', [CustomerOrderController::class, 'track'])->name('orders.track');
Route::post('track-order', [CustomerOrderController::class, 'track']);

// Payment Routes
Route::post('/payment/initiate/{order}', [PaymentController::class, 'initiate'])->name('payment.initiate');
Route::get('/payment/bkash/callback', [PaymentController::class, 'bkashCallback'])->name('payment.bkash.callback');
Route::post('/payment/bkash/callback', [PaymentController::class, 'bkashCallback']);
Route::get('/payment/nagad/callback', [PaymentController::class, 'nagadCallback'])->name('payment.nagad.callback');
Route::post('/payment/nagad/callback', [PaymentController::class, 'nagadCallback']);
Route::post('/payment/sslcommerz/success', [PaymentController::class, 'sslcommerzSuccess'])->name('payment.sslcommerz.success');
Route::post('/payment/sslcommerz/fail', [PaymentController::class, 'sslcommerzFail'])->name('payment.sslcommerz.fail');
Route::post('/payment/sslcommerz/cancel', [PaymentController::class, 'sslcommerzCancel'])->name('payment.sslcommerz.cancel');

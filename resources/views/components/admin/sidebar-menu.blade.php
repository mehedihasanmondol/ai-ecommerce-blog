{{--
/**
 * Admin Sidebar Menu Component
 * Purpose: Reusable sidebar menu with permission-based visibility
 *
 * @category Components
 * @package Admin
 * @created 2025-11-17
 * @updated 2025-12-10
 */
--}}

@props(['mobile' => false])

{{-- Dashboard --}}
<a href="{{ route('admin.dashboard') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-tachometer-alt w-5 mr-3"></i>
    <span>Dashboard</span>
</a>

{{-- User Management Section --}}
@if(auth()->user()->hasPermission('users.view'))
<div class="pt-4 pb-2">
    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">User Management</p>
</div>

<a href="{{ route('admin.users.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-users w-5 mr-3"></i>
    <span>Users</span>
    @if(request()->routeIs('admin.users.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>

@if(auth()->user()->hasPermission('roles.view'))
<a href="{{ route('admin.roles.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.roles.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-shield-alt w-5 mr-3"></i>
    <span>Roles & Permissions</span>
    @if(request()->routeIs('admin.roles.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif

@if(auth()->user()->hasPermission('email-preferences.view'))
<a href="{{ route('admin.email-preferences.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.email-preferences.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-envelope-open-text w-5 mr-3"></i>
    <span>Email Preferences</span>
    @if(request()->routeIs('admin.email-preferences.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif
@endif

{{-- E-commerce Section --}}
@if(auth()->user()->hasPermission('products.view'))
<div class="pt-4 pb-2">
    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">E-commerce</p>
</div>

<a href="{{ route('admin.products.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-box w-5 mr-3"></i>
    <span>Products</span>
    @if(request()->routeIs('admin.products.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>

@if(auth()->user()->hasPermission('orders.view'))
<a href="{{ route('admin.orders.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-shopping-cart w-5 mr-3"></i>
    <span>Orders</span>
    @if(request()->routeIs('admin.orders.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif

{{-- Reports Section --}}
@if(auth()->user()->hasPermission('reports.view'))
<div x-data="{ open: {{ request()->routeIs('admin.reports.*') ? 'true' : 'false' }} }"
    class="space-y-1">
    <button @click="open = !open"
        class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
        <i class="fas fa-chart-line w-5 mr-3"></i>
        <span class="flex-1 text-left">Reports & Analytics</span>
        <i class="fas fa-chevron-down text-xs transition-transform"
            :class="open ? 'rotate-180' : ''"></i>
    </button>

    <div x-show="open" x-collapse class="ml-4 space-y-1 border-l-2 border-gray-200 pl-2">
        <a href="{{ route('admin.reports.index') }}"
            class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.reports.index') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            <i class="fas fa-tachometer-alt w-4 mr-2 text-xs"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.reports.sales') }}"
            class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.reports.sales') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            <i class="fas fa-dollar-sign w-4 mr-2 text-xs"></i>
            <span>Sales Report</span>
        </a>
        <a href="{{ route('admin.reports.products') }}"
            class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.reports.products') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            <i class="fas fa-box w-4 mr-2 text-xs"></i>
            <span>Product Performance</span>
        </a>
        <a href="{{ route('admin.reports.inventory') }}"
            class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.reports.inventory') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            <i class="fas fa-warehouse w-4 mr-2 text-xs"></i>
            <span>Inventory Report</span>
        </a>
        <a href="{{ route('admin.reports.customers') }}"
            class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.reports.customers') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            <i class="fas fa-users w-4 mr-2 text-xs"></i>
            <span>Customer Report</span>
        </a>
        <a href="{{ route('admin.reports.delivery') }}"
            class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.reports.delivery') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            <i class="fas fa-truck w-4 mr-2 text-xs"></i>
            <span>Delivery Report</span>
        </a>
    </div>
</div>
@endif

@if(auth()->user()->hasPermission('categories.view'))
<a href="{{ route('admin.categories.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-tags w-5 mr-3"></i>
    <span>Categories</span>
    @if(request()->routeIs('admin.categories.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif

@if(auth()->user()->hasPermission('brands.view'))
<a href="{{ route('admin.brands.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.brands.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-copyright w-5 mr-3"></i>
    <span>Brands</span>
    @if(request()->routeIs('admin.brands.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif

@if(auth()->user()->hasPermission('attributes.view'))
<a href="{{ route('admin.attributes.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.attributes.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-sliders-h w-5 mr-3"></i>
    <span>Attributes</span>
    @if(request()->routeIs('admin.attributes.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif

@if(auth()->user()->hasPermission('questions.view'))
<a href="{{ route('admin.product-questions.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.product-questions.*') || request()->routeIs('admin.questions.*') || request()->routeIs('admin.answers.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-question-circle w-5 mr-3"></i>
    <span>Product Q&A</span>
    @if(request()->routeIs('admin.product-questions.*') || request()->routeIs('admin.questions.*') || request()->routeIs('admin.answers.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif

@if(auth()->user()->hasPermission('reviews.view'))
<a href="{{ route('admin.reviews.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.reviews.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-star w-5 mr-3"></i>
    <span>Product Reviews</span>
    @if(request()->routeIs('admin.reviews.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif
@endif

@if(auth()->user()->hasPermission('coupons.view'))
<a href="{{ route('admin.coupons.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.coupons.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-ticket-alt w-5 mr-3"></i>
    <span>Coupons</span>
    @if(request()->routeIs('admin.coupons.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif

{{-- Delivery Section --}}
@if(auth()->user()->hasPermission('delivery-zones.view') || auth()->user()->hasPermission('delivery-methods.view') || auth()->user()->hasPermission('delivery-rates.view'))
<div class="pt-4 pb-2">
    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Delivery</p>
</div>

@if(auth()->user()->hasPermission('delivery-zones.view'))
<a href="{{ route('admin.delivery.zones.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.delivery.zones.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-map-marked-alt w-5 mr-3"></i>
    <span>Delivery Zones</span>
    @if(request()->routeIs('admin.delivery.zones.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif

@if(auth()->user()->hasPermission('delivery-methods.view'))
<a href="{{ route('admin.delivery.methods.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.delivery.methods.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-shipping-fast w-5 mr-3"></i>
    <span>Delivery Methods</span>
    @if(request()->routeIs('admin.delivery.methods.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif

@if(auth()->user()->hasPermission('delivery-rates.view'))
<a href="{{ route('admin.delivery.rates.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.delivery.rates.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-dollar-sign w-5 mr-3"></i>
    <span>Delivery Rates</span>
    @if(request()->routeIs('admin.delivery.rates.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif
@endif

{{-- Payments Section --}}
@if(auth()->user()->hasPermission('orders.view') || auth()->user()->hasPermission('payment-gateways.view'))
<div class="pt-4 pb-2">
    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Payments</p>
</div>

@if(auth()->user()->hasPermission('payment-gateways.view'))
<a href="{{ route('admin.payment-gateways.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.payment-gateways.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-credit-card w-5 mr-3"></i>
    <span>Payment Gateways</span>
    @if(request()->routeIs('admin.payment-gateways.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif
@endif

{{-- Inventory Section --}}
@if(auth()->user()->hasPermission('stock.view') || auth()->user()->hasPermission('warehouses.view') || auth()->user()->hasPermission('suppliers.view'))
<div class="pt-4 pb-2">
    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Inventory</p>
</div>

@if(auth()->user()->hasPermission('stock.view'))
<a href="{{ route('admin.stock.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.stock.*') || request()->routeIs('admin.warehouses.*') || request()->routeIs('admin.suppliers.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-boxes w-5 mr-3"></i>
    <span>Stock Management</span>
    @php
    try {
    $stockAlerts = \App\Modules\Stock\Models\StockAlert::where('status', 'pending')->count();
    } catch (\Exception $e) {
    $stockAlerts = 0;
    }
    @endphp
    @if($stockAlerts > 0)
    <span class="ml-auto text-xs bg-red-500 text-white px-2 py-1 rounded-full">{{ $stockAlerts }}</span>
    @endif
    @if(request()->routeIs('admin.stock.*') || request()->routeIs('admin.warehouses.*') || request()->routeIs('admin.suppliers.*'))
    <i class="fas fa-chevron-right {{ $stockAlerts > 0 ? '' : 'ml-auto' }} text-xs"></i>
    @endif
</a>
@endif

@if(auth()->user()->hasPermission('warehouses.view'))
<a href="{{ route('admin.warehouses.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.warehouses.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-warehouse w-5 mr-3"></i>
    <span>Warehouses</span>
    @if(request()->routeIs('admin.warehouses.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif

@if(auth()->user()->hasPermission('suppliers.view'))
<a href="{{ route('admin.suppliers.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.suppliers.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-truck w-5 mr-3"></i>
    <span>Suppliers</span>
    @if(request()->routeIs('admin.suppliers.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif

@if(auth()->user()->hasPermission('stock.view'))
<a href="{{ route('admin.stock.reports.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.stock.reports.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-chart-bar w-5 mr-3"></i>
    <span>Stock Reports</span>
    @if(request()->routeIs('admin.stock.reports.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif
@endif

{{-- Content Section --}}
@if(auth()->user()->hasPermission('homepage-settings.view'))
<div class="pt-4 pb-2">
    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Content</p>
</div>

<a href="{{ route('admin.homepage-settings.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.homepage-settings.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-home w-5 mr-3"></i>
    <span>Homepage Settings</span>
    @if(request()->routeIs('admin.homepage-settings.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>

@if(auth()->user()->hasPermission('secondary-menu.manage'))
<a href="{{ route('admin.secondary-menu.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.secondary-menu.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-bars w-5 mr-3"></i>
    <span>Secondary Menu</span>
    @if(request()->routeIs('admin.secondary-menu.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif

@if(auth()->user()->hasPermission('sale-offers.view'))
<a href="{{ route('admin.sale-offers.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.sale-offers.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-tag w-5 mr-3"></i>
    <span>Sale Offers</span>
    @if(request()->routeIs('admin.sale-offers.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif

@if(auth()->user()->hasPermission('trending-products.view'))
<a href="{{ route('admin.trending-products.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.trending-products.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-fire w-5 mr-3"></i>
    <span>Trending Products</span>
    @if(request()->routeIs('admin.trending-products.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif

@if(auth()->user()->hasPermission('best-sellers.view'))
<a href="{{ route('admin.best-seller-products.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.best-seller-products.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-medal w-5 mr-3"></i>
    <span>Best Sellers</span>
    @if(request()->routeIs('admin.best-seller-products.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif

@if(auth()->user()->hasPermission('new-arrivals.view'))
<a href="{{ route('admin.new-arrival-products.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.new-arrival-products.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-star w-5 mr-3"></i>
    <span>New Arrivals</span>
    @if(request()->routeIs('admin.new-arrival-products.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif

@if(auth()->user()->hasPermission('footer.view'))
<a href="{{ route('admin.footer-management.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.footer-management.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-shoe-prints w-5 mr-3"></i>
    <span>Footer Management</span>
    @if(request()->routeIs('admin.footer-management.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif
@endif

{{-- Blog Section --}}
@if(auth()->user()->hasPermission('posts.view'))
<div class="pt-4 pb-2">
    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Blog</p>
</div>

<a href="{{ route('admin.blog.posts.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.blog.posts.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-file-alt w-5 mr-3"></i>
    <span>Posts</span>
    @if(request()->routeIs('admin.blog.posts.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>

@if(auth()->user()->hasPermission('blog-categories.view'))
<a href="{{ route('admin.blog.categories.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.blog.categories.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-folder w-5 mr-3"></i>
    <span>Categories</span>
    @if(request()->routeIs('admin.blog.categories.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif

@if(auth()->user()->hasPermission('blog-tags.view'))
<a href="{{ route('admin.blog.tags.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.blog.tags.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-tag w-5 mr-3"></i>
    <span>Tags</span>
    @if(request()->routeIs('admin.blog.tags.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif

@if(auth()->user()->hasPermission('blog-comments.view'))
<a href="{{ route('admin.blog.comments.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.blog.comments.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-comments w-5 mr-3"></i>
    <span>Comments</span>
    @if(request()->routeIs('admin.blog.comments.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif
@endif

{{-- Advertisement Section --}}
@if(auth()->user()->hasPermission('advertisements.view'))
<div class="pt-4 pb-2">
    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Marketing</p>
</div>

<div x-data="{ open: {{ request()->routeIs('admin.advertisements.*') ? 'true' : 'false' }} }"
    class="space-y-1">
    <button @click="open = !open"
        class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.advertisements.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
        <i class="fas fa-ad w-5 mr-3"></i>
        <span class="flex-1 text-left">Advertisements</span>
        <i class="fas fa-chevron-down text-xs transition-transform"
            :class="open ? 'rotate-180' : ''"></i>
    </button>

    <div x-show="open" x-collapse class="ml-4 space-y-1 border-l-2 border-gray-200 pl-2">
        <a href="{{ route('admin.advertisements.campaigns.index') }}"
            class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.advertisements.campaigns.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            <i class="fas fa-bullhorn w-4 mr-2 text-xs"></i>
            <span>Campaigns</span>
        </a>
        <a href="{{ route('admin.advertisements.slots.index') }}"
            class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.advertisements.slots.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            <i class="fas fa-th-large w-4 mr-2 text-xs"></i>
            <span>Ad Slots</span>
        </a>
        @if(auth()->user()->hasPermission('advertisements.analytics'))
        <a href="{{ route('admin.advertisements.analytics.index') }}"
            class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.advertisements.analytics.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            <i class="fas fa-chart-bar w-4 mr-2 text-xs"></i>
            <span>Analytics</span>
        </a>
        @endif
    </div>
</div>
@endif

{{-- Newspaper Section --}}
@if(auth()->user()->hasPermission('top-stories.view') || auth()->user()->hasPermission('featured-categories.view') || auth()->user()->hasPermission('headline-banner.view'))
<div class="pt-4 pb-2">
    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Newspaper</p>
</div>

@if(auth()->user()->hasPermission('top-stories.view'))
<a href="{{ route('admin.top-stories.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.top-stories.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-newspaper w-5 mr-3"></i>
    <span>প্রধান খবর</span>
    @if(request()->routeIs('admin.top-stories.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif

@if(auth()->user()->hasPermission('top-videos.view'))
<a href="{{ route('admin.top-videos.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.top-videos.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-video w-5 mr-3"></i>
    <span>শীর্ষ ভিডিও</span>
    @if(request()->routeIs('admin.top-videos.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif

@if(auth()->user()->hasPermission('featured-categories.view'))
<a href="{{ route('admin.featured-categories.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.featured-categories.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-layer-group w-5 mr-3"></i>
    <span>গুরুত্বপুর্ন বিভাগ</span>
    @if(request()->routeIs('admin.featured-categories.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif

@if(auth()->user()->hasPermission('headline-banner.view'))
<a href="{{ route('admin.blog.headline-banner.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.blog.headline-banner.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-bullhorn w-5 mr-3"></i>
    <span>শিরোনাম ব্যানার</span>
    @if(request()->routeIs('admin.blog.headline-banner.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif
@endif

{{-- Feedback Section --}}
@if(auth()->user()->hasPermission('feedback.view'))
<div class="pt-4 pb-2">
    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Feedback</p>
</div>

<a href="{{ route('admin.feedback.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.feedback.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-star w-5 mr-3"></i>
    <span>Customer Feedback</span>
    @php
    try {
    $pendingFeedbackCount = \App\Models\Feedback::where('status', 'pending')->count();
    } catch (\Exception $e) {
    $pendingFeedbackCount = 0;
    }
    @endphp
    @if($pendingFeedbackCount > 0)
    <span class="ml-auto bg-orange-500 text-white text-xs px-2 py-1 rounded-full">{{ $pendingFeedbackCount }}</span>
    @endif
    @if(request()->routeIs('admin.feedback.*'))
    <i class="fas fa-chevron-right {{ $pendingFeedbackCount > 0 ? '' : 'ml-auto' }} text-xs"></i>
    @endif
</a>
@endif

{{-- Appointments Section --}}
@if(auth()->user()->hasPermission('appointments.view') || auth()->user()->hasPermission('chambers.manage'))
<div class="pt-4 pb-2">
    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Appointments</p>
</div>

@if(auth()->user()->hasPermission('appointments.view'))
<a href="{{ route('admin.appointments.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.appointments.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-calendar-check w-5 mr-3"></i>
    <span>Appointments</span>
    @php
    try {
    $pendingAppointments = \App\Models\Appointment::where('status', 'pending')->count();
    } catch (\Exception $e) {
    $pendingAppointments = 0;
    }
    @endphp
    @if($pendingAppointments > 0)
    <span class="ml-auto bg-orange-500 text-white text-xs px-2 py-1 rounded-full">{{ $pendingAppointments }}</span>
    @endif
</a>
@endif

@if(auth()->user()->hasPermission('chambers.manage'))
<a href="{{ route('admin.chambers.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.chambers.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-building w-5 mr-3"></i>
    <span>Chambers</span>
</a>
@endif
@endif

{{-- Finance Section (Placeholder) --}}
@if(auth()->user()->hasPermission('finance.view'))
<div class="pt-4 pb-2">
    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Finance</p>
</div>

<a href="#"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-400 cursor-not-allowed">
    <i class="fas fa-dollar-sign w-5 mr-3"></i>
    <span>Transactions</span>
    <span class="ml-auto text-xs bg-gray-200 px-2 py-1 rounded">Soon</span>
</a>

<a href="#"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-400 cursor-not-allowed">
    <i class="fas fa-chart-line w-5 mr-3"></i>
    <span>Reports</span>
    <span class="ml-auto text-xs bg-gray-200 px-2 py-1 rounded">Soon</span>
</a>
@endif

{{-- Communication Section --}}
@if(auth()->user()->hasPermission('contact-messages.view'))
<div class="pt-4 pb-2">
    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Communication</p>
</div>

<a href="{{ route('admin.contact.messages.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.contact.messages.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-envelope w-5 mr-3"></i>
    <span>Contact Messages</span>
    @php
    try {
    $unreadMessagesCount = \App\Models\ContactMessage::where('status', 'unread')->count();
    } catch (\Exception $e) {
    $unreadMessagesCount = 0;
    }
    @endphp
    @if($unreadMessagesCount > 0)
    <span class="ml-auto bg-blue-500 text-white text-xs px-2 py-1 rounded-full">{{ $unreadMessagesCount }}</span>
    @endif
    @if(request()->routeIs('admin.contact.messages.*'))
    <i class="fas fa-chevron-right {{ $unreadMessagesCount > 0 ? '' : 'ml-auto' }} text-xs"></i>
    @endif
</a>
@endif

{{-- Settings Section --}}
@if(auth()->user()->hasPermission('users.view') || auth()->user()->hasPermission('system.settings.manage') || auth()->user()->hasPermission('settings.manage'))
<div class="pt-4 pb-2">
    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">System</p>
</div>

@if(auth()->user()->hasPermission('settings.manage'))
<a href="{{ route('admin.site-settings.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.site-settings.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-cog w-5 mr-3"></i>
    <span>Site Settings</span>
</a>
@endif

@if(auth()->user()->hasPermission('theme.manage'))
<a href="{{ route('admin.theme-settings.index') }}"
    class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.theme-settings.*') ? 'bg-gray-100 text-blue-600' : '' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
        </path>
    </svg>
    <span>Theme Settings</span>
</a>
@endif

@if(auth()->user()->hasPermission('system.settings.manage'))
<a href="{{ route('admin.system-settings.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.system-settings.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-server w-5 mr-3"></i>
    <span>System Settings</span>
    @if(request()->routeIs('admin.system-settings.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif

@if(auth()->user()->hasPermission('permissions.manage'))
<a href="{{ route('admin.module-settings.index') }}"
    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.module-settings.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-shield-alt w-5 mr-3"></i>
    <span>Permission Settings</span>
    @if(request()->routeIs('admin.module-settings.*'))
    <i class="fas fa-chevron-right ml-auto text-xs"></i>
    @endif
</a>
@endif
@endif
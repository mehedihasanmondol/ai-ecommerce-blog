{{-- 
/**
 * ModuleName: Frontend Header Component
 * Purpose: Main navigation header for public-facing pages (iHerb-style)
 * 
 * Features:
 * - Top announcement bar with promotional message
 * - Main header with logo, search, and user actions
 * - Navigation menu with categories
 * - Responsive mobile menu
 * 
 * @category Frontend
 * @package  Components
 * @created  2025-01-06
 */
--}}

<!-- Top Announcement Bar -->
<div class="bg-gradient-to-r from-green-600 to-green-700 text-white text-sm">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between py-2">
            <div class="flex items-center space-x-4">
                <a href="#" class="flex items-center hover:text-green-100 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span class="font-medium">Up to 70% off iHerb brands</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                
                <a href="#" class="flex items-center hover:text-green-100 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">Black Friday Month</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>BD</span>
                </div>
                <span>|</span>
                <div class="flex items-center space-x-1">
                    <span>EN</span>
                </div>
                <span>|</span>
                <div class="flex items-center space-x-1">
                    <span>USD</span>
                </div>
                <button class="hover:text-green-100 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Main Header -->
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between py-4">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center">
                    <div class="bg-green-600 text-white font-bold text-2xl px-3 py-2 rounded">
                        iHerb
                    </div>
                </a>
            </div>

            <!-- Search Bar -->
            <div class="flex-1 max-w-2xl mx-8">
                <div class="relative">
                    <input 
                        type="text" 
                        placeholder="Search all of iHerb" 
                        class="w-full px-4 py-2.5 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                    <button class="absolute right-2 top-1/2 -translate-y-1/2 bg-green-600 text-white p-2 rounded-md hover:bg-green-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- User Actions -->
            <div class="flex items-center space-x-6">
                @auth
                    <a href="{{ route('customer.orders.index') }}" class="flex items-center text-gray-700 hover:text-green-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="ml-2 text-sm font-medium hidden lg:block">{{ auth()->user()->name }}</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="flex items-center text-gray-700 hover:text-green-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="ml-2 text-sm font-medium hidden lg:block">Sign In</span>
                    </a>
                @endauth

                <!-- Cart -->
                <a href="#" class="relative flex items-center text-gray-700 hover:text-green-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="absolute -top-2 -right-2 bg-green-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">0</span>
                </a>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="border-t border-gray-200">
            <ul class="flex items-center space-x-1 py-3 overflow-x-auto scrollbar-hide">
                <li>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-md transition whitespace-nowrap">
                        Supplements
                    </a>
                </li>
                <li>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-md transition whitespace-nowrap">
                        Sports
                    </a>
                </li>
                <li>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-md transition whitespace-nowrap">
                        Bath
                    </a>
                </li>
                <li>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-md transition whitespace-nowrap">
                        Beauty
                    </a>
                </li>
                <li>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-md transition whitespace-nowrap">
                        Grocery
                    </a>
                </li>
                <li>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-md transition whitespace-nowrap">
                        Home
                    </a>
                </li>
                <li>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-md transition whitespace-nowrap">
                        Baby
                    </a>
                </li>
                <li>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-md transition whitespace-nowrap">
                        Pets
                    </a>
                </li>
                <li>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-md transition whitespace-nowrap">
                        Brands A-Z
                    </a>
                </li>
                <li>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-md transition whitespace-nowrap">
                        Health Goals
                    </a>
                </li>
                <li>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-md transition whitespace-nowrap">
                        Sale Offers
                    </a>
                </li>
                <li>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-md transition whitespace-nowrap">
                        Best Sellers
                    </a>
                </li>
                <li>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-md transition whitespace-nowrap">
                        Try
                    </a>
                </li>
                <li>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-md transition whitespace-nowrap">
                        New
                    </a>
                </li>
                <li>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-md transition whitespace-nowrap">
                        More
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>

<!-- Mobile Menu Toggle (Hidden by default, shown on mobile) -->
<div x-data="{ mobileMenuOpen: false }" class="lg:hidden">
    <button @click="mobileMenuOpen = !mobileMenuOpen" class="fixed bottom-4 right-4 bg-green-600 text-white p-4 rounded-full shadow-lg z-50">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

    <!-- Mobile Menu Overlay -->
    <div 
        x-show="mobileMenuOpen" 
        @click="mobileMenuOpen = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-50 z-40"
        style="display: none;"
    ></div>

    <!-- Mobile Menu -->
    <div 
        x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed top-0 right-0 bottom-0 w-80 bg-white shadow-xl z-50 overflow-y-auto"
        style="display: none;"
    >
        <div class="p-4">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Menu</h2>
                <button @click="mobileMenuOpen = false" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <nav class="space-y-2">
                <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-md transition">Supplements</a>
                <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-md transition">Sports</a>
                <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-md transition">Bath</a>
                <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-md transition">Beauty</a>
                <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-md transition">Grocery</a>
                <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-md transition">Home</a>
                <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-md transition">Baby</a>
                <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-md transition">Pets</a>
                <a href="#" class="block px-4 py-3 text-red-600 hover:bg-red-50 rounded-md transition">Sale Offers</a>
                <a href="#" class="block px-4 py-3 text-blue-600 hover:bg-blue-50 rounded-md transition">Best Sellers</a>
            </nav>
        </div>
    </div>
</div>

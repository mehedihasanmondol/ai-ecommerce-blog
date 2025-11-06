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
<header class="bg-white shadow-sm sticky top-0 z-50 relative">
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

        <!-- Navigation Menu with Mega Dropdown -->
        <nav class="border-t border-gray-200 relative" x-data="{ activeMenu: null }">
            <ul class="flex items-center space-x-1 py-3 overflow-x-auto scrollbar-hide">
                <!-- Bath Menu with Mega Dropdown -->
                <li class="relative static" 
                    @mouseenter="activeMenu = 'bath'" 
                    @mouseleave="activeMenu = null">
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-md transition whitespace-nowrap">
                        Bath
                    </a>
                </li>
                
                <!-- Mega Menu Dropdown (Outside li for proper positioning) -->
                <div x-show="activeMenu === 'bath'" 
                     @mouseenter="activeMenu = 'bath'" 
                     @mouseleave="activeMenu = null"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-1"
                     class="absolute left-0 right-0 top-full w-full bg-white shadow-2xl rounded-lg border border-gray-200 z-[100]"
                     style="display: none;">
                    <div class="container mx-auto px-4">
                        <div class="p-8">
                            <div class="grid grid-cols-5 gap-8 max-w-6xl">
                                <!-- Column 1: Bath & Shower -->
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                        Bath & Shower
                                    </h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Bar Soap</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Bath Soaks</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Body Scrubs</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Body Wash & Shower Gel</a></li>
                                    </ul>
                                </div>

                                <!-- Column 2: Essential Oils -->
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                        Essential Oils
                                    </h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Essential Oil Blends</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Essential Oil Diffusers</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Essential Oil Sets</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Essential Oil Spray</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Single Essential Oils</a></li>
                                    </ul>
                                </div>

                                <!-- Column 3: Body Care -->
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                        Body Care
                                    </h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Body & Massage Oils</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Hand Cream</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Lotion</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Self-Tan</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Skincare Treatments</a></li>
                                    </ul>
                                </div>

                                <!-- Column 4: Haircare -->
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                        Haircare
                                    </h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Conditioner</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Detangler</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Hair Accessories</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Hair Colour</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Hair Styling</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Hair Treatments</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Shampoo</a></li>
                                    </ul>
                                </div>

                                <!-- Column 5: Trending Brands -->
                                <div class="border-l border-gray-200 pl-8">
                                    <h3 class="text-sm font-bold text-gray-900 mb-3">Trending brands</h3>
                                    <div class="space-y-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Supplements Mega Menu Dropdown -->
                <div x-show="activeMenu === 'supplements'" 
                     @mouseenter="activeMenu = 'supplements'" 
                     @mouseleave="activeMenu = null"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-1"
                     class="absolute left-0 right-0 top-full w-full bg-white shadow-2xl rounded-lg border border-gray-200 z-[100]"
                     style="display: none;">
                    <div class="container mx-auto px-4">
                        <div class="p-8">
                            <div class="grid grid-cols-5 gap-8 max-w-6xl">
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Vitamins</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Multivitamins</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Vitamin C</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Vitamin D</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">B-Complex</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Minerals</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Calcium</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Magnesium</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Zinc</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Iron</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Herbs & Botanicals</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Turmeric</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Ashwagandha</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Ginseng</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Green Tea Extract</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Specialty Supplements</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Probiotics</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Omega-3</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Collagen</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">CoQ10</a></li>
                                    </ul>
                                </div>
                                <div class="border-l border-gray-200 pl-8">
                                    <h3 class="text-sm font-bold text-gray-900 mb-3">Trending brands</h3>
                                    <div class="space-y-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sports Mega Menu Dropdown -->
                <div x-show="activeMenu === 'sports'" 
                     @mouseenter="activeMenu = 'sports'" 
                     @mouseleave="activeMenu = null"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute left-0 right-0 top-full w-full bg-white shadow-2xl rounded-lg border border-gray-200 z-[100]"
                     style="display: none;">
                    <div class="container mx-auto px-4">
                        <div class="p-8">
                            <div class="grid grid-cols-5 gap-8 max-w-6xl">
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Protein</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Whey Protein</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Plant Protein</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Protein Bars</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Pre-Workout</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Energy Boosters</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Creatine</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">BCAAs</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Weight Management</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Fat Burners</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Meal Replacements</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Appetite Control</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Recovery</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Post-Workout</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Electrolytes</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Joint Support</a></li>
                                    </ul>
                                </div>
                                <div class="border-l border-gray-200 pl-8">
                                    <h3 class="text-sm font-bold text-gray-900 mb-3">Trending brands</h3>
                                    <div class="space-y-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Beauty Mega Menu Dropdown -->
                <div x-show="activeMenu === 'beauty'" 
                     @mouseenter="activeMenu = 'beauty'" 
                     @mouseleave="activeMenu = null"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute left-0 right-0 top-full w-full bg-white shadow-2xl rounded-lg border border-gray-200 z-[100]"
                     style="display: none;">
                    <div class="container mx-auto px-4">
                        <div class="p-8">
                            <div class="grid grid-cols-4 gap-8 max-w-6xl">
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Skin Care</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Cleansers</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Moisturizers</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Serums</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Face Masks</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Makeup</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Foundation</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Lipstick</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Mascara</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Eye Shadow</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Hair Care</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Shampoo</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Conditioner</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Hair Masks</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Styling Products</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Nails</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Nail Polish</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Nail Care</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Nail Tools</a></li>
                                    </ul>
                                </div>
                                <div class="border-l border-gray-200 pl-8">
                                    <h3 class="text-sm font-bold text-gray-900 mb-3">Trending brands</h3>
                                    <div class="space-y-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grocery, Home, Baby, Pets Mega Menus (Simplified) -->
                <div x-show="activeMenu === 'grocery'" 
                     @mouseenter="activeMenu = 'grocery'" 
                     @mouseleave="activeMenu = null"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute left-0 right-0 top-full w-full bg-white shadow-2xl rounded-lg border border-gray-200 z-[100]"
                     style="display: none;">
                    <div class="container mx-auto px-4">
                        <div class="p-8">
                            <div class="grid grid-cols-5 gap-8 max-w-6xl">
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Snacks</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Protein Bars</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Nuts & Seeds</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Dried Fruits</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Beverages</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Tea & Coffee</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Juices</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Smoothies</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Pantry</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Oils & Vinegars</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Spices</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Baking</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Organic Foods</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Organic Grains</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Organic Snacks</a></li>
                                    </ul>
                                </div>
                                <div class="border-l border-gray-200 pl-8">
                                    <h3 class="text-sm font-bold text-gray-900 mb-3">Trending brands</h3>
                                    <div class="space-y-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeMenu === 'home'" 
                     @mouseenter="activeMenu = 'home'" 
                     @mouseleave="activeMenu = null"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute left-0 right-0 top-full w-full bg-white shadow-2xl rounded-lg border border-gray-200 z-[100]"
                     style="display: none;">
                    <div class="container mx-auto px-4">
                        <div class="p-8">
                            <div class="grid grid-cols-4 gap-8 max-w-6xl">
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Cleaning</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">All-Purpose Cleaners</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Laundry</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Dish Soap</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Air & Fragrance</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Candles</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Air Fresheners</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Diffusers</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Kitchen</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Storage</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Utensils</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Paper Products</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Paper Towels</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Tissues</a></li>
                                    </ul>
                                </div>
                                <div class="border-l border-gray-200 pl-8">
                                    <h3 class="text-sm font-bold text-gray-900 mb-3">Trending brands</h3>
                                    <div class="space-y-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeMenu === 'baby'" 
                     @mouseenter="activeMenu = 'baby'" 
                     @mouseleave="activeMenu = null"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute left-0 right-0 top-full w-full bg-white shadow-2xl rounded-lg border border-gray-200 z-[100]"
                     style="display: none;">
                    <div class="container mx-auto px-4">
                        <div class="p-8">
                            <div class="grid grid-cols-4 gap-8 max-w-6xl">
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Baby Care</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Diapers</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Wipes</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Baby Bath</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Feeding</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Baby Formula</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Baby Food</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Bottles</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Health</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Baby Vitamins</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Teething</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Toys</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Educational Toys</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Plush Toys</a></li>
                                    </ul>
                                </div>
                                <div class="border-l border-gray-200 pl-8">
                                    <h3 class="text-sm font-bold text-gray-900 mb-3">Trending brands</h3>
                                    <div class="space-y-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeMenu === 'pets'" 
                     @mouseenter="activeMenu = 'pets'" 
                     @mouseleave="activeMenu = null"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute left-0 right-0 top-full w-full bg-white shadow-2xl rounded-lg border border-gray-200 z-[100]"
                     style="display: none;">
                    <div class="container mx-auto px-4">
                        <div class="p-8">
                            <div class="grid grid-cols-4 gap-8 max-w-6xl">
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Dog Care</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Dog Food</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Dog Treats</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Dog Supplements</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Cat Care</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Cat Food</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Cat Treats</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Litter</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Pet Health</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Vitamins</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Joint Support</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Grooming</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 mb-3">Pet Accessories</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Toys</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Beds</a></li>
                                        <li><a href="#" class="text-sm text-gray-700 hover:text-green-600 transition">Bowls</a></li>
                                    </ul>
                                </div>
                                <div class="border-l border-gray-200 pl-8">
                                    <h3 class="text-sm font-bold text-gray-900 mb-3">Trending brands</h3>
                                    <div class="space-y-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-600">Brand</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Supplements Menu -->
                <li class="relative static" 
                    @mouseenter="activeMenu = 'supplements'" 
                    @mouseleave="activeMenu = null">
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-md transition whitespace-nowrap">
                        Supplements
                    </a>
                </li>
                
                <!-- Sports Menu -->
                <li class="relative static" 
                    @mouseenter="activeMenu = 'sports'" 
                    @mouseleave="activeMenu = null">
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-md transition whitespace-nowrap">
                        Sports
                    </a>
                </li>
                
                <!-- Beauty Menu -->
                <li class="relative static" 
                    @mouseenter="activeMenu = 'beauty'" 
                    @mouseleave="activeMenu = null">
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-md transition whitespace-nowrap">
                        Beauty
                    </a>
                </li>
                
                <!-- Grocery Menu -->
                <li class="relative static" 
                    @mouseenter="activeMenu = 'grocery'" 
                    @mouseleave="activeMenu = null">
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-md transition whitespace-nowrap">
                        Grocery
                    </a>
                </li>
                
                <!-- Home Menu -->
                <li class="relative static" 
                    @mouseenter="activeMenu = 'home'" 
                    @mouseleave="activeMenu = null">
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-md transition whitespace-nowrap">
                        Home
                    </a>
                </li>
                
                <!-- Baby Menu -->
                <li class="relative static" 
                    @mouseenter="activeMenu = 'baby'" 
                    @mouseleave="activeMenu = null">
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-md transition whitespace-nowrap">
                        Baby
                    </a>
                </li>
                
                <!-- Pets Menu -->
                <li class="relative static" 
                    @mouseenter="activeMenu = 'pets'" 
                    @mouseleave="activeMenu = null">
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

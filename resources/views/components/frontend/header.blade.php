{{-- 
/**
 * ModuleName: Frontend Header Component
 * Purpose: Main navigation header for public-facing pages with dynamic mega menu
 * 
 * Features:
 * - Top announcement bar with promotional message
 * - Main header with logo, search, and user actions
 * - Dynamic navigation menu with categories from database
 * - Responsive mobile menu
 * 
 * @category Frontend
 * @package  Components
 * @created  2025-11-06
 * @updated  2025-11-06
 */
--}}

<!-- Top Announcement Bar -->
<div class="bg-gradient-to-r from-green-600 to-green-700 text-white text-sm">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between py-2">
            <div class="flex items-center space-x-4">
                <a href="{{ route('coupons.index') }}" class="flex items-center hover:text-green-100 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span class="font-medium">Special Offers & Coupons</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                
                <a href="{{ route('shop') }}" class="flex items-center hover:text-green-100 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">Shop Now</span>
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
                    <!-- User Dropdown -->
                    <div x-data="{ userMenuOpen: false }" class="relative">
                        <button 
                            @click="userMenuOpen = !userMenuOpen"
                            @click.away="userMenuOpen = false"
                            class="flex items-center text-gray-700 hover:text-green-600 transition">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                                     alt="{{ auth()->user()->name }}"
                                     class="w-8 h-8 rounded-full object-cover border-2 border-gray-200">
                            @else
                                <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="ml-2 text-sm font-medium hidden lg:block">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 ml-1 hidden lg:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div 
                            x-show="userMenuOpen"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200"
                            style="display: none;">
                            
                            <a href="{{ route('customer.orders.index') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 transition">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    My Orders
                                </div>
                            </a>

                            <a href="{{ route('customer.profile') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 transition">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Profile
                                </div>
                            </a>

                            <div class="border-t border-gray-200 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Logout
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="flex items-center text-gray-700 hover:text-green-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="ml-2 text-sm font-medium hidden lg:block">Sign In</span>
                    </a>
                @endauth

                <!-- Wishlist -->
                @livewire('wishlist.wishlist-counter')

                <!-- Cart -->
                @livewire('cart.cart-counter')
            </div>
        </div>

        <!-- Navigation Container -->
        <div class="border-t border-gray-200 flex items-center justify-between">
            <!-- Primary Mega Menu (Left) -->
            <x-frontend.mega-menu 
                :megaMenuCategories="$megaMenuCategories ?? collect()" 
                :trendingBrands="$trendingBrands ?? collect()" 
            />
            
            <!-- Secondary Menu (Right) -->
            <x-frontend.secondary-menu />
        </div>
    </div>
</header>

<!-- Mobile Menu Overlay -->
<div x-data="{ mobileMenuOpen: false }">
    <!-- Mobile Menu Button (Visible on small screens) -->
    <button 
        @click="mobileMenuOpen = true"
        class="lg:hidden fixed bottom-4 right-4 bg-green-600 text-white p-4 rounded-full shadow-lg z-40 hover:bg-green-700 transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

    <!-- Mobile Menu Backdrop -->
    <div 
        x-show="mobileMenuOpen"
        @click="mobileMenuOpen = false"
        class="fixed inset-0 bg-black bg-opacity-50 z-40"
        style="display: none;"
    ></div>

    <!-- Mobile Menu Sidebar -->
    <div 
        x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed top-0 right-0 bottom-0 w-80 bg-white shadow-xl z-50 overflow-y-auto"
        style="display: none;">
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
                <!-- User Section -->
                @auth
                    <div class="bg-green-50 rounded-md p-4 mb-4">
                        <div class="flex items-center mb-3">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                                     alt="{{ auth()->user()->name }}"
                                     class="w-12 h-12 rounded-full object-cover border-2 border-green-200">
                            @else
                                <div class="w-12 h-12 rounded-full bg-green-500 flex items-center justify-center text-white font-bold text-lg">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="ml-3">
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-600">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        
                        <div class="space-y-1">
                            <a href="{{ route('customer.orders.index') }}" 
                               class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-white rounded transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                My Orders
                            </a>
                            
                            <a href="{{ route('customer.profile') }}" 
                               class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-white rounded transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Profile
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center px-3 py-2 text-sm text-red-600 hover:bg-white rounded transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" 
                       class="flex items-center px-4 py-3 text-white bg-green-600 hover:bg-green-700 rounded-md transition font-medium mb-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Sign In
                    </a>
                @endauth

                <!-- Blog Link -->
                <a href="{{ route('blog.index') }}" 
                   class="block px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-md transition font-medium">
                    📝 Blog
                </a>
                
                <div class="border-t border-gray-200 my-2"></div>
                
                @forelse($megaMenuCategories ?? collect() as $category)
                    <a href="{{ $category->getUrl() }}" 
                       class="block px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-md transition">
                        {{ $category->name }}
                    </a>
                @empty
                    <p class="px-4 py-3 text-gray-500">No categories available</p>
                @endforelse
            </nav>
        </div>
    </div>
</div>

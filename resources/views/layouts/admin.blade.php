<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Allow Livewire to work in development --}}
    <meta http-equiv="Content-Security-Policy" content="default-src * 'unsafe-inline' 'unsafe-eval'; script-src * 'unsafe-inline' 'unsafe-eval'; connect-src * 'unsafe-inline'; img-src * data: blob: 'unsafe-inline'; frame-src *; style-src * 'unsafe-inline';">

    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- TODO: Install Font Awesome locally via npm -->
    <!-- Temporary CDN - Replace with: npm install @fortawesome/fontawesome-free -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-100" x-data="{ sidebarOpen: true, mobileMenuOpen: false }">
    <div class="min-h-screen">
        <!-- Top Navigation Bar -->
        <nav class="bg-white shadow-sm border-b border-gray-200 fixed w-full z-30">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Left Side -->
                    <div class="flex items-center">
                        <!-- Sidebar Toggle -->
                        <button @click="sidebarOpen = !sidebarOpen" 
                                class="hidden lg:block text-gray-500 hover:text-gray-700 focus:outline-none mr-4">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        
                        <!-- Mobile Menu Toggle -->
                        <button @click="mobileMenuOpen = !mobileMenuOpen" 
                                class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none mr-4">
                            <i class="fas fa-bars text-xl"></i>
                        </button>

                        <!-- Logo -->
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                            <i class="fas fa-shield-alt text-2xl text-blue-600 mr-2"></i>
                            <span class="text-xl font-bold text-gray-900">Admin Panel</span>
                        </a>
                    </div>

                    <!-- Right Side -->
                    <div class="flex items-center space-x-4">
                        <!-- Global Search -->
                        <div class="hidden md:block w-64">
                            @livewire('admin.global-user-search')
                        </div>

                        <!-- Notifications -->
                        <button class="text-gray-500 hover:text-gray-700 relative">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500"></span>
                        </button>

                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center space-x-2 text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                                <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="hidden md:block">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs hidden md:block"></i>
                            </button>

                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i>Profile
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i>Settings
                                </a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed left-0 top-16 h-[calc(100vh-4rem)] w-64 bg-white border-r border-gray-200 transition-transform duration-300 ease-in-out z-20 hidden lg:block overflow-y-auto">
            <nav class="p-4 space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-tachometer-alt w-5 mr-3"></i>
                    <span>Dashboard</span>
                </a>

                <!-- User Management Section -->
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

                <a href="{{ route('admin.roles.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.roles.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-shield-alt w-5 mr-3"></i>
                    <span>Roles & Permissions</span>
                    @if(request()->routeIs('admin.roles.*'))
                        <i class="fas fa-chevron-right ml-auto text-xs"></i>
                    @endif
                </a>

                <!-- E-commerce Section (Placeholder) -->
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

                <a href="{{ route('admin.orders.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-shopping-cart w-5 mr-3"></i>
                    <span>Orders</span>
                    @if(request()->routeIs('admin.orders.*'))
                        <i class="fas fa-chevron-right ml-auto text-xs"></i>
                    @endif
                </a>

                <a href="{{ route('admin.categories.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-tags w-5 mr-3"></i>
                    <span>Categories</span>
                    @if(request()->routeIs('admin.categories.*'))
                        <i class="fas fa-chevron-right ml-auto text-xs"></i>
                    @endif
                </a>

                <a href="{{ route('admin.brands.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.brands.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-copyright w-5 mr-3"></i>
                    <span>Brands</span>
                    @if(request()->routeIs('admin.brands.*'))
                        <i class="fas fa-chevron-right ml-auto text-xs"></i>
                    @endif
                </a>

                <a href="{{ route('admin.attributes.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.attributes.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-sliders-h w-5 mr-3"></i>
                    <span>Attributes</span>
                    @if(request()->routeIs('admin.attributes.*'))
                        <i class="fas fa-chevron-right ml-auto text-xs"></i>
                    @endif
                </a>

                <!-- Inventory Section (Placeholder) -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Inventory</p>
                </div>
                
                <a href="#" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-400 cursor-not-allowed">
                    <i class="fas fa-warehouse w-5 mr-3"></i>
                    <span>Stock Management</span>
                    <span class="ml-auto text-xs bg-gray-200 px-2 py-1 rounded">Soon</span>
                </a>

                <!-- Content Section (Placeholder) -->
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

                <a href="{{ route('admin.secondary-menu.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.secondary-menu.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-bars w-5 mr-3"></i>
                    <span>Secondary Menu</span>
                    @if(request()->routeIs('admin.secondary-menu.*'))
                        <i class="fas fa-chevron-right ml-auto text-xs"></i>
                    @endif
                </a>

                <a href="{{ route('admin.sale-offers.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.sale-offers.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-tag w-5 mr-3"></i>
                    <span>Sale Offers</span>
                    @if(request()->routeIs('admin.sale-offers.*'))
                        <i class="fas fa-chevron-right ml-auto text-xs"></i>
                    @endif
                </a>

                <a href="{{ route('admin.trending-products.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.trending-products.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-fire w-5 mr-3"></i>
                    <span>Trending Products</span>
                    @if(request()->routeIs('admin.trending-products.*'))
                        <i class="fas fa-chevron-right ml-auto text-xs"></i>
                    @endif
                </a>

                <a href="#" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-400 cursor-not-allowed">
                    <i class="fas fa-blog w-5 mr-3"></i>
                    <span>Blog Posts</span>
                    <span class="ml-auto text-xs bg-gray-200 px-2 py-1 rounded">Soon</span>
                </a>

                <!-- Finance Section (Placeholder) -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Finance</p>
                </div>
                
                <a href="#" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-400 cursor-not-allowed">
                    <i class="fas fa-dollar-sign w-5 mr-3"></i>
                    <span>Transactions</span>
                    <span class="ml-auto text-xs bg-gray-200 px-2 py-1 rounded">Soon</span>
                </a>

                <a href="#" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-400 cursor-not-allowed">
                    <i class="fas fa-chart-line w-5 mr-3"></i>
                    <span>Reports</span>
                    <span class="ml-auto text-xs bg-gray-200 px-2 py-1 rounded">Soon</span>
                </a>

                <!-- Settings Section -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">System</p>
                </div>
                
                <a href="#" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-cog w-5 mr-3"></i>
                    <span>Settings</span>
                </a>
            </nav>
        </aside>

        <!-- Mobile Sidebar -->
        <aside x-show="mobileMenuOpen" 
               @click.away="mobileMenuOpen = false"
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-300"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="fixed left-0 top-16 h-[calc(100vh-4rem)] w-64 bg-white border-r border-gray-200 z-40 lg:hidden overflow-y-auto">
            <nav class="p-4 space-y-1">
                <!-- Same navigation as desktop sidebar -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-tachometer-alt w-5 mr-3"></i>
                    <span>Dashboard</span>
                </a>

                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">User Management</p>
                </div>
                
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-users w-5 mr-3"></i>
                    <span>Users</span>
                </a>

                <a href="{{ route('admin.roles.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.roles.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-shield-alt w-5 mr-3"></i>
                    <span>Roles & Permissions</span>
                </a>

                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">E-commerce</p>
                </div>
                
                <a href="{{ route('admin.products.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-box w-5 mr-3"></i>
                    <span>Products</span>
                </a>

                <a href="{{ route('admin.orders.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.orders.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-shopping-cart w-5 mr-3"></i>
                    <span>Orders</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-tags w-5 mr-3"></i>
                    <span>Categories</span>
                </a>

                <a href="{{ route('admin.brands.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.brands.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-copyright w-5 mr-3"></i>
                    <span>Brands</span>
                </a>

                <a href="{{ route('admin.attributes.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.attributes.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-sliders-h w-5 mr-3"></i>
                    <span>Attributes</span>
                </a>

                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Content</p>
                </div>
                
                <a href="{{ route('admin.homepage-settings.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.homepage-settings.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-home w-5 mr-3"></i>
                    <span>Homepage Settings</span>
                </a>

                <a href="{{ route('admin.secondary-menu.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.secondary-menu.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-bars w-5 mr-3"></i>
                    <span>Secondary Menu</span>
                </a>
            </nav>
        </aside>

        <!-- Page Content -->
        <main :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-0'" class="pt-16 transition-all duration-300 ease-in-out min-h-screen">
            <div class="py-6">
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-green-600 hover:text-green-800">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle mr-3 mt-1"></i>
                        <div>
                            <p class="font-semibold">There were some errors with your submission:</p>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    @livewireScripts

    <!-- Alert Components -->
    <x-confirm-modal />

    @stack('scripts')
</body>
</html>

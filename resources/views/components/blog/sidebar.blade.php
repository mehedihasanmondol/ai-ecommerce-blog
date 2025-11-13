@props([
    'title' => 'Wellness Hub',
    'subtitle' => 'Health & Lifestyle Blog',
    'categories' => collect(),
    'currentCategory' => null,
    'showBackLink' => false,
    'backLinkUrl' => null,
    'backLinkText' => null,
    'showAllLink' => false,
    'allLinkUrl' => null,
    'allLinkText' => null
])

<aside class="lg:col-span-3">
    <div class="lg:sticky lg:top-8 space-y-6">
        <!-- Collapsible Sidebar Card -->
        <div class="bg-white rounded-lg shadow-sm" x-data="{ sidebarOpen: false }">
            <!-- Header with Toggle Button -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $title }}</h2>
                    @if($subtitle)
                        <p class="text-xs text-gray-500 mt-1">{{ $subtitle }}</p>
                    @endif
                </div>
                
                <!-- Mobile Toggle Button -->
                <button 
                    @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden flex items-center justify-center w-8 h-8 rounded-lg hover:bg-gray-100 transition-colors"
                    :class="{ 'bg-gray-100': !sidebarOpen }"
                    type="button"
                    aria-label="Toggle sidebar menu"
                >
                    <svg 
                        class="w-5 h-5 text-gray-600 transition-transform duration-200"
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
            
            <!-- Collapsible Navigation -->
            <nav 
                class="py-2 overflow-hidden transition-all duration-300 ease-in-out lg:block"
                x-show="sidebarOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 max-h-0"
                x-transition:enter-end="opacity-100 max-h-screen"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 max-h-screen"
                x-transition:leave-end="opacity-0 max-h-0"
            >
                <!-- Home Link -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors group">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="font-medium">Home</span>
                </a>

                <!-- Back Link (if provided) -->
                @if($showBackLink && $backLinkUrl)
                <a href="{{ $backLinkUrl }}" 
                   class="flex items-center gap-3 px-6 py-3 text-blue-600 hover:bg-blue-50 transition-colors group border-b border-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span class="font-medium">{{ $backLinkText }}</span>
                </a>
                @endif

                <!-- View All Link (if provided) -->
                @if($showAllLink && $allLinkUrl)
                <a href="{{ $allLinkUrl }}" 
                   class="flex items-center gap-3 px-6 py-3 bg-green-50 text-green-700 transition-colors group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    <span class="font-medium">{{ $allLinkText }}</span>
                </a>
                @endif

                <!-- Dynamic Categories -->
                @foreach($categories as $category)
                    <a href="{{ route('blog.category', $category->slug) }}" 
                       class="flex items-center justify-between gap-3 px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors group {{ $currentCategory && $currentCategory->id === $category->id ? 'bg-green-50 text-green-700' : '' }}">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <!-- Category Image or Fallback Icon -->
                            @if($category->image_path)
                                <div class="w-8 h-8 flex-shrink-0 rounded-md overflow-hidden bg-gray-100">
                                    <img 
                                        src="{{ asset('storage/' . $category->image_path) }}" 
                                        alt="{{ $category->name }}"
                                        class="w-full h-full object-cover"
                                    >
                                </div>
                            @else
                                <div class="w-8 h-8 flex-shrink-0 rounded-md bg-gradient-to-br from-green-100 to-blue-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                            @endif
                            <span class="font-medium truncate">{{ $category->name }}</span>
                        </div>
                        
                        <!-- Post Count Badge -->
                        @if(isset($category->posts_count) && $category->posts_count > 0)
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full flex-shrink-0">
                                {{ $category->posts_count }}
                            </span>
                        @elseif(isset($category->published_posts_count) && $category->published_posts_count > 0)
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full flex-shrink-0">
                                {{ $category->published_posts_count }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </nav>
        </div>
    </div>
</aside>

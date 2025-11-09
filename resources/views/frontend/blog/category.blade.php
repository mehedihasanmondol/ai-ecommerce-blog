@extends('layouts.app')

@section('title', $category->meta_title ?? $category->name . ' - Blog')
@section('meta_description', $category->meta_description ?? $category->description)

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Sidebar - Sticky -->
            <aside class="lg:col-span-3">
                <div class="lg:sticky lg:top-8 space-y-6">
                    <!-- Wellness Hub + Categories Combined -->
                    <div class="bg-white rounded-lg shadow-sm">
                        <!-- Logo/Brand Header -->
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-2xl font-bold text-gray-900">
                                @if($category->parent_id)
                                    {{ $category->parent->name }}
                                @else
                                    Wellness Hub
                                @endif
                            </h2>
                            @if($category->parent_id)
                            <p class="text-xs text-gray-500 mt-1">Subcategories</p>
                            @endif
                        </div>
                        
                        <!-- Categories Navigation -->
                        <nav class="py-2">
                            <!-- Home Link -->
                            <a href="{{ route('home') }}" class="flex items-center gap-3 px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors group">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                <span class="font-medium">Home</span>
                            </a>

                            <!-- Back to Parent Category (if viewing subcategory) -->
                            @if($category->parent_id)
                            <a href="{{ route('blog.category', $category->parent->slug) }}" 
                               class="flex items-center gap-3 px-6 py-3 text-blue-600 hover:bg-blue-50 transition-colors group border-b border-gray-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                <span class="font-medium">Back to {{ $category->parent->name }}</span>
                            </a>
                            @endif

                            <!-- View All in Parent Category (if has subcategories) -->
                            @if($category->children()->where('is_active', true)->count() > 0)
                            <a href="{{ route('blog.category', $category->slug) }}" 
                               class="flex items-center gap-3 px-6 py-3 bg-green-50 text-green-700 transition-colors group">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                </svg>
                                <span class="font-medium">All {{ $category->name }}</span>
                            </a>
                            @endif

                            <!-- Dynamic Categories from Database -->
                            @foreach($categories as $cat)
                                <a href="{{ route('blog.category', $cat->slug) }}" 
                                   class="flex items-center justify-between gap-3 px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors group {{ $cat->id === $category->id ? 'bg-green-50 text-green-700' : '' }}">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <!-- Category Image or Fallback Icon -->
                                        @if($cat->image_path)
                                            <div class="w-8 h-8 flex-shrink-0 rounded-md overflow-hidden bg-gray-100">
                                                <img 
                                                    src="{{ asset('storage/' . $cat->image_path) }}" 
                                                    alt="{{ $cat->name }}"
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
                                        <span class="font-medium truncate">{{ $cat->name }}</span>
                                    </div>
                                    @if(isset($cat->posts_count) && $cat->posts_count > 0)
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full flex-shrink-0">
                                            {{ $cat->posts_count }}
                                        </span>
                                    @elseif(isset($cat->published_posts_count) && $cat->published_posts_count > 0)
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full flex-shrink-0">
                                            {{ $cat->published_posts_count }}
                                        </span>
                                    @endif
                                </a>
                            @endforeach
                        </nav>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="lg:col-span-9">
                <!-- Category Header -->
                <div class="bg-white rounded-lg shadow-sm mb-6">
                    <!-- Breadcrumb -->
                    <div class="px-8 pt-6 pb-4 border-b border-gray-100">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <a href="{{ route('home') }}" class="hover:text-green-600 transition-colors">Wellness Hub Home</a>
                            <span>/</span>
                            <a href="{{ route('blog.index') }}" class="hover:text-green-600 transition-colors">Blog</a>
                            <span>/</span>
                            <span class="text-gray-900 font-medium">{{ $category->name }}</span>
                        </div>
                    </div>

                    <!-- Category Info -->
                    <div class="px-8 py-6">
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">{{ $category->name }}</h1>
                        
                        @if($category->description)
                        <p class="text-lg text-gray-600 mb-4">{{ $category->description }}</p>
                        @endif
                        
                        <div class="flex items-center gap-4 text-sm text-gray-500">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                {{ $posts->total() }} {{ Str::plural('Article', $posts->total()) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Search, Filter & View Mode Bar -->
                <div class="bg-white rounded-lg shadow-sm mb-6 p-6" x-data="{ viewMode: 'list' }">
                    <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                        <!-- Search Form -->
                        <form action="{{ route('blog.category', $category->slug) }}" method="GET" class="flex-1 w-full lg:max-w-md">
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Search in {{ $category->name }}..." 
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </form>

                        <div class="flex items-center gap-3 w-full lg:w-auto">
                            <!-- Sort Filter -->
                            <select name="sort" 
                                    onchange="window.location.href='{{ route('blog.category', $category->slug) }}?sort=' + this.value + '{{ request('search') ? '&search=' . request('search') : '' }}'"
                                    class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest First</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                                <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title (A-Z)</option>
                            </select>

                            <!-- Per Page Filter -->
                            <select name="per_page" 
                                    onchange="window.location.href='{{ route('blog.category', $category->slug) }}?per_page=' + this.value + '{{ request('search') ? '&search=' . request('search') : '' }}{{ request('sort') ? '&sort=' . request('sort') : '' }}'"
                                    class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 per page</option>
                                <option value="20" {{ request('per_page', 10) == 20 ? 'selected' : '' }}>20 per page</option>
                                <option value="30" {{ request('per_page', 10) == 30 ? 'selected' : '' }}>30 per page</option>
                                <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50 per page</option>
                            </select>

                            <!-- View Mode Toggle -->
                            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                <button type="button" 
                                        @click="viewMode = 'list'"
                                        :class="viewMode === 'list' ? 'bg-green-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                                        class="px-3 py-2.5 transition-colors"
                                        title="List View">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                    </svg>
                                </button>
                                <button type="button" 
                                        @click="viewMode = 'grid'"
                                        :class="viewMode === 'grid' ? 'bg-green-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                                        class="px-3 py-2.5 border-l border-gray-300 transition-colors"
                                        title="Grid View">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Active Filters Display -->
                    @if(request('search') || request('sort'))
                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        <span class="text-sm text-gray-600">Active filters:</span>
                        @if(request('search'))
                        <span class="inline-flex items-center gap-1 bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">
                            Search: "{{ request('search') }}"
                            <a href="{{ route('blog.category', $category->slug) }}?{{ http_build_query(array_filter(request()->except('search'))) }}" 
                               class="hover:text-green-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </a>
                        </span>
                        @endif
                        @if(request('sort'))
                        <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                            Sort: {{ ucfirst(request('sort')) }}
                            <a href="{{ route('blog.category', $category->slug) }}?{{ http_build_query(array_filter(request()->except('sort'))) }}" 
                               class="hover:text-blue-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </a>
                        </span>
                        @endif
                        <a href="{{ route('blog.category', $category->slug) }}" class="text-sm text-red-600 hover:text-red-800 font-medium">
                            Clear all
                        </a>
                    </div>
                    @endif

                <!-- Posts List/Grid -->
                <div>
                    <!-- List View -->
                    <div x-show="viewMode === 'list'" class="space-y-6">
                        @forelse($posts as $post)
                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                            <div class="md:flex">
                                @if($post->featured_image)
                                <div class="md:w-1/3">
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" 
                                         alt="{{ $post->featured_image_alt }}" 
                                         class="w-full h-48 md:h-full object-cover">
                                </div>
                                @endif
                                <div class="p-6 {{ $post->featured_image ? 'md:w-2/3' : 'w-full' }}">
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="inline-block bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">
                                            {{ $category->name }}
                                        </span>
                                        @if($post->is_featured)
                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full">
                                            Featured
                                        </span>
                                        @endif
                                    </div>
                                    
                                    <h3 class="text-2xl font-bold text-gray-900 mb-2 hover:text-blue-600">
                                        <a href="{{ route('products.show', $post->slug) }}">{{ $post->title }}</a>
                                    </h3>
                                    
                                    @if($post->excerpt)
                                    <p class="text-gray-600 mb-4">{{ Str::limit($post->excerpt, 150) }}</p>
                                    @endif
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center text-sm text-gray-500">
                                            <span>{{ $post->author->name }}</span>
                                            <span class="mx-2">•</span>
                                            <span>{{ $post->published_at->format('M d, Y') }}</span>
                                            <span class="mx-2">•</span>
                                            <span>{{ $post->reading_time_text }}</span>
                                            @if($post->views_count > 0)
                                            <span class="mx-2">•</span>
                                            <span>{{ number_format($post->views_count) }} views</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('products.show', $post->slug) }}" 
                                           class="text-blue-600 hover:text-blue-800 font-medium">
                                            Read More →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                        @empty
                        <div class="bg-white rounded-lg shadow-md p-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="mt-4 text-gray-500 text-lg">No posts found in this category yet.</p>
                            <a href="{{ route('blog.index') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800">
                                Browse all posts →
                            </a>
                        </div>
                        @endforelse
                    </div>

                    <!-- Grid View -->
                    <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($posts as $post)
                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                            @if($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}" 
                                 alt="{{ $post->featured_image_alt }}" 
                                 class="w-full h-48 object-cover">
                            @endif
                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="inline-block bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">
                                        {{ $category->name }}
                                    </span>
                                    @if($post->is_featured)
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full">
                                        Featured
                                    </span>
                                    @endif
                                </div>
                                
                                <h3 class="text-xl font-bold text-gray-900 mb-2 hover:text-blue-600 line-clamp-2">
                                    <a href="{{ route('products.show', $post->slug) }}">{{ $post->title }}</a>
                                </h3>
                                
                                @if($post->excerpt)
                                <p class="text-gray-600 mb-4 text-sm line-clamp-3">{{ $post->excerpt }}</p>
                                @endif
                                
                                <div class="flex items-center text-xs text-gray-500 mb-4">
                                    <span>{{ $post->author->name }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $post->published_at->format('M d, Y') }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500">{{ $post->reading_time_text }}</span>
                                    <a href="{{ route('products.show', $post->slug) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                        Read More →
                                    </a>
                                </div>
                            </div>
                        </article>
                        @empty
                    <div class="bg-white rounded-lg shadow-md p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="mt-4 text-gray-500 text-lg">No posts found in this category yet.</p>
                        <a href="{{ route('blog.index') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800">
                            Browse all posts →
                        </a>
                    </div>
                    @endforelse
                    </div>
                </div>

                <!-- Pagination -->
                @if($posts->hasPages())
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Blog - Wellness Hub')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Sidebar - Collapsible -->
            <x-blog.sidebar 
                title="Wellness Hub"
                subtitle="Health & Lifestyle Blog"
                :categories="$categories"
            />

            <!-- Main Content -->
            <div class="lg:col-span-9">
                <!-- Search, Filter & View Mode Bar -->
                <div class="bg-white rounded-lg shadow-sm mb-6 p-6" x-data="{ viewMode: 'grid' }">
                    <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                        <!-- Search Form -->
                        <form action="{{ route('blog.search') }}" method="GET" class="flex-1 w-full lg:max-w-md">
                            <div class="relative">
                                <input type="text" 
                                       name="q" 
                                       value="{{ request('q') }}"
                                       placeholder="Search all posts..." 
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </form>

                        <div class="flex items-center gap-3 w-full lg:w-auto">
                            <!-- Sort Filter -->
                            <select name="sort" 
                                    onchange="window.location.href='{{ route('blog.index') }}?sort=' + this.value + '{{ request('q') ? '&q=' . request('q') : '' }}'"
                                    class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest First</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                                <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title (A-Z)</option>
                            </select>

                            <!-- Per Page Filter -->
                            <select name="per_page" 
                                    onchange="window.location.href='{{ route('blog.index') }}?per_page=' + this.value + '{{ request('q') ? '&q=' . request('q') : '' }}{{ request('sort') ? '&sort=' . request('sort') : '' }}'"
                                    class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 per page</option>
                                <option value="20" {{ request('per_page', 10) == 20 ? 'selected' : '' }}>20 per page</option>
                                <option value="30" {{ request('per_page', 10) == 30 ? 'selected' : '' }}>30 per page</option>
                                <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50 per page</option>
                            </select>

                            <!-- Post Counter -->
                            <div class="px-4 py-2.5 bg-green-50 border border-green-200 rounded-lg text-sm font-medium text-green-700 whitespace-nowrap">
                                {{ $posts->total() }} {{ Str::plural('Post', $posts->total()) }}
                            </div>

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
                    @if(request('q') || request('sort'))
                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        <span class="text-sm text-gray-600">Active filters:</span>
                        @if(request('q'))
                        <span class="inline-flex items-center gap-1 bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">
                            Search: "{{ request('q') }}"
                            <a href="{{ route('blog.index') }}?{{ http_build_query(array_filter(request()->except('q'))) }}" 
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
                            <a href="{{ route('blog.index') }}?{{ http_build_query(array_filter(request()->except('sort'))) }}" 
                               class="hover:text-blue-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </a>
                        </span>
                        @endif
                        <a href="{{ route('blog.index') }}" class="text-sm text-red-600 hover:text-red-800 font-medium">
                            Clear all
                        </a>
                    </div>
                    @endif

                <!-- Posts List/Grid -->
                <div class="pt-6">
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
                                    @if($post->category)
                                    <a href="{{ route('blog.category', $post->category->slug) }}" 
                                       class="inline-block bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full mb-3 hover:bg-gray-200">
                                        {{ $post->category->name }}
                                    </a>
                                    @endif
                                    
                                    <h3 class="text-2xl font-bold text-gray-900 mb-2 hover:text-blue-600">
                                        <a href="{{ route('products.show', $post->slug) }}">{{ $post->title }}</a>
                                    </h3>
                                    
                                    <p class="text-gray-600 mb-4">{{ Str::limit($post->excerpt, 150) }}</p>
                                    
                                    <!-- Tick Marks -->
                                    @if($post->hasTickMarks())
                                    <div class="mb-3">
                                        <x-blog.tick-marks :post="$post" />
                                    </div>
                                    @endif
                                    
                                    <!-- Mobile-Optimized Metadata & Actions -->
                                    <div class="space-y-3">
                                        <!-- Author & Date Info -->
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 text-sm text-gray-500">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                                    {{ substr($post->author->name, 0, 1) }}
                                                </div>
                                                <span class="font-medium">{{ $post->author->name }}</span>
                                            </div>
                                            <div class="flex items-center gap-4 text-xs sm:text-sm">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $post->published_at->format('M d, Y') }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $post->reading_time_text }}
                                                </span>
                                                @if($post->views_count > 0)
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    {{ number_format($post->views_count) }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Read More Button -->
                                        <div class="pt-2">
                                            <a href="{{ route('products.show', $post->slug) }}" 
                                               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors">
                                                <span>Read More</span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                        @empty
                        <div class="bg-white rounded-lg shadow-md p-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="mt-4 text-gray-500 text-lg">No posts found</p>
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
                                    @if($post->category)
                                    <a href="{{ route('blog.category', $post->category->slug) }}" 
                                       class="inline-block bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full hover:bg-gray-200">
                                        {{ $post->category->name }}
                                    </a>
                                    @endif
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
                                
                                <!-- Tick Marks -->
                                @if($post->hasTickMarks())
                                <div class="mb-3">
                                    <x-blog.tick-marks :post="$post" />
                                </div>
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
                            <p class="mt-4 text-gray-500 text-lg">No posts found</p>
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

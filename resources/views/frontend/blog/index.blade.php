@extends('layouts.app')

@section('title', 'Blog')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Our Blog</h1>
            <p class="text-xl text-blue-100">Insights, tips, and stories from our team</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Featured Posts -->
                @if($featuredPosts->count() > 0)
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Featured Posts</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($featuredPosts as $featured)
                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                            @if($featured->featured_image)
                            <img src="{{ asset('storage/' . $featured->featured_image) }}" 
                                 alt="{{ $featured->featured_image_alt }}" 
                                 class="w-full h-48 object-cover">
                            @endif
                            <div class="p-6">
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full mb-3">
                                    Featured
                                </span>
                                <h3 class="text-xl font-bold text-gray-900 mb-2 hover:text-blue-600">
                                    <a href="{{ route('products.show', $featured->slug) }}">{{ $featured->title }}</a>
                                </h3>
                                <p class="text-gray-600 mb-4">{{ Str::limit($featured->excerpt, 100) }}</p>
                                <div class="flex items-center text-sm text-gray-500">
                                    <span>{{ $featured->author->name }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $featured->published_at->format('M d, Y') }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $featured->reading_time_text }}</span>
                                </div>
                            </div>
                        </article>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- All Posts -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Latest Posts</h2>
                    <div class="space-y-6">
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
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center text-sm text-gray-500">
                                            <span>{{ $post->author->name }}</span>
                                            <span class="mx-2">•</span>
                                            <span>{{ $post->published_at->format('M d, Y') }}</span>
                                            <span class="mx-2">•</span>
                                            <span>{{ $post->reading_time_text }}</span>
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
                            <p class="mt-4 text-gray-500">No posts found</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($posts->hasPages())
                    <div class="mt-8">
                        {{ $posts->links() }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Search -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Search</h3>
                    <form action="{{ route('blog.search') }}" method="GET">
                        <div class="flex">
                            <input type="text" name="q" placeholder="Search posts..." 
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-r-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Categories -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Categories</h3>
                    <ul class="space-y-2">
                        @foreach($categories as $category)
                        <li>
                            <a href="{{ route('blog.category', $category->slug) }}" 
                               class="flex items-center justify-between text-gray-700 hover:text-blue-600">
                                <span>{{ $category->name }}</span>
                                <span class="text-sm text-gray-500">{{ $category->published_posts_count }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Popular Posts -->
                @if($popularPosts->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Popular Posts</h3>
                    <div class="space-y-4">
                        @foreach($popularPosts as $popular)
                        <div class="flex space-x-3">
                            @if($popular->featured_image)
                            <img src="{{ asset('storage/' . $popular->featured_image) }}" 
                                 alt="{{ $popular->title }}" 
                                 class="w-16 h-16 rounded object-cover">
                            @endif
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900 hover:text-blue-600 mb-1">
                                    <a href="{{ route('products.show', $popular->slug) }}">
                                        {{ Str::limit($popular->title, 50) }}
                                    </a>
                                </h4>
                                <p class="text-xs text-gray-500">{{ number_format($popular->views_count) }} views</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Tags -->
                @if($popularTags->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Popular Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($popularTags as $tag)
                        <a href="{{ route('blog.tag', $tag->slug) }}" 
                           class="inline-block bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 text-sm px-3 py-1 rounded-full transition duration-150">
                            {{ $tag->name }} ({{ $tag->posts_count }})
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Tag: ' . $tag->name . ' - Blog')
@section('meta_description', $tag->description ?? 'Posts tagged with ' . $tag->name)

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Tag Header -->
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl">
                <nav class="text-sm mb-4">
                    <a href="{{ route('home') }}" class="text-purple-100 hover:text-white">Home</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('blog.index') }}" class="text-purple-100 hover:text-white">Blog</a>
                    <span class="mx-2">/</span>
                    <span class="text-white">Tag: {{ $tag->name }}</span>
                </nav>
                
                <div class="flex items-center gap-3 mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <h1 class="text-4xl md:text-5xl font-bold">{{ $tag->name }}</h1>
                </div>
                
                @if($tag->description)
                <p class="text-xl text-purple-100">{{ $tag->description }}</p>
                @endif
                
                <div class="mt-6 flex items-center gap-4 text-sm">
                    <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full">
                        {{ $posts->total() }} {{ Str::plural('Article', $posts->total()) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="space-y-6 pt-6">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <p class="mt-4 text-gray-500 text-lg">No posts found with this tag yet.</p>
                        <a href="{{ route('blog.index') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800">
                            Browse all posts →
                        </a>
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
                                @if($category->published_posts_count > 0)
                                <span class="text-sm text-gray-500">{{ $category->published_posts_count }}</span>
                                @endif
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Back to All Posts -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg shadow-md p-6 border border-purple-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Browse All Posts</h3>
                    <p class="text-sm text-gray-600 mb-4">Explore articles from all categories</p>
                    <a href="{{ route('blog.index') }}" 
                       class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-800 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        View All Posts
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

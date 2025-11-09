@extends('layouts.app')

@section('title', 'Search Results: ' . $query . ' - Blog')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Search Header -->
    <div class="bg-gradient-to-r from-gray-700 to-gray-900 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl">
                <nav class="text-sm mb-4">
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-white">Home</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('blog.index') }}" class="text-gray-300 hover:text-white">Blog</a>
                    <span class="mx-2">/</span>
                    <span class="text-white">Search Results</span>
                </nav>
                
                <div class="flex items-center gap-3 mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <h1 class="text-4xl md:text-5xl font-bold">Search Results</h1>
                </div>
                
                <p class="text-xl text-gray-300 mb-6">
                    Showing results for: <span class="text-white font-semibold">"{{ $query }}"</span>
                </p>
                
                <!-- Search Form -->
                <form action="{{ route('blog.search') }}" method="GET" class="max-w-2xl">
                    <div class="flex">
                        <input type="text" 
                               name="q" 
                               value="{{ $query }}"
                               placeholder="Search posts..." 
                               class="flex-1 px-6 py-4 text-gray-900 border-0 rounded-l-lg focus:ring-2 focus:ring-blue-500">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-r-lg font-medium">
                            Search
                        </button>
                    </div>
                </form>
                
                <div class="mt-6">
                    <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm">
                        {{ $posts->total() }} {{ Str::plural('Result', $posts->total()) }} Found
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
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
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="mt-4 text-xl font-semibold text-gray-900">No results found</h3>
                        <p class="mt-2 text-gray-500">We couldn't find any posts matching "{{ $query }}"</p>
                        <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="{{ route('blog.index') }}" 
                               class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                                Browse All Posts
                            </a>
                            <button onclick="document.querySelector('input[name=q]').focus()" 
                                    class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium">
                                Try Another Search
                            </button>
                        </div>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($posts->hasPages())
                <div class="mt-8">
                    {{ $posts->appends(['q' => $query])->links() }}
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Search Tips -->
                <div class="bg-blue-50 rounded-lg shadow-md p-6 border border-blue-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Search Tips</h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Use specific keywords</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Try different word variations</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Check spelling</span>
                        </li>
                    </ul>
                </div>

                <!-- Categories -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Browse by Category</h3>
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

                <!-- Back to Blog -->
                <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-lg shadow-md p-6 border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Browse All Posts</h3>
                    <p class="text-sm text-gray-600 mb-4">Explore our complete collection of articles</p>
                    <a href="{{ route('blog.index') }}" 
                       class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
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

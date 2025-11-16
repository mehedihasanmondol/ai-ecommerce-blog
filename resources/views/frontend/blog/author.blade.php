@extends('layouts.app')

@section('title', $author->name . ' - Author Profile')
@section('meta_description', $author->authorProfile?->bio ? \Illuminate\Support\Str::limit($author->authorProfile->bio, 155) : 'View all posts by ' . $author->name)

@section('content')
<div class="bg-gradient-to-b from-gray-50 to-white min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Sidebar -->
            <x-blog.sidebar 
                title="{{ \App\Models\SiteSetting::get('blog_title', 'Wellness Hub') }}"
                subtitle="{{ \App\Models\SiteSetting::get('blog_tagline', 'Health & Lifestyle Blog') }}"
                :categories="$categories"
                :currentCategory="null"
            />

            <!-- Main Content -->
            <div class="lg:col-span-9">
                <!-- Author Header Card -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
                    <!-- Cover Background -->
                    <div class="h-16"></div>
                    
                    <!-- Author Info -->
                    <div class="px-8 pb-8">
                        <div class="flex flex-col md:flex-row items-start md:items-end gap-6 -mt-16">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                @if($author->authorProfile?->avatar_or_fallback)
                                    <img src="{{ asset('storage/' . $author->authorProfile->avatar_or_fallback) }}" 
                                         alt="{{ $author->name }}"
                                         class="w-32 h-32 rounded-full border-4 border-white shadow-xl object-cover">
                                @else
                                    <div class="w-32 h-32 rounded-full border-4 border-white shadow-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-4xl font-bold">
                                        {{ substr($author->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>

                            <!-- Name and Title -->
                            <div class="flex-1">
                                <div class="flex items-center justify-between gap-4 mb-1">
                                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">{{ $author->name }}</h1>
                                    
                                   
                                </div>
                                @if($author->authorProfile?->job_title)
                                    <p class="text-lg text-gray-600 mb-3">{{ $author->authorProfile->job_title }}</p>
                                @endif
                                
                                <!-- Social Links -->
                                @if($author->authorProfile && $author->authorProfile->hasSocialLinks())
                                    <div class="flex flex-wrap gap-3 mt-4">
                                        @if($author->authorProfile->website)
                                            <a href="{{ $author->authorProfile->website }}" target="_blank" rel="noopener noreferrer" 
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                                </svg>
                                                Website
                                            </a>
                                        @endif
                                        @if($author->authorProfile->twitter)
                                            <a href="https://twitter.com/{{ $author->authorProfile->twitter }}" target="_blank" rel="noopener noreferrer"
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-sky-100 hover:bg-sky-200 text-sky-700 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path>
                                                </svg>
                                                Twitter
                                            </a>
                                        @endif
                                        @if($author->authorProfile->facebook)
                                            <a href="https://facebook.com/{{ $author->authorProfile->facebook }}" target="_blank" rel="noopener noreferrer"
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M9.101 23.691v-7.98H6.627v-3.667h2.474v-1.58c0-4.085 1.848-5.978 5.858-5.978.401 0 .955.042 1.468.103a8.68 8.68 0 0 1 1.141.195v3.325a8.623 8.623 0 0 0-.653-.036 26.805 26.805 0 0 0-.733-.009c-.707 0-1.259.096-1.675.309a1.686 1.686 0 0 0-.679.622c-.258.42-.374.995-.374 1.752v1.297h3.919l-.386 3.667h-3.533v7.98H9.101z"></path>
                                                </svg>
                                                Facebook
                                            </a>
                                        @endif
                                        @if($author->authorProfile->linkedin)
                                            <a href="https://linkedin.com/in/{{ $author->authorProfile->linkedin }}" target="_blank" rel="noopener noreferrer"
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-800 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path>
                                                </svg>
                                                LinkedIn
                                            </a>
                                        @endif
                                        @if($author->authorProfile->instagram)
                                            <a href="https://instagram.com/{{ $author->authorProfile->instagram }}" target="_blank" rel="noopener noreferrer"
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-pink-100 hover:bg-pink-200 text-pink-700 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"></path>
                                                </svg>
                                                Instagram
                                            </a>
                                        @endif
                                        @if($author->authorProfile->github)
                                            <a href="https://github.com/{{ $author->authorProfile->github }}" target="_blank" rel="noopener noreferrer"
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"></path>
                                                </svg>
                                                GitHub
                                            </a>
                                        @endif
                                        @if($author->authorProfile->youtube)
                                            <a href="https://youtube.com/@{{ $author->authorProfile->youtube }}" target="_blank" rel="noopener noreferrer"
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"></path>
                                                </svg>
                                                YouTube
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Bio -->
                        @if($author->authorProfile?->bio)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    About {{ $author->name }}
                                </h3>
                                <p class="text-gray-700 leading-relaxed">{{ $author->authorProfile->bio }}</p>
                            </div>
                        @endif

                        <!-- Stats -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 text-center">
                                    <div class="text-3xl font-bold text-blue-600 mb-1">{{ number_format($totalPosts) }}</div>
                                    <div class="text-sm text-gray-600 font-medium">Articles Published</div>
                                </div>
                                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 text-center">
                                    <div class="text-3xl font-bold text-purple-600 mb-1">{{ number_format($totalViews) }}</div>
                                    <div class="text-sm text-gray-600 font-medium">Total Views</div>
                                </div>
                                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 text-center">
                                    <div class="text-3xl font-bold text-green-600 mb-1">{{ number_format($totalComments) }}</div>
                                    <div class="text-sm text-gray-600 font-medium">Comments Received</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Posts Section Header - Compact & with Sorting -->
                <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white rounded-lg shadow-sm p-4">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                        <span>Articles <span class="text-gray-500 font-normal">({{ number_format($totalPosts) }})</span></span>
                    </h2>
                    
                    <!-- Sorting Dropdown -->
                    <div class="flex items-center gap-2">
                        <label for="sort" class="text-sm font-medium text-gray-700 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                            </svg>
                            Sort:
                        </label>
                        <select id="sort" 
                                onchange="window.location.href='{{ route('blog.author', $author->id) }}?sort=' + this.value"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white cursor-pointer">
                            <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="most_viewed" {{ $sort === 'most_viewed' ? 'selected' : '' }}>Most Viewed</option>
                            <option value="most_popular" {{ $sort === 'most_popular' ? 'selected' : '' }}>Most Popular</option>
                        </select>
                    </div>
                </div>

                @if($posts->count() > 0)
                    <!-- Posts Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        @foreach($posts as $post)
                            <article class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden group">
                                <!-- Media Slider (Image + YouTube Video) -->
                                @if($post->featured_image && $post->youtube_url)
                                    <!-- Slider with both image and video -->
                                    <div class="aspect-video overflow-hidden relative media-slider" id="slider-{{ $post->id }}">
                                        <div class="slider-container relative w-full h-full">
                                            <!-- Featured Image Slide -->
                                            <div class="slider-slide active absolute inset-0 transition-opacity duration-500">
                                                <img src="{{ asset('storage/' . $post->featured_image) }}" 
                                                     alt="{{ $post->featured_image_alt }}"
                                                     class="w-full h-full object-cover">
                                            </div>
                                            
                                            <!-- YouTube Video Slide -->
                                            <div class="slider-slide absolute inset-0 opacity-0 transition-opacity duration-500">
                                                <iframe src="{{ $post->youtube_embed_url }}" 
                                                        class="w-full h-full"
                                                        frameborder="0" 
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                        allowfullscreen
                                                        loading="lazy">
                                                </iframe>
                                            </div>
                                        </div>
                                        
                                        <!-- Slider Navigation -->
                                        <div class="absolute bottom-3 right-3 flex gap-2 z-10">
                                            <button type="button" onclick="changeSlide({{ $post->id }}, 'prev')" 
                                                    class="bg-white/90 hover:bg-white p-2 rounded-full shadow-lg transition-all hover:scale-110">
                                                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                </svg>
                                            </button>
                                            <button type="button" onclick="changeSlide({{ $post->id }}, 'next')" 
                                                    class="bg-white/90 hover:bg-white p-2 rounded-full shadow-lg transition-all hover:scale-110">
                                                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <!-- Slide Indicators -->
                                        <div class="absolute bottom-3 left-3 flex gap-2 z-10">
                                            <div class="w-2 h-2 rounded-full bg-white shadow-lg" data-indicator="0"></div>
                                            <div class="w-2 h-2 rounded-full bg-white/50 shadow-lg" data-indicator="1"></div>
                                        </div>
                                    </div>
                                @elseif($post->featured_image)
                                    <!-- Only Featured Image -->
                                    <div class="aspect-video overflow-hidden">
                                        <img src="{{ asset('storage/' . $post->featured_image) }}" 
                                             alt="{{ $post->featured_image_alt }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    </div>
                                @elseif($post->youtube_url)
                                    <!-- Only YouTube Video -->
                                    <div class="aspect-video overflow-hidden">
                                        <iframe src="{{ $post->youtube_embed_url }}" 
                                                class="w-full h-full"
                                                frameborder="0" 
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                allowfullscreen
                                                loading="lazy">
                                        </iframe>
                                    </div>
                                @else
                                    <!-- Placeholder -->
                                    <div class="aspect-video bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif

                                <div class="p-5">
                                    <!-- Category -->
                                    @if($post->category)
                                        <a href="{{ route('blog.category', $post->category->slug) }}" 
                                           class="inline-block text-xs font-semibold text-blue-600 hover:text-blue-800 mb-2">
                                            {{ $post->category->name }}
                                        </a>
                                    @endif

                                    <!-- Title -->
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                        <a href="{{ route('products.show', $post->slug) }}">
                                            {{ $post->title }}
                                        </a>
                                    </h3>

                                    <!-- Excerpt -->
                                    @if($post->excerpt)
                                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $post->excerpt }}</p>
                                    @endif

                                    <!-- Meta -->
                                    <div class="flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $post->published_at->format('M d, Y') }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            {{ number_format($post->views_count) }}
                                        </span>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $posts->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                        <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No Articles Yet</h3>
                        <p class="text-gray-600">{{ $author->name }} hasn't published any articles yet. Check back soon!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Media Slider Functionality
    const sliders = {};
    
    function changeSlide(postId, direction) {
        const slider = document.getElementById('slider-' + postId);
        if (!slider) return;
        
        const slides = slider.querySelectorAll('.slider-slide');
        const indicators = slider.querySelectorAll('[data-indicator]');
        
        // Initialize slider state if not exists
        if (!sliders[postId]) {
            sliders[postId] = { currentSlide: 0 };
        }
        
        // Hide current slide
        slides[sliders[postId].currentSlide].classList.remove('active');
        slides[sliders[postId].currentSlide].classList.add('opacity-0');
        indicators[sliders[postId].currentSlide].classList.remove('bg-white');
        indicators[sliders[postId].currentSlide].classList.add('bg-white/50');
        
        // Calculate next slide
        if (direction === 'next') {
            sliders[postId].currentSlide = (sliders[postId].currentSlide + 1) % slides.length;
        } else {
            sliders[postId].currentSlide = (sliders[postId].currentSlide - 1 + slides.length) % slides.length;
        }
        
        // Show new slide
        slides[sliders[postId].currentSlide].classList.add('active');
        slides[sliders[postId].currentSlide].classList.remove('opacity-0');
        indicators[sliders[postId].currentSlide].classList.add('bg-white');
        indicators[sliders[postId].currentSlide].classList.remove('bg-white/50');
    }
    
    // Auto-play slider (optional - switches every 5 seconds)
    document.addEventListener('DOMContentLoaded', function() {
        const mediaSliders = document.querySelectorAll('.media-slider');
        
        mediaSliders.forEach(slider => {
            const postId = slider.id.replace('slider-', '');
            sliders[postId] = { currentSlide: 0 };
            
            // Auto-advance every 5 seconds
            setInterval(() => {
                changeSlide(postId, 'next');
            }, 5000);
        });
    });
</script>
@endpush

@endsection

@props([
    'post',
    'showSlider' => true,
    'class' => ''
])

<article class="bg-white overflow-hidden  group {{ $class }}">
    <!-- Media Section (Image/Video) -->
    <a href="{{ route('products.show', $post->slug) }}" class="block">
        @if($showSlider && $post->featured_image && $post->youtube_url)
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
    </a>

    <!-- Content Section -->
    <div class="p-5">
        <!-- Category Badge -->
        @if($post->category)
            <a href="{{ route('blog.category', $post->category->slug) }}" 
               class="inline-block text-xs font-semibold text-blue-600 hover:text-blue-800 mb-2 transition-colors">
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

        <!-- Tick Marks -->
        @if($post->tickMarks && $post->tickMarks->count() > 0)
            <div class="mb-3">
                <x-blog.tick-marks :post="$post" />
            </div>
        @endif

        <!-- Meta Info -->
        <div class="flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100 mb-3">
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

        <!-- Read More Button -->
        <a href="{{ route('products.show', $post->slug) }}" 
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors w-full justify-center">
            <span>Read More</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>
</article>

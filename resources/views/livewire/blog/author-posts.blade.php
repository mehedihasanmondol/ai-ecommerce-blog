<div>
    <!-- Filter & Sort Section -->
    <div class=" flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white  p-4 border-t-1 border-gray-200">
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
            <select wire:model.live="sort"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white cursor-pointer">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="most_viewed">Most Viewed</option>
                <option value="most_popular">Most Popular</option>
            </select>
        </div>
    </div>

    @if($posts->count() > 0)
        <!-- Posts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8 bg-gray-50 pt-2 pb-4  ">
            @foreach($posts as $post)
                <article class="bg-white  overflow-hidden group">
                    <a href="{{ route('products.show', $post->slug) }}">
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
                    </a>

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
            <p class="text-gray-600">No articles have been published yet. Check back soon!</p>
        </div>
    @endif
</div>

@script
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
    document.addEventListener('livewire:navigated', function() {
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
@endscript

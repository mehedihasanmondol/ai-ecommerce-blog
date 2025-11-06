@props(['products'])

@if($products->count() > 0)
<section class="py-8 bg-white border-t border-b border-gray-200">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Trending now</h2>
            <a href="#" class="text-blue-600 hover:text-blue-700 font-medium text-sm hover:underline">
                Shop all
            </a>
        </div>

        <!-- Products Slider -->
        <div class="relative">
            <!-- Navigation Buttons -->
            <button 
                id="trending-prev" 
                class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 z-10 bg-white shadow-lg rounded-full p-2 hover:bg-gray-50 transition hidden md:block"
                aria-label="Previous"
            >
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            
            <button 
                id="trending-next" 
                class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 z-10 bg-white shadow-lg rounded-full p-2 hover:bg-gray-50 transition hidden md:block"
                aria-label="Next"
            >
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <!-- Products Container -->
            <div class="overflow-hidden">
                <div id="trending-slider" class="flex gap-4 transition-transform duration-300 ease-in-out">
                    @foreach($products as $product)
                        <div class="flex-none w-[calc(100%-1rem)] sm:w-[calc(50%-0.5rem)] md:w-[calc(33.333%-0.667rem)] lg:w-[calc(20%-0.8rem)]">
                            <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="block group">
                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                                    <!-- Product Image -->
                                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                                        @if($product->image_url)
                                            <img 
                                                src="{{ asset('storage/' . $product->image_url) }}" 
                                                alt="{{ $product->name }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                            >
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        <!-- Discount Badge -->
                                        @if($product->discount_percentage > 0)
                                            <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                                -{{ $product->discount_percentage }}%
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="p-3">
                                        <!-- Brand -->
                                        @if($product->brand)
                                            <p class="text-xs text-gray-500 mb-1">{{ $product->brand->name }}</p>
                                        @endif

                                        <!-- Product Name -->
                                        <h3 class="text-sm font-medium text-gray-900 line-clamp-2 mb-2 group-hover:text-green-600 transition">
                                            {{ $product->name }}
                                        </h3>

                                        <!-- Rating -->
                                        <div class="flex items-center mb-2">
                                            <div class="flex text-yellow-400">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= 4)
                                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                        </svg>
                                                    @else
                                                        <svg class="w-3 h-3 fill-current text-gray-300" viewBox="0 0 20 20">
                                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                        </svg>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="text-xs text-gray-600 ml-1">
                                                {{ number_format(rand(1000, 50000)) }}
                                            </span>
                                        </div>

                                        <!-- Price -->
                                        <div class="flex items-baseline space-x-2">
                                            @if($product->sale_price)
                                                <span class="text-lg font-bold text-red-600">
                                                    ${{ number_format($product->sale_price, 2) }}
                                                </span>
                                                <span class="text-sm text-gray-400 line-through">
                                                    ${{ number_format($product->price, 2) }}
                                                </span>
                                            @else
                                                <span class="text-lg font-bold text-gray-900">
                                                    ${{ number_format($product->price, 2) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('trending-slider');
        const prevBtn = document.getElementById('trending-prev');
        const nextBtn = document.getElementById('trending-next');
        
        if (!slider || !prevBtn || !nextBtn) return;

        let currentPosition = 0;
        const itemWidth = slider.children[0]?.offsetWidth || 0;
        const gap = 16; // 1rem = 16px
        const scrollAmount = itemWidth + gap;
        const maxScroll = slider.scrollWidth - slider.parentElement.offsetWidth;

        function updateButtons() {
            prevBtn.style.opacity = currentPosition <= 0 ? '0.5' : '1';
            prevBtn.style.pointerEvents = currentPosition <= 0 ? 'none' : 'auto';
            
            nextBtn.style.opacity = currentPosition >= maxScroll ? '0.5' : '1';
            nextBtn.style.pointerEvents = currentPosition >= maxScroll ? 'none' : 'auto';
        }

        prevBtn.addEventListener('click', () => {
            currentPosition = Math.max(0, currentPosition - scrollAmount);
            slider.style.transform = `translateX(-${currentPosition}px)`;
            updateButtons();
        });

        nextBtn.addEventListener('click', () => {
            currentPosition = Math.min(maxScroll, currentPosition + scrollAmount);
            slider.style.transform = `translateX(-${currentPosition}px)`;
            updateButtons();
        });

        // Initialize button states
        updateButtons();

        // Update on window resize
        window.addEventListener('resize', () => {
            currentPosition = 0;
            slider.style.transform = 'translateX(0)';
            updateButtons();
        });
    });
</script>
@endpush
@endif

@props(['products'])

@if($products->count() > 0)
<div class="bg-white py-8">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Inspired by your browsing</h2>
        
        <!-- Products Carousel -->
        <div class="relative">
            <!-- Navigation Buttons -->
            <button 
                onclick="scrollCarousel('inspired-browsing', 'left')" 
                class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-gray-50 transition-all border border-gray-200"
                aria-label="Previous products"
            >
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            
            <button 
                onclick="scrollCarousel('inspired-browsing', 'right')" 
                class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-gray-50 transition-all border border-gray-200"
                aria-label="Next products"
            >
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <!-- Products Grid -->
            <div 
                id="inspired-browsing" 
                class="flex gap-4 overflow-x-auto scroll-smooth pb-4 scrollbar-hide"
                style="scrollbar-width: none; -ms-overflow-style: none;"
            >
                @foreach($products as $product)
                    @php
                        $variant = $product->variants->where('is_default', true)->first() ?? $product->variants->first();
                        $image = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                        $averageRating = 4.5; // Placeholder - will be dynamic when reviews are implemented
                        $totalReviews = rand(100, 50000); // Placeholder
                    @endphp
                    
                    <div class="flex-none w-[200px] group">
                        <a href="{{ route('products.show', $product->slug) }}" class="block">
                            <!-- Product Image -->
                            <div class="relative bg-white border border-gray-200 rounded-lg overflow-hidden mb-3 aspect-square">
                                @if($image)
                                    <img 
                                        src="{{ Storage::url($image->image_path) }}" 
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-300"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Sale Badge -->
                                @if($variant && $variant->sale_price && $variant->sale_price < $variant->price)
                                    <div class="absolute top-2 left-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-red-600 text-white">
                                            SALE
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Product Info -->
                            <div class="space-y-1">
                                <!-- Brand -->
                                @if($product->brand)
                                    <div class="text-xs text-gray-600 truncate">
                                        {{ $product->brand->name }}
                                    </div>
                                @endif
                                
                                <!-- Product Name -->
                                <h3 class="text-sm font-medium text-gray-900 line-clamp-2 group-hover:text-blue-600 transition-colors min-h-[40px]">
                                    {{ $product->name }}
                                </h3>
                                
                                <!-- Rating -->
                                <div class="flex items-center space-x-1">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($averageRating))
                                                <svg class="w-3 h-3 text-orange-400 fill-current" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @elseif($i - 0.5 <= $averageRating)
                                                <svg class="w-3 h-3 text-orange-400" viewBox="0 0 20 20">
                                                    <defs>
                                                        <linearGradient id="half-inspired-{{ $product->id }}-{{ $i }}">
                                                            <stop offset="50%" stop-color="currentColor"/>
                                                            <stop offset="50%" stop-color="#D1D5DB"/>
                                                        </linearGradient>
                                                    </defs>
                                                    <path fill="url(#half-inspired-{{ $product->id }}-{{ $i }})" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @else
                                                <svg class="w-3 h-3 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-xs text-gray-600">{{ number_format($totalReviews) }}</span>
                                </div>
                                
                                <!-- Price -->
                                @if($variant)
                                    <div class="flex items-baseline space-x-1">
                                        @if($variant->sale_price && $variant->sale_price < $variant->price)
                                            <span class="text-base font-bold text-red-600">
                                                ${{ number_format($variant->sale_price, 2) }}
                                            </span>
                                            <span class="text-xs text-gray-500 line-through">
                                                ${{ number_format($variant->price, 2) }}
                                            </span>
                                        @else
                                            <span class="text-base font-bold text-gray-900">
                                                ${{ number_format($variant->price, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Carousel Scroll Script -->
<script>
function scrollCarousel(carouselId, direction) {
    const carousel = document.getElementById(carouselId);
    const scrollAmount = 220; // Card width (200px) + gap (20px)
    
    if (direction === 'left') {
        carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    } else {
        carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }
}

// Hide scrollbar for webkit browsers
document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.textContent = `
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
    `;
    document.head.appendChild(style);
});
</script>

<!-- Custom Styles -->
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endif

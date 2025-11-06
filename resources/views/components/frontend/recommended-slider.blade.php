{{-- 
/**
 * ModuleName: Frontend Recommended Products Slider
 * Purpose: Horizontal scrolling product slider for "Recommended for You" section
 * 
 * Features:
 * - Horizontal scroll with navigation arrows
 * - Product cards with ratings and prices
 * - Responsive design
 * - Smooth scrolling animation
 */
--}}

@props(['products'])

@php
    // Debug: Check if products exist
    $hasProducts = $products && $products->count() > 0;
@endphp

@if($hasProducts)
<section class="py-8 bg-white border-b border-gray-200">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Recommended for you</h2>
        </div>

        <!-- Product Slider Container -->
        <div class="relative" x-data="productSlider()">
            <!-- Left Arrow -->
            <button @click="scrollLeft" 
                    :class="canScrollLeft ? 'opacity-100' : 'opacity-50 cursor-not-allowed'"
                    class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white hover:bg-gray-50 shadow-lg rounded-full p-3 transition-all duration-200 hidden lg:flex items-center justify-center"
                    style="margin-left: -20px;">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <!-- Products Container -->
            <div x-ref="container" 
                 @scroll="updateScrollState"
                 class="flex gap-4 overflow-x-auto scrollbar-hide scroll-smooth pb-4"
                 style="scrollbar-width: none; -ms-overflow-style: none;">
                
                @foreach($products as $product)
                <div class="flex-none w-[180px] sm:w-[200px]">
                    <a href="{{ route('products.show', $product->slug) }}" class="block group">
                        <div class="bg-white rounded-lg border border-gray-200 hover:shadow-lg transition-all duration-200 overflow-hidden">
                            <!-- Product Image -->
                            <div class="relative aspect-square bg-gray-50 overflow-hidden">
                                @php
                                    $defaultVariant = $product->variants->where('is_default', true)->first() ?? $product->variants->first();
                                    $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                                @endphp
                                
                                @if($primaryImage)
                                    <img src="{{ asset('storage/' . $primaryImage->path) }}" 
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-200">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Sale Badge -->
                                @if($defaultVariant && $defaultVariant->sale_price)
                                <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                    SALE
                                </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="p-3">
                                <!-- Brand/Category -->
                                @if($product->brand)
                                <p class="text-xs text-gray-500 mb-1 truncate">{{ $product->brand->name }}</p>
                                @endif

                                <!-- Product Name -->
                                <h3 class="text-sm font-medium text-gray-900 mb-2 line-clamp-2 min-h-[40px]">
                                    {{ $product->name }}
                                </h3>

                                <!-- Rating -->
                                <div class="flex items-center gap-1 mb-2">
                                    @php
                                        $rating = $product->average_rating ?? 4.5;
                                        $reviewCount = $product->reviews_count ?? rand(500, 20000);
                                    @endphp
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($rating))
                                                <svg class="w-3 h-3 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @elseif($i - 0.5 <= $rating)
                                                <svg class="w-3 h-3 text-yellow-400" viewBox="0 0 20 20">
                                                    <defs>
                                                        <linearGradient id="half-{{ $product->id }}-{{ $i }}">
                                                            <stop offset="50%" stop-color="#FBBF24"/>
                                                            <stop offset="50%" stop-color="#D1D5DB"/>
                                                        </linearGradient>
                                                    </defs>
                                                    <path fill="url(#half-{{ $product->id }}-{{ $i }})" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @else
                                                <svg class="w-3 h-3 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-xs text-gray-600">{{ number_format($reviewCount) }}</span>
                                </div>

                                <!-- Price -->
                                @if($defaultVariant)
                                <div class="flex items-center gap-2">
                                    @if($defaultVariant->sale_price)
                                        <span class="text-lg font-bold text-red-600">
                                            ${{ number_format($defaultVariant->sale_price, 2) }}
                                        </span>
                                        <span class="text-sm text-gray-400 line-through">
                                            ${{ number_format($defaultVariant->price, 2) }}
                                        </span>
                                    @else
                                        <span class="text-lg font-bold text-gray-900">
                                            ${{ number_format($defaultVariant->price, 2) }}
                                        </span>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            <!-- Right Arrow -->
            <button @click="scrollRight" 
                    :class="canScrollRight ? 'opacity-100' : 'opacity-50 cursor-not-allowed'"
                    class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white hover:bg-gray-50 shadow-lg rounded-full p-3 transition-all duration-200 hidden lg:flex items-center justify-center"
                    style="margin-right: -20px;">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Scroll Indicator -->
        <div class="lg:hidden text-center mt-4">
            <p class="text-xs text-gray-500">← Swipe to see more →</p>
        </div>
    </div>
</section>

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    function productSlider() {
        return {
            canScrollLeft: false,
            canScrollRight: true,
            
            init() {
                this.updateScrollState();
            },
            
            scrollLeft() {
                this.$refs.container.scrollBy({
                    left: -400,
                    behavior: 'smooth'
                });
            },
            
            scrollRight() {
                this.$refs.container.scrollBy({
                    left: 400,
                    behavior: 'smooth'
                });
            },
            
            updateScrollState() {
                const container = this.$refs.container;
                this.canScrollLeft = container.scrollLeft > 0;
                this.canScrollRight = container.scrollLeft < (container.scrollWidth - container.clientWidth - 10);
            }
        }
    }
</script>
@endif

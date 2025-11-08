@props(['products', 'title' => 'Related Products'])

@if($products->count() > 0)
<div class="bg-white rounded-lg shadow-sm p-6 lg:p-8">
    <!-- Section Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ $title }}</h2>
        @if($products->count() > 4)
            <a href="{{ route('shop') }}" class="text-green-600 hover:text-green-700 font-medium text-sm flex items-center space-x-1 transition">
                <span>View All</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @endif
    </div>

    <!-- Products Carousel -->
    <div x-data="{
        scrollContainer: null,
        canScrollLeft: false,
        canScrollRight: true,
        init() {
            this.scrollContainer = this.$refs.container;
            this.updateScrollButtons();
        },
        scroll(direction) {
            const scrollAmount = 300;
            if (direction === 'left') {
                this.scrollContainer.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            } else {
                this.scrollContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            }
            setTimeout(() => this.updateScrollButtons(), 300);
        },
        updateScrollButtons() {
            this.canScrollLeft = this.scrollContainer.scrollLeft > 0;
            this.canScrollRight = this.scrollContainer.scrollLeft < (this.scrollContainer.scrollWidth - this.scrollContainer.clientWidth - 10);
        }
    }" class="relative">
        
        <!-- Scroll Left Button -->
        <button 
            @click="scroll('left')"
            x-show="canScrollLeft"
            class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white hover:bg-gray-50 p-2 rounded-full shadow-lg transition-all"
            style="margin-left: -16px;">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <!-- Products Container -->
        <div 
            x-ref="container"
            @scroll="updateScrollButtons()"
            class="flex space-x-4 overflow-x-auto scrollbar-hide pb-4"
            style="scroll-behavior: smooth;">
            
            @foreach($products as $product)
                @php
                    $variant = $product->variants->where('is_default', true)->first() ?? $product->variants->first();
                    $image = $product->images->first();
                @endphp
                
                <!-- Product Card -->
                <div class="flex-shrink-0 w-64">
                    <a href="{{ route('products.show', $product->slug) }}" class="block group">
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            <!-- Product Image -->
                            <div class="relative aspect-square bg-gray-100 overflow-hidden">
                                @if($image)
                                    <img src="{{ asset('storage/' . $image->path) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Badges -->
                                <div class="absolute top-2 left-2 flex flex-col space-y-1">
                                    @if($product->is_featured)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Featured
                                        </span>
                                    @endif
                                    @if($variant && $variant->sale_price && $variant->sale_price < $variant->price)
                                        @php
                                            $discount = round((($variant->price - $variant->sale_price) / $variant->price) * 100);
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                            -{{ $discount }}%
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="p-4">
                                <!-- Brand -->
                                @if($product->brand)
                                    <p class="text-xs text-gray-500 mb-1">{{ $product->brand->name }}</p>
                                @endif

                                <!-- Product Name -->
                                <h3 class="text-sm font-medium text-gray-900 mb-2 line-clamp-2 group-hover:text-green-600 transition">
                                    {{ $product->name }}
                                </h3>

                                <!-- Rating (Placeholder) -->
                                <div class="flex items-center mb-2">
                                    <div class="flex">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3 h-3 {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-gray-500 ml-1">(0)</span>
                                </div>

                                <!-- Price -->
                                @if($variant)
                                    <div class="flex items-baseline space-x-2">
                                        @if($variant->sale_price && $variant->sale_price < $variant->price)
                                            <span class="text-lg font-bold text-green-600">
                                                ৳{{ number_format($variant->sale_price, 2) }}
                                            </span>
                                            <span class="text-sm text-gray-400 line-through">
                                                ৳{{ number_format($variant->price, 2) }}
                                            </span>
                                        @else
                                            <span class="text-lg font-bold text-green-600">
                                                ৳{{ number_format($variant->price, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <!-- Stock Status -->
                                @if($variant)
                                    <div class="mt-2">
                                        @if($variant->stock_quantity > 0)
                                            <span class="text-xs text-green-600 font-medium">In Stock</span>
                                        @else
                                            <span class="text-xs text-red-600 font-medium">Out of Stock</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Scroll Right Button -->
        <button 
            @click="scroll('right')"
            x-show="canScrollRight"
            class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white hover:bg-gray-50 p-2 rounded-full shadow-lg transition-all"
            style="margin-right: -16px;">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>
</div>
@endif

<style>
    /* Hide scrollbar for Chrome, Safari and Opera */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    /* Hide scrollbar for IE, Edge and Firefox */
    .scrollbar-hide {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }

    /* Line clamp utility */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

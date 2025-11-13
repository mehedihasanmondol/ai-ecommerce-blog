<!-- Grid View -->
<div x-show="viewMode === 'grid'" class="bg-white rounded-lg shadow-sm overflow-hidden group hover:shadow-md transition">
    <div class="relative aspect-square bg-gray-100">
        <a href="{{ route('products.show', $product->slug) }}">
            <img src="{{ $primaryImage ? asset('storage/' . $primaryImage->image_path) : asset('images/placeholder.png') }}" 
                 alt="{{ $product->name }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
        </a>
        
        <!-- Wishlist Button -->
        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
            @livewire('wishlist.add-to-wishlist', ['productId' => $product->id, 'variantId' => $variant->id ?? null, 'size' => 'md'], key('wishlist-grid-'.$product->id))
        </div>

        @if($variant && $variant->stock_quantity <= 0)
        <div class="absolute top-2 left-2 bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">
            Out of Stock
        </div>
        @elseif($hasDiscount)
        <div class="absolute top-2 left-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">
            SALE
        </div>
        @endif
    </div>

    <div class="p-4">
        @if($product->brand)
        <p class="text-xs text-gray-600 mb-1">{{ $product->brand->name }}</p>
        @endif
        
        <h3 class="text-sm font-medium text-gray-900 mb-2 line-clamp-2 min-h-[40px]">
            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-green-600">
                {{ $product->name }}
            </a>
        </h3>

        <!-- Rating -->
        @if($product->average_rating > 0)
        <div class="flex items-center mb-2">
            <div class="flex items-center">
                @for($i = 1; $i <= 5; $i++)
                <svg class="w-4 h-4 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                </svg>
                @endfor
            </div>
            <span class="ml-1 text-xs text-gray-600">({{ $product->review_count ?? 0 }})</span>
        </div>
        @endif

        <!-- Price -->
        <div class="mb-3">
            <div class="flex items-baseline space-x-2">
                <span class="text-lg font-bold text-gray-900">${{ number_format($price, 2) }}</span>
                @if($hasDiscount)
                <span class="text-sm text-gray-500 line-through">${{ number_format($originalPrice, 2) }}</span>
                @endif
            </div>
        </div>

        <!-- Add to Cart Button -->
        @if($variant && $variant->stock_quantity > 0)
        <button onclick="Livewire.dispatch('add-to-cart', { productId: {{ $product->id }}, variantId: {{ $variant->id }}, quantity: 1 })"
                class="w-full px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">
            Add to Cart
        </button>
        @else
        <button disabled class="w-full px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
            Out of Stock
        </button>
        @endif
    </div>
</div>

<!-- List View -->
<div x-show="viewMode === 'list'" class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
    <div class="flex flex-col md:flex-row">
        <div class="relative md:w-48 aspect-square bg-gray-100 flex-shrink-0">
            <a href="{{ route('products.show', $product->slug) }}">
                <img src="{{ $primaryImage ? asset('storage/' . $primaryImage->image_path) : asset('images/placeholder.png') }}" 
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover">
            </a>
            
            @if($variant && $variant->stock_quantity <= 0)
            <div class="absolute top-2 left-2 bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded">
                Out of Stock
            </div>
            @elseif($hasDiscount)
            <div class="absolute top-2 left-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">
                SALE
            </div>
            @endif
        </div>

        <div class="flex-1 p-6">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    @if($product->brand)
                    <p class="text-sm text-gray-600 mb-1">{{ $product->brand->name }}</p>
                    @endif
                    
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        <a href="{{ route('products.show', $product->slug) }}" class="hover:text-green-600">
                            {{ $product->name }}
                        </a>
                    </h3>

                    @if($product->average_rating > 0)
                    <div class="flex items-center mb-3">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            @endfor
                        </div>
                        <span class="ml-2 text-sm text-gray-600">({{ $product->review_count ?? 0 }} reviews)</span>
                    </div>
                    @endif

                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                        {{ Str::limit(strip_tags($product->description), 150) }}
                    </p>

                    <div class="flex items-center space-x-4">
                        <div>
                            <div class="flex items-baseline space-x-2">
                                <span class="text-2xl font-bold text-gray-900">${{ number_format($price, 2) }}</span>
                                @if($hasDiscount)
                                <span class="text-lg text-gray-500 line-through">${{ number_format($originalPrice, 2) }}</span>
                                @endif
                            </div>
                            @if($hasDiscount)
                            <p class="text-sm text-green-600 font-medium">
                                Save ${{ number_format($originalPrice - $price, 2) }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="ml-6 flex flex-col items-end space-y-2">
                    @livewire('wishlist.add-to-wishlist', ['productId' => $product->id, 'variantId' => $variant->id ?? null, 'size' => 'lg'], key('wishlist-list-'.$product->id))
                    
                    @if($variant && $variant->stock_quantity > 0)
                    <button onclick="Livewire.dispatch('add-to-cart', { productId: {{ $product->id }}, variantId: {{ $variant->id }}, quantity: 1 })"
                            class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                        Add to Cart
                    </button>
                    @else
                    <button disabled class="px-6 py-3 bg-gray-300 text-gray-500 font-medium rounded-lg cursor-not-allowed">
                        Out of Stock
                    </button>
                    @endif

                    <a href="{{ route('products.show', $product->slug) }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                        View Details
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

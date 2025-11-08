@props(['product', 'relatedProducts' => []])

@php
    // Get 2-3 frequently purchased together products
    // In a real implementation, this would come from purchase history data
    $bundleProducts = $relatedProducts->take(2);
    
    // Calculate total price
    $currentVariant = $product->variants->first();
    $currentPrice = $currentVariant->sale_price ?? $currentVariant->price ?? 0;
    
    $totalPrice = $currentPrice;
    $bundleItems = collect([
        [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $currentPrice,
            'image' => $product->images->first()?->image_path ?? 'placeholder.png',
            'rating' => 4.5, // Would come from reviews
            'reviews' => rand(1000, 50000),
            'isCurrent' => true
        ]
    ]);
    
    foreach($bundleProducts as $related) {
        $variant = $related->variants->first();
        $price = $variant->sale_price ?? $variant->price ?? 0;
        $totalPrice += $price;
        
        $bundleItems->push([
            'id' => $related->id,
            'name' => $related->name,
            'price' => $price,
            'image' => $related->images->first()?->image_path ?? 'placeholder.png',
            'rating' => 4.5,
            'reviews' => rand(1000, 50000),
            'isCurrent' => false
        ]);
    }
@endphp

@if($bundleProducts->count() > 0)
<div class="bg-white py-8 border-t border-gray-200" x-data="{
    selectedItems: [{{ $product->id }}],
    toggleItem(id) {
        if (this.selectedItems.includes(id)) {
            this.selectedItems = this.selectedItems.filter(i => i !== id);
        } else {
            this.selectedItems.push(id);
        }
    },
    isSelected(id) {
        return this.selectedItems.includes(id);
    },
    get totalPrice() {
        let total = 0;
        const items = {{ json_encode($bundleItems) }};
        items.forEach(item => {
            if (this.selectedItems.includes(item.id)) {
                total += item.price;
            }
        });
        return total.toFixed(2);
    },
    get selectedCount() {
        return this.selectedItems.length;
    }
}">
    <div class="container mx-auto px-4">
        <!-- Section Title with Badge Style -->
        <div class="mb-6">
            <span class="inline-block bg-gray-100 text-gray-900 text-lg font-semibold px-4 py-2 rounded-lg">
                Frequently purchased together
            </span>
        </div>
        
        <!-- Bundle Container -->
        <div class="bg-white border-t border-gray-200 pt-6">
            <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                
                <!-- Left Side: Product Images with Plus Signs -->
                <div class="flex-1">
                    <div class="flex items-center justify-start flex-wrap gap-6 lg:gap-8">
                        @foreach($bundleItems as $index => $item)
                            <!-- Product Image -->
                            <div class="flex flex-col items-center group">
                                <!-- Image Container with Hover Effect -->
                                <a href="{{ route('products.show', $item['id']) }}" 
                                   class="block w-32 h-32 lg:w-40 lg:h-40 bg-white border-2 border-gray-200 rounded-xl overflow-hidden hover:border-orange-400 hover:shadow-lg transition-all duration-300 p-3">
                                    <img src="{{ asset('storage/' . $item['image']) }}" 
                                         alt="{{ $item['name'] }}"
                                         class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300">
                                </a>
                                
                                <!-- Star Rating & Reviews -->
                                <div class="flex items-center mt-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($item['rating']))
                                            <svg class="w-4 h-4 text-orange-400 fill-current" viewBox="0 0 20 20">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                            </svg>
                                        @endif
                                    @endfor
                                    <span class="text-sm text-gray-700 ml-1.5 font-medium">{{ number_format($item['reviews']) }}</span>
                                </div>
                                
                                <!-- Product Name (Truncated) -->
                                <p class="text-xs text-gray-600 text-center mt-2 max-w-[140px] line-clamp-2 leading-tight">
                                    {{ Str::limit($item['name'], 40) }}
                                </p>
                            </div>
                            
                            <!-- Plus Sign (except for last item) -->
                            @if(!$loop->last)
                            <div class="text-gray-400 text-3xl font-light self-start mt-12 lg:mt-16">+</div>
                            @endif
                        @endforeach
                    </div>
                </div>
                
                <!-- Right Side: Product List & Total -->
                <div class="lg:w-96 space-y-4">
                    <!-- Product Checkboxes -->
                    <div class="space-y-3">
                        @foreach($bundleItems as $item)
                        <label class="flex items-start space-x-3 cursor-pointer group">
                            <!-- Checkbox -->
                            <input type="checkbox" 
                                   :checked="isSelected({{ $item['id'] }})"
                                   @change="toggleItem({{ $item['id'] }})"
                                   {{ $item['isCurrent'] ? 'disabled' : '' }}
                                   class="mt-1 w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500 {{ $item['isCurrent'] ? 'opacity-50' : '' }}">
                            
                            <!-- Product Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1 min-w-0">
                                        @if($item['isCurrent'])
                                            <span class="text-xs font-semibold text-green-600 mb-1 block">Current Item</span>
                                        @endif
                                        <a href="{{ route('products.show', $item['id']) }}" 
                                           class="text-sm text-blue-600 hover:text-blue-800 hover:underline line-clamp-2 group-hover:text-blue-800">
                                            {{ $item['name'] }}
                                        </a>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900 whitespace-nowrap ml-2">
                                        ${{ number_format($item['price'], 2) }}
                                    </span>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    
                    <!-- Total Price -->
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-base font-semibold text-gray-900">Total:</span>
                            <span class="text-2xl font-bold text-gray-900" x-text="'$' + totalPrice"></span>
                        </div>
                        
                        <!-- Add Selected to Cart Button -->
                        <button @click="addToCart()"
                                :disabled="selectedCount === 0"
                                class="w-full bg-orange-500 hover:bg-orange-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold py-3 px-6 rounded-xl transition duration-200 shadow-sm hover:shadow-md">
                            <span x-show="selectedCount > 0" x-text="'Add Selected to Cart (' + selectedCount + ')'"></span>
                            <span x-show="selectedCount === 0">Select items to add</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function addToCart() {
        // This would integrate with your cart system
        // For now, just show an alert
        const selectedCount = Alpine.store('selectedCount') || 0;
        alert(`Adding ${selectedCount} items to cart`);
        
        // In production, you would:
        // 1. Get selected product IDs
        // 2. Send AJAX request to add to cart
        // 3. Update cart count in header
        // 4. Show success message
    }
</script>
@endpush
@endif

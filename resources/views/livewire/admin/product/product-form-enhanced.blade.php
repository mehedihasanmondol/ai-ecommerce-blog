<div class="p-6" x-data="{ activeTab: 'general' }" wire:ignore.self>
    <div class="max-w-7xl mx-auto">
        {{-- Flash Messages --}}
        @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        @endif

        {{-- Header with Actions --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $isEdit ? 'Edit Product' : 'Add New Product' }}</h1>
                <p class="text-sm text-gray-600 mt-1">{{ $isEdit ? 'Update product information' : 'Create a new product in your catalog' }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.products.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="button" 
                        wire:click="saveAsDraft"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <span wire:loading.remove wire:target="saveAsDraft">Save as Draft</span>
                    <span wire:loading wire:target="saveAsDraft">Saving...</span>
                </button>
                <button type="button" 
                        wire:click="publish"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <span wire:loading.remove wire:target="publish">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                    <span wire:loading wire:target="publish">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    <span>{{ $isEdit ? 'Update Product' : 'Publish Product' }}</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6">
            {{-- Main Content Area --}}
            <div class="col-span-8">
                {{-- Tab Navigation --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                    <div class="border-b border-gray-200">
                        <nav class="flex -mb-px">
                            <button @click="activeTab = 'general'" 
                                    :class="activeTab === 'general' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="px-6 py-4 border-b-2 font-medium text-sm transition-colors">
                                General
                            </button>
                            
                            @if($product_type === 'simple' || $product_type === 'affiliate' || $product_type === 'grouped')
                            <button @click="activeTab = 'pricing'" 
                                    :class="activeTab === 'pricing' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="px-6 py-4 border-b-2 font-medium text-sm transition-colors">
                                {{ $product_type === 'grouped' ? 'Grouped Products' : 'Pricing & Stock' }}
                            </button>
                            @endif
                            
                            @if($product_type === 'variable')
                            <button @click="activeTab = 'variations'" 
                                    :class="activeTab === 'variations' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="px-6 py-4 border-b-2 font-medium text-sm transition-colors">
                                Variations
                            </button>
                            @endif
                            
                            @if($product_type === 'simple' || $product_type === 'grouped')
                            <button @click="activeTab = 'shipping'" 
                                    :class="activeTab === 'shipping' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="px-6 py-4 border-b-2 font-medium text-sm transition-colors">
                                Shipping
                            </button>
                            @endif
                            
                            <button @click="activeTab = 'seo'" 
                                    :class="activeTab === 'seo' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="px-6 py-4 border-b-2 font-medium text-sm transition-colors">
                                SEO
                            </button>
                        </nav>
                    </div>

                    {{-- Tab Content --}}
                    <div class="p-6">
                        {{-- General Tab --}}
                        <div x-show="activeTab === 'general'" class="space-y-6">
                            {{-- Product Name --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Product Name *</label>
                                <input type="text" 
                                       wire:model.live.debounce.300ms="name" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg"
                                       placeholder="Enter product name">
                                @error('name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Slug --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Permalink</label>
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-500 text-sm">{{ url('/') }}/product/</span>
                                    <input type="text" 
                                           wire:model="slug" 
                                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="product-slug">
                                </div>
                                @error('slug') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Description --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                                <textarea wire:model="description" 
                                          rows="6"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Enter detailed product description"></textarea>
                                @error('description') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Short Description --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Short Description</label>
                                <textarea wire:model="short_description" 
                                          rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Brief product summary (max 500 characters)"></textarea>
                                <p class="text-xs text-gray-500 mt-1">This will appear on product cards and search results</p>
                                @error('short_description') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Pricing & Stock Tab (Simple/Affiliate/Grouped) --}}
                        <div x-show="activeTab === 'pricing'" class="space-y-6">
                            @if($product_type === 'simple' || $product_type === 'grouped')
                            
                            @if($product_type === 'grouped')
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-amber-900 mb-1">Grouped Product Pricing</h4>
                                        <p class="text-sm text-amber-800">Set the base price and stock for the grouped product package. Individual child products maintain their own pricing. The displayed price will show as a range based on child products.</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="grid grid-cols-2 gap-6">
                                {{-- Regular Price --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Regular Price ($) *</label>
                                    <input type="number" 
                                           wire:model="variant.price" 
                                           step="0.01"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg"
                                           placeholder="0.00">
                                    @error('variant.price') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- Sale Price --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sale Price ($)</label>
                                    <input type="number" 
                                           wire:model="variant.sale_price" 
                                           step="0.01"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg"
                                           placeholder="0.00">
                                    <p class="text-xs text-gray-500 mt-1">⚠️ Must be less than regular price</p>
                                    @error('variant.sale_price') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- SKU & Stock --}}
                            <div class="grid grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">SKU</label>
                                    <input type="text" 
                                           wire:model="variant.sku" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Auto-generated">
                                    <p class="text-xs text-gray-500 mt-1">Leave empty for auto-generation</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Stock Quantity *</label>
                                    <input type="number" 
                                           wire:model="variant.stock_quantity" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="0">
                                    @error('variant.stock_quantity') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Low Stock Alert</label>
                                    <input type="number" 
                                           wire:model="variant.low_stock_alert" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="5">
                                </div>
                            </div>

                            {{-- Cost Price --}}
                            <div class="w-1/3">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Cost Price ($)</label>
                                <input type="number" 
                                       wire:model="variant.cost_price" 
                                       step="0.01"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="0.00">
                                <p class="text-xs text-gray-500 mt-1">Your cost for this product (for profit calculation)</p>
                            </div>
                            @endif

                            @if($product_type === 'grouped')
                            {{-- Divider --}}
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-4 bg-white text-gray-500 font-medium">Child Products Selection</span>
                                </div>
                            </div>

                            <div class="space-y-6">
                                {{-- Info Box --}}
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div>
                                            <h4 class="font-semibold text-blue-900 mb-1">About Child Products</h4>
                                            <p class="text-sm text-blue-800">Select simple products to include in this group. Each child product maintains its own price and stock. Customers can purchase individual items or the entire group.</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Product Selection --}}
                                <div>
                                    <div class="flex items-center justify-between mb-4">
                                        <label class="block text-sm font-semibold text-gray-700">Select Child Products</label>
                                        <span class="text-sm text-gray-500">{{ count($selectedChildProducts) }} products selected</span>
                                    </div>

                                    {{-- Search Products --}}
                                    <div class="mb-4">
                                        <input type="text" 
                                               wire:model.live.debounce.300ms="childProductSearch"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                               placeholder="Search products to add...">
                                    </div>

                                    {{-- Selected Products List --}}
                                    @if(count($selectedChildProducts) > 0)
                                    <div class="mb-4 space-y-2">
                                        <h5 class="text-sm font-medium text-gray-700 mb-2">Selected Products:</h5>
                                        @foreach($selectedChildProducts as $index => $childId)
                                            @php
                                                $childProduct = \App\Modules\Ecommerce\Product\Models\Product::find($childId);
                                            @endphp
                                            @if($childProduct)
                                            <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h6 class="font-medium text-gray-900">{{ $childProduct->name }}</h6>
                                                        <p class="text-sm text-gray-600">
                                                            SKU: {{ $childProduct->variants->first()?->sku ?? 'N/A' }} | 
                                                            Price: ${{ number_format($childProduct->variants->first()?->price ?? 0, 2) }} |
                                                            Stock: {{ $childProduct->variants->first()?->stock_quantity ?? 0 }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <button wire:click="removeChildProduct({{ $index }})" 
                                                        class="px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    @endif

                                    {{-- Available Products to Add --}}
                                    @if(!empty($childProductSearch))
                                    <div class="border border-gray-200 rounded-lg max-h-64 overflow-y-auto">
                                        @php
                                            $availableProducts = \App\Modules\Ecommerce\Product\Models\Product::where('product_type', 'simple')
                                                ->where('name', 'like', '%' . $childProductSearch . '%')
                                                ->whereNotIn('id', $selectedChildProducts)
                                                ->limit(10)
                                                ->get();
                                        @endphp
                                        
                                        @if($availableProducts->count() > 0)
                                            @foreach($availableProducts as $product)
                                            <div class="flex items-center justify-between p-3 hover:bg-gray-50 border-b border-gray-200 last:border-b-0">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h6 class="font-medium text-gray-900 text-sm">{{ $product->name }}</h6>
                                                        <p class="text-xs text-gray-600">
                                                            ${{ number_format($product->variants->first()?->price ?? 0, 2) }} | 
                                                            Stock: {{ $product->variants->first()?->stock_quantity ?? 0 }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <button wire:click="addChildProduct({{ $product->id }})" 
                                                        class="px-3 py-1 text-sm text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                                    + Add
                                                </button>
                                            </div>
                                            @endforeach
                                        @else
                                            <div class="p-4 text-center text-gray-500 text-sm">
                                                No products found
                                            </div>
                                        @endif
                                    </div>
                                    @endif

                                    @if(count($selectedChildProducts) == 0 && empty($childProductSearch))
                                    <div class="text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        <p class="text-gray-600 mb-1">No child products selected</p>
                                        <p class="text-sm text-gray-500">Search and add simple products to this group</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($product_type === 'affiliate')
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">External URL *</label>
                                    <input type="url" 
                                           wire:model="external_url" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="https://example.com/product">
                                    @error('external_url') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Button Text *</label>
                                    <input type="text" 
                                           wire:model="button_text" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Buy Now">
                                    @error('button_text') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- Variations Tab --}}
                        <div x-show="activeTab === 'variations'" class="space-y-6">
                            @if($product_type === 'variable')
                                {{-- Info Box --}}
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div>
                                            <h4 class="font-semibold text-blue-900 mb-1">Variable Product Variations</h4>
                                            <p class="text-sm text-blue-800">
                                                @if(!$product?->id)
                                                    Create product attributes (like Size, Color), then generate variations. Variations will be saved when you publish the product.
                                                @else
                                                    Create product attributes (like Size, Color), then generate variations by selecting which attribute values to use. Each variation can have its own price and stock.
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Attribute Manager Section --}}
                                <div class="border border-gray-200 rounded-lg">
                                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                        <h3 class="text-base font-semibold text-gray-900">Step 1: Manage Attributes</h3>
                                        <p class="text-sm text-gray-600 mt-1">Create and manage product attributes like Size, Color, Material, etc.</p>
                                    </div>
                                    <div class="p-6">
                                        @livewire('admin.product.attribute-manager', key('attribute-manager'))
                                    </div>
                                </div>

                                {{-- Variation Generator Section --}}
                                <div class="border border-gray-200 rounded-lg">
                                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                        <h3 class="text-base font-semibold text-gray-900">Step 2: Generate & Manage Variations</h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            @if(!$product?->id)
                                                Create variations here. They will be saved temporarily and stored when you publish the product.
                                            @else
                                                Select attributes and generate all possible variations, then set individual prices and stock.
                                            @endif
                                        </p>
                                    </div>
                                    <div class="p-6">
                                        @livewire('admin.product.variation-generator', ['productId' => $product?->id, 'tempMode' => !$product?->id], key('variation-generator-' . ($product?->id ?? 'new')))
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-12 bg-gray-50 rounded-lg border border-gray-200">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    <p class="text-gray-600">Variations are only available for Variable Products</p>
                                    <p class="text-sm text-gray-500 mt-1">Change product type to "Variable Product" to enable variations</p>
                                </div>
                            @endif
                        </div>

                        {{-- Shipping Tab --}}
                        <div x-show="activeTab === 'shipping'" class="space-y-6">
                            @if($product_type === 'grouped')
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-blue-900 mb-1">Grouped Product Shipping</h4>
                                        <p class="text-sm text-blue-800">Set shipping dimensions for the entire grouped package. Individual child products may have their own shipping details, but these values apply when the group is purchased together.</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="grid grid-cols-4 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Weight (kg)</label>
                                    <input type="number" 
                                           wire:model="variant.weight" 
                                           step="0.01"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="0.00">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Length (cm)</label>
                                    <input type="number" 
                                           wire:model="variant.length" 
                                           step="0.01"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="0.00">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Width (cm)</label>
                                    <input type="number" 
                                           wire:model="variant.width" 
                                           step="0.01"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="0.00">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Height (cm)</label>
                                    <input type="number" 
                                           wire:model="variant.height" 
                                           step="0.01"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="0.00">
                                </div>
                            </div>

                            <div class="w-1/2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Shipping Class</label>
                                <select wire:model="variant.shipping_class" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Standard Shipping</option>
                                    <option value="free">Free Shipping</option>
                                    <option value="express">Express Shipping</option>
                                    <option value="heavy">Heavy Item</option>
                                </select>
                            </div>
                        </div>

                        {{-- SEO Tab --}}
                        <div x-show="activeTab === 'seo'" class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Title</label>
                                <input type="text" 
                                       wire:model="meta_title" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Leave empty to use product name">
                                <p class="text-xs text-gray-500 mt-1">Recommended: 50-60 characters</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Description</label>
                                <textarea wire:model="meta_description" 
                                          rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Brief description for search engines"></textarea>
                                <p class="text-xs text-gray-500 mt-1">Recommended: 150-160 characters</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Keywords</label>
                                <input type="text" 
                                       wire:model="meta_keywords" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="keyword1, keyword2, keyword3">
                                <p class="text-xs text-gray-500 mt-1">Separate keywords with commas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-span-4 space-y-6">
                {{-- Publish Box --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Publish</h3>
                    
                    <div class="space-y-4">
                        {{-- Status --}}
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-sm text-gray-600">Status:</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="is_active" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                <span class="ml-3 text-sm font-medium text-gray-900">{{ $is_active ? 'Active' : 'Inactive' }}</span>
                            </label>
                        </div>

                        {{-- Featured --}}
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-sm text-gray-600">Featured:</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="is_featured" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                <span class="ml-3 text-sm font-medium text-gray-900">{{ $is_featured ? 'Yes' : 'No' }}</span>
                            </label>
                        </div>

                        {{-- Visibility --}}
                        <div class="py-2">
                            <span class="text-sm text-gray-600 block mb-2">Visibility:</span>
                            <p class="text-sm text-gray-900">{{ $is_active ? '🟢 Public' : '🔴 Hidden' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Product Type --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Type</h3>
                    
                    <div class="space-y-3">
                        <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" wire:model.live="product_type" value="simple" class="w-4 h-4 text-blue-600">
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-900">Simple Product</span>
                                <span class="block text-xs text-gray-500">Single product with one price</span>
                            </div>
                        </label>

                        <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" wire:model.live="product_type" value="variable" class="w-4 h-4 text-blue-600">
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-900">Variable Product</span>
                                <span class="block text-xs text-gray-500">Product with variations (size, color, etc.)</span>
                            </div>
                        </label>

                        <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" wire:model.live="product_type" value="grouped" class="w-4 h-4 text-blue-600">
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-900">Grouped Product</span>
                                <span class="block text-xs text-gray-500">Collection of related products</span>
                            </div>
                        </label>

                        <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" wire:model.live="product_type" value="affiliate" class="w-4 h-4 text-blue-600">
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-900">Affiliate Product</span>
                                <span class="block text-xs text-gray-500">External product with redirect link</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Categories --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Categories</h3>
                    
                    <select wire:model="category_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Brands --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Brand</h3>
                    
                    <select wire:model="brand_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    @error('brand_id') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    </div>
</div>

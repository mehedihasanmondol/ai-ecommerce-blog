<div class="p-6" x-data="{ activeTab: 'general' }">
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
                        wire:click="save"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <span wire:loading.remove wire:target="save">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                    <span wire:loading wire:target="save">
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
                            <button @click="activeTab = 'pricing'" 
                                    :class="activeTab === 'pricing' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="px-6 py-4 border-b-2 font-medium text-sm transition-colors"
                                    x-show="product_type === 'simple' || product_type === 'affiliate'">
                                Pricing & Stock
                            </button>
                            <button @click="activeTab = 'variations'" 
                                    :class="activeTab === 'variations' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="px-6 py-4 border-b-2 font-medium text-sm transition-colors"
                                    x-show="product_type === 'variable'">
                                Variations
                            </button>
                            <button @click="activeTab = 'shipping'" 
                                    :class="activeTab === 'shipping' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="px-6 py-4 border-b-2 font-medium text-sm transition-colors"
                                    x-show="product_type === 'simple'">
                                Shipping
                            </button>
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

                        {{-- Pricing & Stock Tab (Simple/Affiliate) --}}
                        <div x-show="activeTab === 'pricing'" class="space-y-6">
                            @if($product_type === 'simple')
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

                        {{-- Shipping Tab --}}
                        <div x-show="activeTab === 'shipping'" class="space-y-6">
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

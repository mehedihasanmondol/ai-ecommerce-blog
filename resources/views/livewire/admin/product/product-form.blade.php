<div class="p-6">
    <div class="max-w-5xl mx-auto">
        {{-- Flash Messages --}}
        @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ $isEdit ? 'Edit Product' : 'Create New Product' }}</h1>
            <p class="text-sm text-gray-600 mt-1">{{ $isEdit ? 'Update product information' : 'Add a new product to your catalog' }}</p>
        </div>

        {{-- Progress Steps --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex-1 flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $currentStep >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                        1
                    </div>
                    <div class="flex-1 h-1 mx-2 {{ $currentStep >= 2 ? 'bg-blue-600' : 'bg-gray-200' }}"></div>
                </div>
                <div class="flex-1 flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $currentStep >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                        2
                    </div>
                    <div class="flex-1 h-1 mx-2 {{ $currentStep >= 3 ? 'bg-blue-600' : 'bg-gray-200' }}"></div>
                </div>
                <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $currentStep >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                    3
                </div>
            </div>
            <div class="flex items-center justify-between mt-2">
                <span class="text-xs font-medium {{ $currentStep >= 1 ? 'text-blue-600' : 'text-gray-500' }}">Basic Info</span>
                <span class="text-xs font-medium {{ $currentStep >= 2 ? 'text-blue-600' : 'text-gray-500' }}">Pricing & Stock</span>
                <span class="text-xs font-medium {{ $currentStep >= 3 ? 'text-blue-600' : 'text-gray-500' }}">SEO & Media</span>
            </div>
        </div>

        <form wire:submit="save">
            {{-- Step 1: Basic Information --}}
            @if($currentStep === 1)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>

                {{-- Product Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                    <input type="text" 
                           wire:model.live.debounce.300ms="name" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter product name">
                    @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Slug *</label>
                    <input type="text" 
                           wire:model="slug" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="product-slug">
                    @error('slug') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Short Description --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                    <textarea wire:model="short_description" 
                              rows="2"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Brief product description"></textarea>
                    @error('short_description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Description</label>
                    <textarea wire:model="description" 
                              rows="6"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Detailed product description"></textarea>
                    @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Category & Brand --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select wire:model="category_id" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                        <select wire:model="brand_id" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @error('brand_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Product Type --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Product Type *</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all
                            {{ $product_type === 'simple' ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" wire:model.live="product_type" value="simple" class="sr-only">
                            <div class="flex-1">
                                <div class="flex items-center justify-center mb-2">
                                    <svg class="w-8 h-8 {{ $product_type === 'simple' ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <div class="text-center text-sm font-medium {{ $product_type === 'simple' ? 'text-blue-600' : 'text-gray-700' }}">Simple</div>
                            </div>
                        </label>

                        <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all
                            {{ $product_type === 'variable' ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" wire:model.live="product_type" value="variable" class="sr-only">
                            <div class="flex-1">
                                <div class="flex items-center justify-center mb-2">
                                    <svg class="w-8 h-8 {{ $product_type === 'variable' ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                </div>
                                <div class="text-center text-sm font-medium {{ $product_type === 'variable' ? 'text-blue-600' : 'text-gray-700' }}">Variable</div>
                            </div>
                        </label>

                        <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all
                            {{ $product_type === 'grouped' ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" wire:model.live="product_type" value="grouped" class="sr-only">
                            <div class="flex-1">
                                <div class="flex items-center justify-center mb-2">
                                    <svg class="w-8 h-8 {{ $product_type === 'grouped' ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <div class="text-center text-sm font-medium {{ $product_type === 'grouped' ? 'text-blue-600' : 'text-gray-700' }}">Grouped</div>
                            </div>
                        </label>

                        <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all
                            {{ $product_type === 'affiliate' ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" wire:model.live="product_type" value="affiliate" class="sr-only">
                            <div class="flex-1">
                                <div class="flex items-center justify-center mb-2">
                                    <svg class="w-8 h-8 {{ $product_type === 'affiliate' ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </div>
                                <div class="text-center text-sm font-medium {{ $product_type === 'affiliate' ? 'text-blue-600' : 'text-gray-700' }}">Affiliate</div>
                            </div>
                        </label>
                    </div>
                    @error('product_type') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Affiliate Fields --}}
                @if($product_type === 'affiliate')
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 space-y-4">
                    <h3 class="text-sm font-semibold text-orange-900">Affiliate Product Settings</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">External URL *</label>
                        <input type="url" 
                               wire:model="external_url" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               placeholder="https://example.com/product">
                        @error('external_url') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Button Text *</label>
                        <input type="text" 
                               wire:model="button_text" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               placeholder="Buy Now">
                        @error('button_text') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                @endif

                {{-- Status Toggles --}}
                <div class="flex items-center gap-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="is_featured" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-700">Featured Product</span>
                    </label>

                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="is_active" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-700">Active</span>
                    </label>
                </div>
            </div>
            @endif

            {{-- Step 2: Pricing & Stock --}}
            @if($currentStep === 2)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Pricing & Stock</h2>

                @if($product_type === 'simple')
                <div class="space-y-6">
                    {{-- SKU --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                        <input type="text" 
                               wire:model="variant.sku" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               placeholder="Auto-generated if left empty">
                        @error('variant.sku') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Pricing --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Regular Price *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                                <input type="number" 
                                       wire:model="variant.price" 
                                       step="0.01"
                                       class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                       placeholder="0.00">
                            </div>
                            @error('variant.price') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price (Optional)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                                <input type="number" 
                                       wire:model="variant.sale_price" 
                                       step="0.01"
                                       class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                       placeholder="0.00">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">⚠️ Must be less than regular price</p>
                            @error('variant.sale_price') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cost Price</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                                <input type="number" 
                                       wire:model="variant.cost_price" 
                                       step="0.01"
                                       class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                       placeholder="0.00">
                            </div>
                            @error('variant.cost_price') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Stock --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity *</label>
                            <input type="number" 
                                   wire:model="variant.stock_quantity" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                   placeholder="0">
                            @error('variant.stock_quantity') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Low Stock Alert</label>
                            <input type="number" 
                                   wire:model="variant.low_stock_alert" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                   placeholder="5">
                            @error('variant.low_stock_alert') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Dimensions --}}
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Shipping Dimensions</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Weight (kg)</label>
                                <input type="number" 
                                       wire:model="variant.weight" 
                                       step="0.01"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                       placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Length (cm)</label>
                                <input type="number" 
                                       wire:model="variant.length" 
                                       step="0.01"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                       placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Width (cm)</label>
                                <input type="number" 
                                       wire:model="variant.width" 
                                       step="0.01"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                       placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Height (cm)</label>
                                <input type="number" 
                                       wire:model="variant.height" 
                                       step="0.01"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                       placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    {{-- Shipping Class --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Class</label>
                        <select wire:model="variant.shipping_class" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Standard</option>
                            <option value="express">Express</option>
                            <option value="free">Free Shipping</option>
                        </select>
                    </div>
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-600">Pricing and stock settings are not applicable for {{ $product_type }} products.</p>
                    <p class="text-sm text-gray-500 mt-2">You can manage variants separately after creating the product.</p>
                </div>
                @endif
            </div>
            @endif

            {{-- Step 3: SEO & Media --}}
            @if($currentStep === 3)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">SEO & Media</h2>

                {{-- SEO Fields --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                        <input type="text" 
                               wire:model="meta_title" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               placeholder="Leave empty to use product name">
                        <p class="text-xs text-gray-500 mt-1">Recommended: 50-60 characters</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                        <textarea wire:model="meta_description" 
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                  placeholder="Brief description for search engines"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Recommended: 150-160 characters</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                        <input type="text" 
                               wire:model="meta_keywords" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               placeholder="keyword1, keyword2, keyword3">
                        <p class="text-xs text-gray-500 mt-1">Separate keywords with commas</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Navigation Buttons --}}
            <div class="flex items-center justify-between mt-6">
                @if($currentStep > 1)
                <button type="button" 
                        wire:click="previousStep"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Previous
                </button>
                @else
                <a href="{{ route('admin.products.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                @endif

                @if($currentStep < 3)
                <button type="button" 
                        wire:click="nextStep"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Next Step
                </button>
                @else
                <button type="submit" 
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                    <span wire:loading.remove wire:target="save">
                        {{ $isEdit ? 'Update Product' : 'Create Product' }}
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving...
                    </span>
                </button>
                @endif
            </div>
        </form>
    </div>
</div>

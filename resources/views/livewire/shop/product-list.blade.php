<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar Filters -->
            <aside class="lg:w-64 flex-shrink-0">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
                    <!-- Mobile Filter Toggle -->
                    <button wire:click="$toggle('showFilters')" 
                            class="lg:hidden w-full mb-4 px-4 py-2 bg-green-600 text-white rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filters
                    </button>

                    <div class="space-y-6 {{ $showFilters ? '' : 'hidden lg:block' }}">
                        
                        <!-- Categories -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-3">
                                Categories 
                                <span class="text-xs text-gray-500">(Selected: {{ count($selectedCategories) }})</span>
                            </h3>
                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                @foreach($categories as $category)
                                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                    <input type="checkbox" 
                                           value="{{ $category->id }}"
                                           wire:model.live="selectedCategories"
                                           class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                    <span class="ml-2 text-sm text-gray-700">{{ $category->name }}</span>
                                    <span class="ml-auto text-xs text-gray-500">({{ $category->products_count }})</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Brands -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-3">Brands</h3>
                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                @foreach($brands as $brand)
                                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                    <input type="checkbox" 
                                           value="{{ $brand->id }}"
                                           wire:model.live="selectedBrands"
                                           class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                    <span class="ml-2 text-sm text-gray-700">{{ $brand->name }}</span>
                                    <span class="ml-auto text-xs text-gray-500">({{ $brand->products_count }})</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-3">Price Range</h3>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-2">
                                    <input type="number" 
                                           wire:model.live.debounce.500ms="minPrice"
                                           placeholder="Min"
                                           min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    <span class="text-gray-500">-</span>
                                    <input type="number" 
                                           wire:model.live.debounce.500ms="maxPrice"
                                           placeholder="Max"
                                           min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                </div>
                                @if($priceRange)
                                <p class="text-xs text-gray-500">
                                    Range: ${{ number_format($priceRange->min_price, 2) }} - ${{ number_format($priceRange->max_price, 2) }}
                                </p>
                                @endif
                            </div>
                        </div>

                        <!-- Rating -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-3">Rating</h3>
                            <div class="space-y-2">
                                @for($i = 5; $i >= 1; $i--)
                                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                    <input type="radio" 
                                           name="rating"
                                           value="{{ $i }}"
                                           wire:model.live="minRating"
                                           class="border-gray-300 text-green-600 focus:ring-green-500">
                                    <span class="ml-2 flex items-center">
                                        @for($j = 1; $j <= 5; $j++)
                                        <svg class="w-4 h-4 {{ $j <= $i ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        @endfor
                                        <span class="ml-1 text-sm text-gray-600">& Up</span>
                                    </span>
                                </label>
                                @endfor
                            </div>
                        </div>

                        <!-- Availability -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-3">Availability</h3>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                <input type="checkbox" 
                                       wire:model.live="inStock"
                                       class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                <span class="ml-2 text-sm text-gray-700">In Stock Only</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                <input type="checkbox" 
                                       wire:model.live="onSale"
                                       class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                <span class="ml-2 text-sm text-gray-700">On Sale</span>
                            </label>
                        </div>

                        <!-- Clear Filters -->
                        <button wire:click="clearFilters" 
                                class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Clear All Filters
                        </button>
                    </div>

                    <!-- Loading Indicator -->
                    <div wire:loading class="mt-4 text-center">
                        <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-lg">
                            <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Loading...
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 relative">
                @include('livewire.shop.partials.header')
                
                <!-- Products Container with Loading State -->
                <div class="relative">
                    <!-- Loading Overlay with Blur (Only for filter changes) -->
                    <div wire:loading.delay.longest
                         wire:target="selectedCategories,selectedBrands,minPrice,maxPrice,minRating,inStock,onSale,sortBy,perPage"
                         class="fixed inset-0 z-50 overflow-y-auto"
                         style="display: none;"
                         x-show="true"
                         x-cloak>
                        
                        <!-- Backdrop with blur -->
                        <div class="fixed inset-0 transition-all duration-300" 
                             style="background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"></div>
                        
                        <!-- Modal Content -->
                        <div class="flex items-center justify-center min-h-screen p-4">
                            <div class="relative rounded-lg shadow-2xl max-w-md w-full p-6 border border-gray-200"
                                 style="background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);">
                                
                                <!-- Loading Icon -->
                                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full mb-4">
                                    <svg class="animate-spin w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                                
                                <!-- Title -->
                                <h3 class="text-lg font-bold text-gray-900 text-center mb-2">Loading Products</h3>
                                
                                <!-- Message -->
                                <p class="text-sm text-gray-600 text-center mb-4">Please wait while we update the results...</p>
                                
                                <!-- Progress Bar -->
                                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="bg-green-600 h-2 rounded-full animate-progress"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Products -->
                    @include('livewire.shop.partials.products')
                </div>
            </main>
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes progress {
        0% {
            width: 0%;
        }
        50% {
            width: 70%;
        }
        100% {
            width: 100%;
        }
    }
    
    .animate-progress {
        animation: progress 1.5s ease-in-out infinite;
    }
</style>
@endpush

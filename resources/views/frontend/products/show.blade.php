@extends('layouts.app')

@section('title', $product->meta_title ?? $product->name)
@section('meta_description', $product->meta_description ?? $product->short_description)
@section('meta_keywords', $product->meta_keywords ?? '')

@section('content')
@php
    // Define variant early for use throughout the view
    $variant = $defaultVariant ?? $product->variants->first();
@endphp

<!-- Breadcrumb -->
@php
    $breadcrumbs = [
        ['label' => 'Home', 'url' => route('home')]
    ];
    
    // Add parent category if exists
    if($product->category && $product->category->parent) {
        $breadcrumbs[] = [
            'label' => $product->category->parent->name,
            'url' => route('shop') . '?category=' . $product->category->parent->slug
        ];
    }
    
    // Add category if exists
    if($product->category) {
        $breadcrumbs[] = [
            'label' => $product->category->name,
            'url' => route('shop') . '?category=' . $product->category->slug
        ];
    }
    
    // Add brand if exists (optional)
    if($product->brand) {
        $breadcrumbs[] = [
            'label' => $product->brand->name,
            'url' => route('shop') . '?brand=' . $product->brand->slug
        ];
    }
    
    // Add current product (no link)
    $breadcrumbs[] = [
        'label' => $product->name,
        'url' => null
    ];
@endphp

<x-breadcrumb :items="$breadcrumbs" />

<!-- Main Product Section -->
<div class="bg-white">
    <div class="container mx-auto px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Left Column: Product Gallery (4 columns) -->
            <div class="lg:col-span-4">
                <x-product-gallery :product="$product" />
            </div>

            <!-- Middle Column: Product Info (5 columns) -->
            <div class="lg:col-span-5">
                <!-- Badges Row -->
                <div class="flex flex-wrap gap-2 mb-3">
                    @if($variant && $variant->sale_price && $variant->sale_price < $variant->price)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold bg-red-600 text-white">
                            Special!
                        </span>
                    @endif
                    @if($product->brand && $product->brand->is_featured)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold bg-teal-600 text-white">
                            iHerb Brands
                        </span>
                    @endif
                </div>

                <!-- Product Title -->
                <h1 class="text-xl lg:text-2xl font-bold text-gray-900 mb-2">
                    {{ $product->name }}
                </h1>

                <!-- Brand -->
                @if($product->brand)
                <div class="mb-3">
                    <span class="text-sm text-gray-600">By </span>
                    <a href="{{ route('shop') }}?brand={{ $product->brand->slug }}" class="text-sm text-blue-600 hover:text-blue-800 transition font-medium">
                        {{ $product->brand->name }}
                    </a>
                </div>
                @endif

                <!-- Rating & Reviews -->
                <div class="flex items-center space-x-4 mb-4">
                    <div class="flex items-center space-x-1">
                        <span class="text-lg font-bold text-gray-900">{{ number_format($averageRating, 1) }}</span>
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($averageRating))
                                    <svg class="w-4 h-4 text-orange-400 fill-current" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @elseif($i - 0.5 <= $averageRating)
                                    <svg class="w-4 h-4 text-orange-400" viewBox="0 0 20 20">
                                        <defs>
                                            <linearGradient id="half-{{ $i }}">
                                                <stop offset="50%" stop-color="currentColor"/>
                                                <stop offset="50%" stop-color="#D1D5DB"/>
                                            </linearGradient>
                                        </defs>
                                        <path fill="url(#half-{{ $i }})" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                    </div>
                    <a href="#reviews-section" class="text-sm text-blue-600 hover:underline flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        {{ number_format($totalReviews) }} {{ Str::plural('Review', $totalReviews) }}
                    </a>
                    <a href="#questions-section" class="text-sm text-blue-600 hover:underline flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Q & A
                    </a>
                </div>

                <!-- Stock Status -->
                @if($variant && $variant->stock_quantity > 0)
                <div class="mb-4">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-green-700 font-semibold">In stock</span>
                    </div>
                    @if($variant->stock_quantity <= 10)
                        <div class="mt-1 flex items-center text-sm text-orange-600">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-medium">{{ $variant->stock_quantity }} left - Order soon!</span>
                        </div>
                    @endif
                </div>
                @else
                <div class="mb-4">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-red-700 font-semibold">Out of Stock</span>
                    </div>
                </div>
                @endif


                <!-- Product Information List -->
                <div class="mb-6">
                    <div class="space-y-2 text-sm">
                        <!-- 100% Authentic Badge -->
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold text-green-700">100% authentic</span>
                            <button class="text-gray-400 hover:text-gray-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Best By Date -->
                        @if($variant && $variant->expires_at)
                        <div class="flex items-start space-x-2">
                            <span class="text-gray-700 font-medium min-w-[100px]">Best by:</span>
                            <span class="text-gray-900">{{ $variant->expires_at->format('m/Y') }}</span>
                            <button class="text-gray-400 hover:text-gray-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        @endif

                        <!-- First Available -->
                        <div class="flex items-start space-x-2">
                            <span class="text-gray-700 font-medium min-w-[100px]">First available:</span>
                            <span class="text-gray-900">{{ $product->created_at->format('m/Y') }}</span>
                        </div>

                        <!-- Shipping Weight -->
                        @if($variant && $variant->weight)
                        <div class="flex items-start space-x-2">
                            <span class="text-gray-700 font-medium min-w-[100px]">Shipping weight:</span>
                            <span class="text-gray-900">{{ number_format($variant->weight, 2) }} kg</span>
                            <button class="text-gray-400 hover:text-gray-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        @endif

                        <!-- Product Code -->
                        <div class="flex items-start space-x-2">
                            <span class="text-gray-700 font-medium min-w-[100px]">Product code:</span>
                            <span class="text-gray-900">{{ $variant->sku ?? $product->sku }}</span>
                        </div>

                        <!-- UPC Code -->
                        @if($variant && $variant->barcode)
                        <div class="flex items-start space-x-2">
                            <span class="text-gray-700 font-medium min-w-[100px]">UPC:</span>
                            <span class="text-gray-900">{{ $variant->barcode }}</span>
                        </div>
                        @endif

                        <!-- Package Quantity -->
                        @if($variant && $variant->dimensions)
                        <div class="flex items-start space-x-2">
                            <span class="text-gray-700 font-medium min-w-[100px]">Package quantity:</span>
                            <span class="text-gray-900">{{ $variant->dimensions }}</span>
                        </div>
                        @endif

                        <!-- Dimensions -->
                        @if($variant && ($variant->length || $variant->width || $variant->height))
                        <div class="flex items-start space-x-2">
                            <span class="text-gray-700 font-medium min-w-[100px]">Dimensions:</span>
                            <span class="text-gray-900">
                                {{ $variant->length ?? 0 }} x {{ $variant->width ?? 0 }} x {{ $variant->height ?? 0 }} cm
                            </span>
                        </div>
                        @endif

                        <!-- Try Risk Free -->
                        <div class="flex items-start space-x-2">
                            <span class="text-gray-700 font-medium min-w-[100px]">Try Risk Free:</span>
                            <span class="text-green-700 font-semibold">Free for 90 Days</span>
                            <button class="text-gray-400 hover:text-gray-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Rankings -->
                @if($product->category)
                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="text-sm font-bold text-gray-900 mb-2">Product rankings:</h3>
                    <div class="space-y-1 text-sm">
                        <div>
                            <span class="font-semibold text-blue-700">#1</span>
                            <span class="text-gray-700"> in </span>
                            <a href="{{ route('shop') }}?category={{ $product->category->slug }}" class="text-blue-600 hover:underline">
                                {{ $product->category->name }}
                            </a>
                        </div>
                        @if($product->category->parent)
                        <div>
                            <span class="font-semibold text-blue-700">#1</span>
                            <span class="text-gray-700"> in </span>
                            <a href="{{ route('shop') }}?category={{ $product->category->parent->slug }}" class="text-blue-600 hover:underline">
                                {{ $product->category->parent->name }}
                            </a>
                        </div>
                        @endif
                        @if($product->brand)
                        <div>
                            <span class="font-semibold text-blue-700">#32</span>
                            <span class="text-gray-700"> in </span>
                            <a href="{{ route('shop') }}?brand={{ $product->brand->slug }}" class="text-blue-600 hover:underline">
                                {{ $product->brand->name }} Products
                            </a>
                        </div>
                        @endif
                        <div>
                            <span class="font-semibold text-blue-700">#90</span>
                            <span class="text-gray-700"> in </span>
                            <a href="{{ route('shop') }}" class="text-blue-600 hover:underline">
                                All Products
                            </a>
                        </div>
                    </div>
                </div>
                @endif

            </div>

            <!-- Right Column: Price & Cart Sidebar (3 columns) -->
            <div class="lg:col-span-3">
                <div class="lg:sticky lg:top-[180px]">
                    <!-- Price & Cart Section (iHerb Style) -->
                    <div class="bg-white border border-gray-300 rounded-xl p-5 shadow-sm">
                        @if($product->product_type === 'variable' && $product->variants->count() > 1)
                            @php
                                $minPrice = $product->variants->min('sale_price') ?? $product->variants->min('price');
                                $maxPrice = $product->variants->max('sale_price') ?? $product->variants->max('price');
                            @endphp
                            <!-- Price Display -->
                            <div class="mb-3">
                                <div class="flex items-baseline space-x-2">
                                    <span class="text-3xl font-bold text-red-600">
                                        ${{ number_format($minPrice, 2) }}
                                    </span>
                                    @if($minPrice != $maxPrice)
                                        <span class="text-lg text-gray-600">
                                            - ${{ number_format($maxPrice, 2) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-600">
                                    ${{ number_format($minPrice / 50, 2) }}/ml
                                </div>
                            </div>
                        @else
                            @if($variant)
                                <!-- Price Display -->
                                <div class="mb-3">
                                    @if($variant->sale_price && $variant->sale_price < $variant->price)
                                        <div class="flex items-baseline space-x-2 mb-1">
                                            <span class="text-3xl font-bold text-red-600">
                                                ${{ number_format($variant->sale_price, 2) }}
                                            </span>
                                            <span class="text-sm font-semibold text-red-600">
                                                ({{ round((($variant->price - $variant->sale_price) / $variant->price) * 100) }}% off)
                                            </span>
                                        </div>
                                        <div class="flex items-baseline space-x-2">
                                            <span class="text-sm text-gray-500 line-through">
                                                ${{ number_format($variant->price, 2) }}
                                            </span>
                                            <span class="text-sm text-gray-600">
                                                ${{ number_format($variant->sale_price / ($variant->weight ?? 50), 2) }}/ml
                                            </span>
                                        </div>
                                    @else
                                        <div class="mb-1">
                                            <span class="text-3xl font-bold text-red-600">
                                                ${{ number_format($variant->price, 2) }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            ${{ number_format($variant->price / ($variant->weight ?? 50), 2) }}/ml
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endif
                        
                        <!-- Progress Bar & Claimed Text -->
                        @if($variant && $variant->stock_quantity > 0)
                        <div class="mb-4">
                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: 19%"></div>
                            </div>
                            <!-- Claimed Text -->
                            <div class="text-sm text-gray-700">
                                19% claimed
                            </div>
                        </div>
                        @endif
                        
                        <!-- Variant Selector (for variable products) -->
                        @if($product->product_type === 'variable' && $product->variants->count() > 1)
                        <div class="mb-4">
                            <x-variant-selector :product="$product" />
                        </div>
                        @endif
                        
                        <!-- Add to Cart Component -->
                        <div>
                            @livewire('cart.add-to-cart', ['product' => $product, 'defaultVariant' => $defaultVariant])
                        </div>
                    </div>
                    
                    <!-- Add to Lists Button (Separate) -->
                    <div class="mt-3">
                        <button class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-green-600 hover:border-green-500 hover:bg-green-50 transition font-semibold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span>Add to Lists</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Frequently Purchased Together -->
<x-frequently-purchased-together :product="$product" :relatedProducts="$relatedProducts" />

<!-- Inspired by Browsing -->
<x-inspired-by-browsing :products="$inspiredByBrowsing" />

<!-- Product Overview Section -->
@if($product->description)
<div class="bg-white py-8">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Product overview</h2>
        
        <div class="bg-white rounded-lg border border-gray-200 p-6 lg:p-8">
            <div class="prose max-w-none">
                {!! $product->description !!}
            </div>
        </div>
    </div>
</div>
@endif

<!-- Questions and Answers Section -->
<div class="bg-gray-50 py-8" id="questions-section">
    <div class="container mx-auto px-4">
        <div class="bg-white rounded-lg border border-gray-200 p-6 lg:p-8">
            <!-- Success Message -->
            @if(session('question_success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-green-800 font-medium">{{ session('question_success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Header -->
            <div class="mb-6 flex items-start justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Questions and answers</h2>
                    <p class="text-sm text-gray-600">
                        Answers posted solely reflect the views and opinions expressed by the contributors and not those of our store.
                    </p>
                </div>
                <div>
                    @livewire('product.ask-question', ['productId' => $product->id])
                </div>
            </div>

            <!-- Livewire Question List Component -->
            @livewire('product.question-list', ['productId' => $product->id])
        </div>
    </div>
</div>

<!-- Customer Reviews Section -->
<div class="bg-gray-50 py-8" id="reviews-section">
    <div class="container mx-auto px-4">
        <div class="bg-white rounded-lg border border-gray-200 p-6 lg:p-8">
            <!-- Header -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Customer Reviews</h2>
                <p class="text-sm text-gray-600">
                    Share your experience with this product and help other customers make informed decisions.
                </p>
            </div>

            <!-- Review List Component -->
            @livewire('product.review-list', ['productId' => $product->id])

            <!-- Review Form Component -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                @livewire('product.review-form', ['productId' => $product->id])
            </div>
        </div>
    </div>
</div>

<!-- Recently Viewed Products -->
@if($recentlyViewed->count() > 0)
<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <x-related-products :products="$recentlyViewed" title="Recently Viewed" />
    </div>
</div>
@endif

@endsection

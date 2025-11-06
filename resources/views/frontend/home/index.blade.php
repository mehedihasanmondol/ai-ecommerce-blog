@extends('layouts.app')

@section('title', 'Home - Health, Wellness & Beauty Products')

@section('meta')
    <meta name="description" content="Shop the best health, wellness, and beauty products. Free shipping on orders over $40. Quality guaranteed.">
    <meta name="keywords" content="health products, supplements, beauty, wellness, organic">
@endsection

@section('content')
<!-- Hero Slider -->
<x-frontend.hero-slider />

<!-- Recommended Products Slider -->
@php
    // Use featured products, or fallback to new arrivals if no featured products
    $recommendedProducts = $featuredProducts->count() > 0 ? $featuredProducts : $newArrivals;
@endphp

{{-- Debug: Show product count --}}
<!-- DEBUG: Featured Products: {{ $featuredProducts->count() }}, New Arrivals: {{ $newArrivals->count() }}, Recommended: {{ $recommendedProducts->count() }} -->

<x-frontend.recommended-slider :products="$recommendedProducts" />

<!-- Sale Offers Slider -->
<x-frontend.sale-offers-slider :products="$saleOffers" />

<!-- Shop by Category Section -->
<x-frontend.shop-by-category :categories="$featuredCategories" />

<!-- Promotional Banner -->
<section class="py-12 bg-gradient-to-r from-green-600 to-green-700 text-white">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <h2 class="text-3xl lg:text-4xl font-bold mb-4">Black Friday Month Sale!</h2>
            <p class="text-xl mb-6">Get up to 70% off on selected products. Limited time offer!</p>
            <a href="#" class="inline-block bg-white text-green-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-medium transition">
                Shop Sale Items
            </a>
        </div>
    </div>
</section>

<!-- New Arrivals -->
@if($newArrivals->count() > 0)
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold text-gray-900">New Arrivals</h2>
            <a href="#" class="text-green-600 hover:text-green-700 font-medium">View All →</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($newArrivals as $product)
            <x-frontend.product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Best Sellers -->
@if($bestSellers->count() > 0)
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Best Sellers</h2>
            <a href="#" class="text-green-600 hover:text-green-700 font-medium">View All →</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($bestSellers as $product)
            <x-frontend.product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Featured Brands -->
@if($featuredBrands->count() > 0)
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Featured Brands</h2>
            <a href="#" class="text-green-600 hover:text-green-700 font-medium">View All →</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @foreach($featuredBrands as $brand)
            <a href="#" class="group">
                <div class="bg-gray-50 rounded-lg p-6 text-center hover:shadow-lg transition">
                    @if($brand->logo_path)
                        <img src="{{ asset('storage/' . $brand->logo_path) }}" alt="{{ $brand->name }}" class="h-16 mx-auto object-contain">
                    @else
                        <div class="h-16 flex items-center justify-center">
                            <span class="text-lg font-bold text-gray-600">{{ $brand->name }}</span>
                        </div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Features Section -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-lg mb-2">Free Shipping</h3>
                <p class="text-gray-600">On orders over $40</p>
            </div>
            <div class="text-center">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-lg mb-2">Quality Guaranteed</h3>
                <p class="text-gray-600">100% authentic products</p>
            </div>
            <div class="text-center">
                <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-lg mb-2">Easy Returns</h3>
                <p class="text-gray-600">30-day return policy</p>
            </div>
            <div class="text-center">
                <div class="bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-lg mb-2">24/7 Support</h3>
                <p class="text-gray-600">Always here to help</p>
            </div>
        </div>
    </div>
</section>
@endsection

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

<!-- Trending Products Section -->
<x-frontend.trending-products :products="$trendingProducts" />

<!-- Best Sellers Section -->
<x-frontend.best-sellers :products="$bestSellerProducts" />
@endsection

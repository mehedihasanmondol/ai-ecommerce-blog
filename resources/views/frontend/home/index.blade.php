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
@if(\App\Models\SiteSetting::get('sale_offers_section_enabled', '1') === '1')
    <x-frontend.sale-offers-slider 
        :products="$saleOffers" 
        :title="\App\Models\SiteSetting::get('sale_offers_section_title', 'Sale Offers')" />
@endif

<!-- Shop by Category Section -->
<x-frontend.shop-by-category :categories="$featuredCategories" />

<!-- Trending Products Section -->
@if(\App\Models\SiteSetting::get('trending_section_enabled', '1') === '1')
    <x-frontend.trending-products 
        :products="$trendingProducts" 
        :title="\App\Models\SiteSetting::get('trending_section_title', 'Trending Now')" />
@endif

<!-- Best Sellers Section -->
@if(\App\Models\SiteSetting::get('best_sellers_section_enabled', '1') === '1')
    <x-frontend.best-sellers 
        :products="$bestSellerProducts" 
        :title="\App\Models\SiteSetting::get('best_sellers_section_title', 'Best Sellers')" />
@endif

<!-- New Arrivals Section -->
@if(\App\Models\SiteSetting::get('new_arrivals_section_enabled', '1') === '1')
    <x-frontend.new-arrivals 
        :products="$newArrivalProducts" 
        :title="\App\Models\SiteSetting::get('new_arrivals_section_title', 'New Arrivals')" />
@endif
@endsection

@extends('layouts.newspaper')

@section('title', $seoData['title'])

@push('meta')
<meta name="description" content="{{ $seoData['description'] }}">
<meta name="keywords" content="{{ $seoData['keywords'] }}">
<meta property="og:title" content="{{ $seoData['title'] }}">
<meta property="og:description" content="{{ $seoData['description'] }}">
<meta property="og:image" content="{{ $seoData['og_image'] }}">
<meta property="og:type" content="{{ $seoData['og_type'] }}">
<link rel="canonical" href="{{ $seoData['canonical'] }}">
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen">
    <x-frontend.news-post-list
        :posts="$posts"
        :totalPosts="$totalPosts"
        :breadcrumbs="$breadcrumbs"
        :currentUrl="$currentUrl"
        :category="$category"
        :pageTitle="$category->name"
        :loadMoreEndpoint="'/api/category/' . $category->slug . '/posts'"
        :latestPosts="$latestPosts"
        :popularPosts="$popularPosts"
        :latestVideo="$latestVideo" />
</div>
@endsection
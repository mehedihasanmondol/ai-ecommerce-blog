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
<x-frontend.news-post-list
    :posts="$posts"
    :totalPosts="$totalPosts"
    :breadcrumbs="$breadcrumbs"
    :currentUrl="$currentUrl"
    pageTitle="ভিডিও গ্যালারি"
    loadMoreEndpoint="{{ route('api.video-gallery.posts') }}"
    :latestPosts="$latestPosts"
    :popularPosts="$popularPosts"
    :showVideoThumbnails="true" />
@endsection
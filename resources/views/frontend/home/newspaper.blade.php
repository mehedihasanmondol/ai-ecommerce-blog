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
    <div class="bg-white min-h-screen">
        <div class="container mx-auto px-4 py-8">
            {{-- Blog Posts Content --}}
            <div class="text-center py-20">
                <p class="text-gray-600">Blog content will appear here when blog posts are published.</p>
            </div>
        </div>
    </div>
@endsection
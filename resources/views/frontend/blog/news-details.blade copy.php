@extends('layouts.app')

@section('title', $seoData['title'] ?? $post->title)

@section('description', $seoData['description'] ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 160))

@section('keywords', $seoData['keywords'] ?? 'blog, article')

@section('og_type', $seoData['og_type'] ?? 'article')
@section('og_title', $seoData['title'] ?? $post->title)
@section('og_description', $seoData['description'] ?? $post->excerpt)
@section('og_image', $seoData['og_image'] ?? asset('images/og-default.jpg'))
@section('canonical', $seoData['canonical'] ?? url($post->slug))

@section('twitter_card', 'summary_large_image')
@section('twitter_title', $seoData['title'] ?? $post->title)
@section('twitter_description', $seoData['description'] ?? $post->excerpt)
@section('twitter_image', $seoData['og_image'] ?? asset('images/og-default.jpg'))

@if(isset($seoData['author_name']))
@section('author', $seoData['author_name'])
@endif

@push('meta_tags')
<!-- Article Specific Meta -->
<meta property="article:published_time" content="{{ $seoData['published_at']->toIso8601String() }}">
<meta property="article:modified_time" content="{{ $seoData['updated_at']->toIso8601String() }}">
@if($post->author)
<meta property="article:author" content="{{ $post->author->name }}">
@endif
@if($post->categories && $post->categories->count() > 0)
@foreach($post->categories as $category)
<meta property="article:section" content="{{ $category->name }}">
@endforeach
@endif
@foreach($post->tags as $tag)
<meta property="article:tag" content="{{ $tag->name }}">
@endforeach

<!-- Breadcrumb Schema -->

@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/news-details.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/news-details.js') }}" defer></script>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen py-6">
    <!-- Breadcrumbs -->
    <div class="container mx-auto px-4 mb-4">
        <nav class="flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('home') }}" class="hover:text-green-600 transition-colors">{{ \App\Models\SiteSetting::get('site_name', 'হোম') }}</a>
            <span>/</span>
            @if($post->categories && $post->categories->count() > 0)
            @foreach($post->categories as $index => $category)
            <a href="{{ url($category->slug) }}" class="hover:text-green-600 transition-colors">
                {{ $category->name }}
            </a>
            @if(!$loop->last)
            <span>/</span>
            @endif
            @endforeach
            <span>/</span>
            @endif
            <span class="text-gray-400">{{ \Illuminate\Support\Str::limit($post->title, 50) }}</span>
        </nav>
        <!-- 3-Column Layout -->
        <div class="news-details-container">
            <!-- Left Sidebar (Sticky) -->
            <aside class="news-sidebar-left">
                <!-- Author Box -->
                <div class="author-box">
                    @if($post->author)
                    @if($post->author->authorProfile?->media)
                    <img src="{{ $post->author->authorProfile->media->medium_url }}"
                        alt="{{ $post->author->name }}">
                    @elseif($post->author->authorProfile?->avatar)
                    <img src="{{ asset('storage/' . $post->author->authorProfile->avatar) }}"
                        alt="{{ $post->author->name }}">
                    @elseif($post->author->media)
                    <img src="{{ $post->author->media->medium_url }}"
                        alt="{{ $post->author->name }}">
                    @elseif($post->author->avatar)
                    <img src="{{ asset('storage/' . $post->author->avatar) }}"
                        alt="{{ $post->author->name }}">
                    @else
                    <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #059669); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 2rem; font-weight: bold;">
                        {{ substr($post->author->name, 0, 1) }}
                    </div>
                    @endif

                    <h3>{{ $post->author->name }}</h3>

                    @if($post->author->authorProfile?->bio)
                    <p class="bio">{{ \Illuminate\Support\Str::limit($post->author->authorProfile->bio, 100) }}</p>
                    @endif
                    @endif

                    <!-- Post Time in Bangla -->
                    <div class="post-time">
                        {{ bengali_time_ago($post->published_at) }}
                    </div>
                </div>

                <!-- Social Share & Controls -->
                <div class="sidebar-controls">
                    <h4>শেয়ার করুন</h4>
                    <div class="social-share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                            target="_blank"
                            class="facebook social-share-btn"
                            data-platform="facebook">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                            Facebook
                        </a>

                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}"
                            target="_blank"
                            class="twitter social-share-btn"
                            data-platform="twitter">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                            Twitter
                        </a>

                        <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . request()->url()) }}"
                            target="_blank"
                            class="whatsapp social-share-btn"
                            data-platform="whatsapp">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                            WhatsApp
                        </a>

                        <a href="fb-messenger://share/?link={{ urlencode(request()->url()) }}"
                            class="messenger social-share-btn"
                            data-platform="messenger">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.373 0 0 4.974 0 11.111c0 3.498 1.744 6.614 4.469 8.654V24l4.088-2.242c1.092.3 2.246.464 3.443.464 6.627 0 12-4.974 12-11.111C24 4.974 18.627 0 12 0zm1.191 14.963l-3.055-3.26-5.963 3.26L10.732 8l3.131 3.26L19.752 8l-6.561 6.963z" />
                            </svg>
                            Messenger
                        </a>

                        <button class="print print-article-btn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            প্রিন্ট
                        </button>
                    </div>

                    <h4>ফন্ট সাইজ</h4>
                    <div class="font-size-controls">
                        <button class="font-size-btn" data-size="small">ছোট</button>
                        <button class="font-size-btn active" data-size="medium">মাঝারি</button>
                        <button class="font-size-btn" data-size="large">বড়</button>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="news-main-content font-size-medium">
                <!-- Post Title -->
                <div class="px-8 pt-8 pb-4">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight">{{ $post->title }}</h1>
                </div>

                <!-- Featured Media -->
                <div class="px-8 pb-6">
                    @if($post->youtube_url && $post->youtube_video_id)
                    <!-- YouTube Video First -->
                    <div class="relative rounded-xl overflow-hidden shadow-lg" style="padding-bottom: 56.25%; height: 0;">
                        <iframe
                            src="{{ $post->youtube_embed_url }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                            class="absolute top-0 left-0 w-full h-full">
                        </iframe>
                    </div>
                    @endif

                    @if($post->media || $post->featured_image)
                    <div class="@if($post->youtube_url && $post->youtube_video_id) mt-4 @endif">
                        @if($post->media)
                        <img src="{{ $post->media->large_url }}"
                            alt="{{ $post->featured_image_alt ?? $post->title }}"
                            class="w-full rounded-xl">
                        @elseif($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}"
                            alt="{{ $post->featured_image_alt }}"
                            class="w-full rounded-xl">
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Post Content -->
                <div class="px-8 pb-8">
                    <div class="prose prose-lg max-w-none">
                        {!! $post->content !!}
                    </div>
                </div>

                <!-- Tags -->
                @if($post->tags && $post->tags->count() > 0)
                <div class="px-8 py-6 border-t border-gray-200">
                    <div class="flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                        <a href="{{ route('blog.tag', $tag->slug) }}"
                            class="inline-block bg-gray-100 hover:bg-green-100 text-gray-700 hover:text-green-700 px-4 py-2 rounded-full transition duration-150 text-sm">
                            #{{ $tag->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Related News (সংশ্লিষ্ট খবর) -->
                @if($relatedPosts->count() > 0)
                <div class="related-news">
                    <h2>
                        @if(\App\Models\SiteSetting::get('site_logo'))
                        <img src="{{ asset('storage/' . \App\Models\SiteSetting::get('site_logo')) }}"
                            alt="{{ \App\Models\SiteSetting::get('site_name') }}"
                            style="height: 32px; width: auto;">
                        @endif
                        সংশ্লিষ্ট খবর
                    </h2>
                    <div class="related-news-grid">
                        @foreach($relatedPosts->take(5) as $related)
                        <a href="{{ url($related->slug) }}" class="related-news-item">
                            @if($related->media || $related->featured_image)
                            @if($related->media)
                            <img src="{{ $related->media->medium_url }}" alt="{{ $related->title }}">
                            @elseif($related->featured_image)
                            <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}">
                            @endif
                            @else
                            <div style="width: 100%; height: 120px; background: linear-gradient(135deg, #f3f4f6, #e5e7eb); display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @endif
                            <div class="content">
                                <h3>{{ \Illuminate\Support\Str::limit($related->title, 80) }}</h3>
                                <div class="meta">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ bengali_time_ago($related->published_at) }}</span>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Bottom Share Icons -->
                <div class="px-8 py-6 border-t border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">শেয়ার করুন:</h3>
                    <div class="flex space-x-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url($post->slug)) }}"
                            target="_blank"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                            Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url($post->slug)) }}&text={{ urlencode($post->title) }}"
                            target="_blank"
                            class="bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                            Twitter
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url($post->slug)) }}"
                            target="_blank"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                            WhatsApp
                        </a>
                    </div>
                </div>
            </main>

            <!-- Right Sidebar -->
            <aside class="news-sidebar-right">
                <!-- Ad Placeholder -->
                <div class="sidebar-widget">
                    <div class="ad-placeholder">
                        বিজ্ঞাপন<br>300×600
                    </div>
                </div>

                <!-- Latest News Widget -->
                @if(isset($latestPosts) && $latestPosts->count() > 0)
                <div class="sidebar-widget">
                    <div class="sidebar-widget-header">
                        সর্বশেষ খবর
                    </div>
                    <div class="sidebar-widget-content">
                        <div class="scrollable-news">
                            @foreach($latestPosts->take(10) as $latestPost)
                            <a href="{{ url($latestPost->slug) }}" class="news-list-item">
                                @if($latestPost->media || $latestPost->featured_image)
                                @if($latestPost->media)
                                <img src="{{ $latestPost->media->small_url }}" alt="{{ $latestPost->title }}">
                                @elseif($latestPost->featured_image)
                                <img src="{{ asset('storage/' . $latestPost->featured_image) }}" alt="{{ $latestPost->title }}">
                                @endif
                                @else
                                <div style="width: 80px; height: 60px; background: #f3f4f6; border-radius: 0.375rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                @endif
                                <div class="content">
                                    <h4>{{ \Illuminate\Support\Str::limit($latestPost->title, 80) }}</h4>
                                    <div class="time">{{ bengali_time_ago($latestPost->published_at) }}</div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Most Read Widget -->
                @if(isset($mostReadPosts) && $mostReadPosts->count() > 0)
                <div class="sidebar-widget">
                    <div class="sidebar-widget-header">
                        সর্বাধিক পঠিত
                    </div>
                    <div class="sidebar-widget-content">
                        <div class="scrollable-news">
                            @foreach($mostReadPosts->take(10) as $mostRead)
                            <a href="{{ url($mostRead->slug) }}" class="news-list-item">
                                @if($mostRead->media || $mostRead->featured_image)
                                @if($mostRead->media)
                                <img src="{{ $mostRead->media->small_url }}" alt="{{ $mostRead->title }}">
                                @elseif($mostRead->featured_image)
                                <img src="{{ asset('storage/' . $mostRead->featured_image) }}" alt="{{ $mostRead->title }}">
                                @endif
                                @else
                                <div style="width: 80px; height: 60px; background: #f3f4f6; border-radius: 0.375rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                @endif
                                <div class="content">
                                    <h4>{{ \Illuminate\Support\Str::limit($mostRead->title, 80) }}</h4>
                                    <div class="time">{{ bengali_number(number_format($mostRead->views_count)) }} বার পড়া হয়েছে</div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </aside>
        </div>
    </div>

</div>
@endsection
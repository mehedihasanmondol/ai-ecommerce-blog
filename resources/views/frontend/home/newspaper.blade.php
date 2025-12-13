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

    {{-- Header Banner Advertisement --}}
    <div class="container mx-auto px-4">
        <x-advertisement.ad-banner slot-slug="header-banner" />
    </div>

    <div class="container mx-auto px-4 py-4">
        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Left Column: Featured + News Grid (9 columns) --}}
            <div class="lg:col-span-9 space-y-6">

                {{-- Featured Section: 50/50 Split --}}
                <div class="bg-white shadow-md overflow-hidden">
                    <div class="grid md:grid-cols-2 gap-0">
                        {{-- Left 50%: Main Featured Post --}}
                        @if($featuredPost)
                        <article class="border-r border-gray-200">
                            {{-- Featured Image --}}
                            <a href="{{ url('/' . $featuredPost->slug) }}" class="block">
                                <div class="relative h-80 overflow-hidden">
                                    @if($featuredPost->media)
                                    <img src="{{ $featuredPost->media->large_url }}"
                                        alt="{{ $featuredPost->title }}"
                                        class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400">ছবি নেই</span>
                                    </div>
                                    @endif
                                    @if($featuredPost->is_featured)
                                    <div class="absolute bottom-4 left-4 right-4">
                                        <span class="bg-red-600 text-white px-3 py-1 text-xs font-bold inline-block">
                                            প্রধান সংবাদ
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </a>

                            {{-- Featured Content --}}
                            <div class="p-6">
                                <h1 class="text-2xl font-bold mb-3 leading-tight hover:text-blue-600 transition-colors">
                                    <a href="{{ url('/' . $featuredPost->slug) }}">
                                        {{ $featuredPost->title }}
                                    </a>
                                </h1>

                                {{-- Excerpt --}}
                                <a href="{{ url('/' . $featuredPost->slug) }}" class="block">
                                    <p class="text-gray-700 mb-4 text-sm leading-relaxed line-clamp-3 hover:text-gray-900 transition-colors">
                                        {{ $featuredPost->excerpt }}
                                    </p>
                                </a>

                                {{-- Meta Info --}}
                                <div class="flex items-center gap-4 text-xs text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ bengali_date($featuredPost->published_at, 'short_time') }}
                                    </span>
                                </div>
                            </div>
                        </article>
                        @endif

                        {{-- Right 50%: List of Next 4 Featured Posts --}}
                        <div class="bg-white">
                            @if($topStories->isNotEmpty())
                            <div class="divide-y divide-gray-200">
                                @foreach($topStories->skip(1)->take(4) as $story)
                                <article class="flex gap-4 p-4 hover:bg-white transition-colors group">
                                    {{-- Thumbnail --}}
                                    <div class="flex-shrink-0">
                                        <a href="{{ url('/' . $story->slug) }}" class="block">
                                            <div class="w-28 h-20 overflow-hidden">
                                                @if($story->media)
                                                <img src="{{ $story->media->small_url }}"
                                                    alt="{{ $story->title }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                @else
                                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                                    <span class="text-gray-400 text-xs">ছবি নেই</span>
                                                </div>
                                                @endif
                                            </div>
                                        </a>
                                    </div>

                                    {{-- Content --}}
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-base mb-2 line-clamp-2 leading-snug group-hover:text-blue-600 transition-colors">
                                            <a href="{{ url('/' . $story->slug) }}">
                                                {{ $story->title }}
                                            </a>
                                        </h3>
                                        <div class="text-xs text-gray-500 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ bengali_date($story->published_at, 'short_time') }}
                                        </div>
                                    </div>
                                </article>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- 3-Column News Grid --}}
                @if($topStories->count() > 5)
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($topStories->skip(5) as $story)
                    <article class="bg-white shadow-md overflow-hidden group hover:shadow-lg transition-shadow">
                        {{-- Image --}}
                        <a href="{{ url('/' . $story->slug) }}" class="block">
                            <div class="relative h-48 overflow-hidden">
                                @if($story->media)
                                <img src="{{ $story->media->medium_url }}"
                                    alt="{{ $story->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400 text-sm">ছবি নেই</span>
                                </div>
                                @endif
                            </div>
                        </a>

                        {{-- Content --}}
                        <div class="p-4">
                            <h3 class="font-bold text-base mb-3 line-clamp-2 leading-snug group-hover:text-blue-600 transition-colors">
                                <a href="{{ url('/' . $story->slug) }}">
                                    {{ $story->title }}
                                </a>
                            </h3>
                            <a href="{{ url('/' . $story->slug) }}" class="block">
                                <p class="text-sm text-gray-600 line-clamp-3 mb-3 leading-relaxed hover:text-gray-900 transition-colors">
                                    {{ Str::limit($story->excerpt, 120) }}
                                </p>
                            </a>
                            <div class="text-xs text-gray-500 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ bengali_date($story->published_at, 'short_time') }}
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
                @endif



            </div>

            {{-- Right Sidebar (3 columns) --}}
            <aside class="lg:col-span-3 space-y-6">

                {{-- Sidebar Advertisement --}}
                <x-advertisement.ad-banner slot-slug="sidebar-top" />

                {{-- Latest & Popular News Tabs (Reusable Component) --}}
                <x-news-tabs :latestPosts="$latestPosts" :popularPosts="$popularPosts" />


            </aside>
        </div>
    </div>

    {{-- Homepage Mid Content Advertisement --}}
    <div class="container mx-auto px-4 py-6">
        <x-advertisement.ad-banner slot-slug="homepage-mid-content" />
    </div>

    <div class="container mx-auto px-4">
        {{-- Featured Category Content Grid --}}
        {{-- Featured Category Sections --}}
        @if($featuredCategoriesEnabled == '1' && !empty($featuredCategorySections))
        @foreach($featuredCategorySections as $section)
        <div class="lazy-category-section grid grid-cols-1 lg:grid-cols-12 gap-6 py-2"
            data-category-id="{{ $section['category']->id }}"
            data-loaded="false">

            {{-- Loading Skeleton --}}
            <div class="lg:col-span-9 space-y-6">
                <div class="bg-white shadow-md overflow-hidden p-6">
                    <div class="animate-pulse">
                        {{-- Header Skeleton --}}
                        <div class="flex items-center justify-between mb-4">
                            <div class="h-8 bg-gray-200 rounded w-1/3"></div>
                            <div class="h-8 w-20 bg-gray-200 rounded"></div>
                        </div>

                        {{-- Main Content Skeleton --}}
                        <div class="grid md:grid-cols-3 gap-4 mb-4">
                            {{-- Large featured post skeleton --}}
                            <div class="md:col-span-2">
                                <div class="h-64 bg-gray-200 rounded mb-4"></div>
                                <div class="h-6 bg-gray-200 rounded w-3/4 mb-2"></div>
                                <div class="h-4 bg-gray-200 rounded w-full mb-2"></div>
                                <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                            </div>

                            {{-- Side posts skeleton --}}
                            <div class="md:col-span-1 space-y-4">
                                <div class="h-32 bg-gray-200 rounded"></div>
                                <div class="h-32 bg-gray-200 rounded"></div>
                            </div>
                        </div>

                        {{-- Grid Posts Skeleton --}}
                        <div class="grid md:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                            <div class="h-24 bg-gray-200 rounded"></div>
                            <div class="h-24 bg-gray-200 rounded"></div>
                            <div class="h-24 bg-gray-200 rounded"></div>
                            <div class="h-24 bg-gray-200 rounded"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Skeleton --}}
            <aside class="lg:col-span-3">
                <div class="animate-pulse space-y-4">
                    <div class="h-64 bg-gray-200 rounded"></div>
                    <div class="h-48 bg-gray-200 rounded"></div>
                </div>
            </aside>
        </div>

        {{-- Category Divider Advertisement --}}
        <div class="py-6">
            <x-advertisement.ad-banner slot-slug="homepage-category-divider" />
        </div>

        @endforeach
        @endif
    </div>

    {{-- Homepage Bottom Advertisement --}}
    <div class="container mx-auto px-4 py-6">
        <x-advertisement.ad-banner slot-slug="homepage-bottom" />
    </div>

    {{-- Mobile Sticky Footer Advertisement (Mobile Only) --}}
    <div class="md:hidden">
        <x-advertisement.ad-banner slot-slug="mobile-sticky-footer" />
    </div>

</div>

@push('scripts')
@vite('resources/js/lazy-category-loader.js')
@endpush

@endsection
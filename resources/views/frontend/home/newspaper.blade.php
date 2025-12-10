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

    {{-- Full-Width Advertisement Placeholder --}}
    <div class="container mx-auto px-4">
        <div class="h-24 bg-white rounded flex items-center justify-center border-2 border-dashed border-gray-300">
            <span class="text-gray-400"> বিজ্ঞাপন ৯৭০ × ৯০ (Full Width Banner)</span>
        </div>

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

                            {{-- Featured Content --}}
                            <div class="p-6">
                                <h1 class="text-2xl font-bold mb-3 leading-tight hover:text-blue-600 transition-colors">
                                    <a href="{{ url('/' . $featuredPost->slug) }}">
                                        {{ $featuredPost->title }}
                                    </a>
                                </h1>

                                {{-- Excerpt --}}
                                <p class="text-gray-700 mb-4 text-sm leading-relaxed line-clamp-3">
                                    {{ $featuredPost->excerpt }}
                                </p>

                                {{-- Meta Info --}}
                                <div class="flex items-center gap-4 text-xs text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ bengali_date($featuredPost->published_at, 'short') }}
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
                                            {{ bengali_date($story->published_at, 'short') }}
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

                        {{-- Content --}}
                        <div class="p-4">
                            <h3 class="font-bold text-base mb-3 line-clamp-2 leading-snug group-hover:text-blue-600 transition-colors">
                                <a href="{{ url('/' . $story->slug) }}">
                                    {{ $story->title }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-600 line-clamp-3 mb-3 leading-relaxed">
                                {{ Str::limit($story->excerpt, 120) }}
                            </p>
                            <div class="text-xs text-gray-500 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ bengali_date($story->published_at, 'short') }}
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
                @endif



            </div>

            {{-- Right Sidebar (3 columns) --}}
            <aside class="lg:col-span-3 space-y-6">

                {{-- Ad Placeholder --}}
                <div class="bg-gray-100 shadow-md text-center">
                    <div class="h-64 bg-white rounded flex items-center justify-center border-2 border-dashed border-gray-300">
                        <span class="text-gray-400">বিজ্ঞাপন ৩০০ × ২৫০</span>
                    </div>

                </div>

                {{-- Latest & Popular News Tabs (Reusable Component) --}}
                <x-news-tabs :latestPosts="$latestPosts" :popularPosts="$popularPosts" />


            </aside>
        </div>
    </div>



    <div class="container mx-auto px-4 py-4">
        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 ">
            {{-- Left Column: Featured + News Grid (9 columns) --}}
            <div class="lg:col-span-9 space-y-6">

                {{-- Featured Category Sections --}}
                @if($featuredCategoriesEnabled == '1' && !empty($featuredCategorySections))
                @foreach($featuredCategorySections as $section)
                <div class="bg-white shadow-md overflow-hidden">
                    {{-- Category Header --}}
                    <div class="px-6 py-3 flex items-center justify-between bg-white">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <span class="w-1 h-6 bg-red-600"></span>
                            {{ $section['category']->name }}
                        </h2>
                        @if($siteLogo = \App\Models\SiteSetting::get('site_logo'))
                        <a href="{{ url('/' . $section['category']->slug) }}"
                            class="flex items-center hover:opacity-80 transition-opacity">

                            <img src="{{ asset('storage/' . $siteLogo) }}"
                                alt="{{ \App\Models\SiteSetting::get('site_name', config('app.name')) }}"
                                class="h-8 w-auto object-contain">

                        </a>
                        @endif
                    </div>

                    {{-- Top Section: 2/3 + 1/3 Grid --}}
                    <div class="grid md:grid-cols-3 gap-4 ">
                        {{-- Left 2/3: Main Featured Post --}}
                        @if($section['posts']->isNotEmpty())
                        @php $featuredPost = $section['posts']->first(); @endphp
                        <article class="md:col-span-2 border-r border-gray-200">
                            {{-- Featured Image --}}
                            <div class="relative overflow-hidden" style="padding-top: 66.67%;">
                                @if($featuredPost->media)
                                <img src="{{ $featuredPost->media->large_url }}"
                                    alt="{{ $featuredPost->title }}"
                                    class="absolute inset-0 w-full h-full object-cover">
                                @else
                                <div class="absolute inset-0 w-full h-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400">ছবি নেই</span>
                                </div>
                                @endif
                                @if($featuredPost->is_featured)
                                <div class="absolute bottom-4 left-4 right-4">
                                    <span class="bg-red-600 text-white px-3 py-1 text-xs font-bold inline-block">
                                        ফিচারড
                                    </span>
                                </div>
                                @endif
                            </div>

                            {{-- Featured Content --}}
                            <div class="p-6">
                                <h3 class="text-2xl font-bold mb-3 leading-tight hover:text-blue-600 transition-colors">
                                    <a href="{{ url('/' . $featuredPost->slug) }}">
                                        {{ $featuredPost->title }}
                                    </a>
                                </h3>

                                {{-- Excerpt --}}
                                @if($featuredPost->excerpt)
                                <p class="text-gray-700 mb-4 text-sm leading-relaxed line-clamp-3">
                                    {{ $featuredPost->excerpt }}
                                </p>
                                @endif

                                {{-- Meta Info --}}
                                <div class="flex items-center gap-4 text-xs text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ bengali_date($featuredPost->published_at, 'short') }}
                                    </span>
                                </div>
                            </div>
                        </article>

                        {{-- Right 1/3: List of Next 2 Posts --}}
                        <div class="md:col-span-1 flex flex-col gap-4 pr-4">
                            @if($section['posts']->count() > 1)
                            @foreach($section['posts']->skip(1)->take(2) as $post)
                            <article class="flex-1 flex flex-col group bg-white">
                                {{-- Thumbnail --}}
                                <div class="relative overflow-hidden" style="padding-top: 66.67%;">
                                    @if($post->media)
                                    <img src="{{ $post->media->medium_url }}"
                                        alt="{{ $post->title }}"
                                        class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                    <div class="absolute inset-0 w-full h-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400 text-sm">ছবি নেই</span>
                                    </div>
                                    @endif
                                </div>

                                {{-- Content --}}
                                <div class="p-4 flex-1">
                                    <h4 class="font-bold text-base mb-2 line-clamp-2 leading-snug group-hover:text-blue-600 transition-colors">
                                        <a href="{{ url('/' . $post->slug) }}">
                                            {{ $post->title }}
                                        </a>
                                    </h4>
                                    <div class="text-xs text-gray-500 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ bengali_date($post->published_at, 'short') }}
                                    </div>
                                </div>
                            </article>
                            @endforeach
                            @endif
                        </div>
                        @endif
                    </div>

                    {{-- 3-Column News Grid (Posts 4, 5, 6) --}}
                    @if($section['posts']->count() > 3)
                    <div class="grid md:grid-cols-2 gap-6 p-4 border-t border-gray-200">
                        @foreach($section['posts']->skip(3)->take(4) as $post)

                        <article class="bg-white overflow-hidden group ">
                            <div class="grid md:grid-cols-2 gap-6 ">

                                {{-- Image --}}
                                <div class="relative overflow-hidden">
                                    @if($post->media)
                                    <img src="{{ $post->media->medium_url }}"
                                        alt="{{ $post->title }}"
                                        class="w-full  group-hover:scale-105 transition-transform duration-300">
                                    @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400 text-sm">ছবি নেই</span>
                                    </div>
                                    @endif
                                </div>


                                {{-- Content --}}
                                <div class="">
                                    <h4 class="font-bold text-base mb-3 line-clamp-2 leading-snug group-hover:text-blue-600 transition-colors">
                                        <a href="{{ url('/' . $post->slug) }}">
                                            {{ $post->title }}
                                        </a>
                                    </h4>
                                    @if($post->excerpt)
                                    <p class="text-sm text-gray-600 line-clamp-3 mb-3 leading-relaxed">
                                        {{ Str::limit($post->excerpt, 120) }}
                                    </p>
                                    @endif
                                    <div class="text-xs text-gray-500 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ bengali_date($post->published_at, 'short') }}
                                    </div>
                                </div>
                            </div>
                        </article>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
                @endif

            </div>

            {{-- Right Sidebar (3 columns) --}}
            <aside class="lg:col-span-3 space-y-6">



                {{-- Ad Placeholder --}}
                <div class="bg-gray-100 shadow-md p-6 text-center">
                    <div class="text-xs text-gray-500 mb-2">বিজ্ঞাপন</div>
                    <div class="h-64 bg-white rounded flex items-center justify-center border-2 border-dashed border-gray-300">
                        <span class="text-gray-400">৩০০ × ২৫০</span>
                    </div>
                </div>

            </aside>
        </div>
    </div>


</div>
@endsection
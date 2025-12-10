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
        {{-- Headline Banner --}}
        @if($headlineBanner['enabled'] ?? false)
            <div class="bg-red-600 text-white py-2 px-4">
                <div class="container mx-auto">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-sm">{{ $headlineBanner['label'] ?? 'জরুরি সংবাদ' }}</span>
                        <span class="text-sm">{{ $headlineBanner['text'] ?? '' }}</span>
                    </div>
                </div>
            </div>
        @endif

        <div class="container mx-auto px-4 py-6">
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
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                                        @foreach($topStories->take(4) as $story)
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
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                    @if($topStories->count() > 4)
                        <div class="grid md:grid-cols-3 gap-6">
                            @foreach($topStories->skip(4) as $story)
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
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ bengali_date($story->published_at, 'short') }}
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif

                    {{-- Category Sections --}}
                    @foreach($categorySections as $section)
                        <div class="bg-white shadow-md p-6">
                            <div class="flex items-center justify-between mb-4 pb-2 border-b-2 border-blue-600">
                                <h2 class="text-xl font-bold">{{ $section['category']->name }}</h2>
                                <a href="{{ route('blog.category', $section['category']->slug) }}" 
                                   class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                                    সব দেখুন →
                                </a>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                @foreach($section['posts'] as $post)
                                    <article class="flex gap-4 group">
                                        {{-- Thumbnail --}}
                                        <div class="flex-shrink-0">
                                            <div class="w-32 h-24 overflow-hidden">
                                                @if($post->media)
                                                    <img src="{{ $post->media->small_url }}" 
                                                         alt="{{ $post->title }}"
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
                                            <h3 class="font-bold text-sm mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                                <a href="{{ url('/' . $post->slug) }}">
                                                    {{ $post->title }}
                                                </a>
                                            </h3>
                                            <div class="text-xs text-gray-500">
                                                {{ $post->published_at?->diffForHumans() }}
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                </div>

                {{-- Right Sidebar (3 columns) --}}
                <aside class="lg:col-span-3 space-y-6">
                    
                    {{-- Latest News --}}
                    @if($latestPosts->isNotEmpty())
                        <div class="bg-white shadow-md p-6">
                            <h3 class="text-lg font-bold mb-4 pb-2 border-b-2 border-green-600">সর্বশেষ সংবাদ</h3>
                            <div class="space-y-4">
                                @foreach($latestPosts as $post)
                                    <article class="group">
                                        <h4 class="font-bold text-sm mb-1 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                            <a href="{{ url('/' . $post->slug) }}">
                                                {{ $post->title }}
                                            </a>
                                        </h4>
                                        <div class="text-xs text-gray-500">
                                            {{ $post->published_at?->diffForHumans() }}
                                        </div>
                                    </article>
                                    @if(!$loop->last)
                                        <hr class="border-gray-200">
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Popular/Most Viewed --}}
                    @if($popularPosts->isNotEmpty())
                        <div class="bg-white shadow-md p-6">
                            <h3 class="text-lg font-bold mb-4 pb-2 border-b-2 border-orange-600">জনপ্রিয় সংবাদ</h3>
                            <div class="space-y-4">
                                @foreach($popularPosts as $index => $post)
                                    <article class="flex gap-3 group">
                                        <div class="flex-shrink-0">
                                            <span class="inline-flex items-center justify-center w-8 h-8 bg-orange-100 text-orange-600 font-bold rounded text-sm">
                                                {{ $index + 1 }}
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-bold text-sm mb-1 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                                <a href="{{ url('/' . $post->slug) }}">
                                                    {{ $post->title }}
                                                </a>
                                            </h4>
                                            <div class="text-xs text-gray-500">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    {{ number_format($post->views_count) }} বার পঠিত
                                                </span>
                                            </div>
                                        </div>
                                    </article>
                                    @if(!$loop->last)
                                        <hr class="border-gray-200">
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

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
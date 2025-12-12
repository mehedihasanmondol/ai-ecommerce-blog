@props([
'posts',
'totalPosts',
'breadcrumbs',
'currentUrl',
'category' => null,
'pageTitle' => 'সর্বশেষ',
'loadMoreEndpoint' => null,
'latestPosts' => null,
'popularPosts' => null,
'latestVideo' => null,
])
<div class="bg-white">
    <div class="container mx-auto ">
        {{-- Breadcrumbs and Filter Toggle --}}
        <div class="mb-3 relative ">
            <div id="breadcrumbs-section" class="flex items-center justify-between px-4 py-3">
                {{-- Breadcrumbs --}}
                <nav class="flex items-center gap-2 text-sm text-gray-600">
                    <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors">হোম</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>

                    @foreach($breadcrumbs as $index => $crumb)
                    @if($loop->last)
                    <span class="font-semibold text-gray-900">{{ $crumb['name'] }}</span>
                    @else
                    <a href="{{ $crumb['url'] }}" class="hover:text-blue-600 transition-colors">{{ $crumb['name'] }}</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    @endif
                    @endforeach
                </nav>

                {{-- Filter Toggle Button --}}
                <button onclick="toggleFilter()"
                    id="filter-toggle-btn"
                    class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span class="text-sm font-semibold">ফিল্টার</span>
                </button>
            </div>

            {{-- Filter Section (Hidden by default) --}}
            <div id="filter-section" class="hidden absolute top-0 left-0 right-0 bg-white  px-4 py-3 rounded z-10">
                <form method="GET" action="{{ $currentUrl }}" class="flex flex-wrap items-center gap-4">
                    {{-- Sort By --}}
                    <div class="flex items-center gap-2">
                        <label for="sort" class="text-sm font-semibold text-gray-700 whitespace-nowrap">সাজান:</label>
                        <select name="sort" id="sort" class="px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>সর্বশেষ</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>পুরাতন</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>জনপ্রিয়</option>
                            <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>ফিচারড</option>
                        </select>
                    </div>

                    {{-- Post Type Filter --}}
                    <div class="flex items-center gap-2">
                        <label for="post_type" class="text-sm font-semibold text-gray-700 whitespace-nowrap">পোস্ট টাইপ:</label>
                        <select name="post_type" id="post_type" class="px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="" {{ request('post_type') == '' ? 'selected' : '' }}>সব</option>
                            <option value="article" {{ request('post_type') == 'article' ? 'selected' : '' }}>আর্টিকেল</option>
                            <option value="video" {{ request('post_type') == 'video' ? 'selected' : '' }}>ভিডিও</option>
                        </select>
                    </div>

                    {{-- Subcategory Filter (only if category has children) --}}
                    @if($category && $category->children()->where('is_active', true)->count() > 0)
                    <div class="flex items-center gap-2">
                        <label for="subcategory" class="text-sm font-semibold text-gray-700 whitespace-nowrap">বিভাগ:</label>
                        <select name="subcategory" id="subcategory" class="px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="">সকল {{ $category->name }}</option>
                            @foreach($category->children()->where('is_active', true)->orderBy('sort_order')->get() as $subcat)
                            <option value="{{ $subcat->slug }}" {{ request('subcategory') == $subcat->slug ? 'selected' : '' }}>
                                {{ $subcat->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- Search --}}
                    <div class="flex items-center gap-2 flex-1">
                        <label for="search" class="text-sm font-semibold text-gray-700 whitespace-nowrap">অনুসন্ধান:</label>
                        <input type="text"
                            name="search"
                            id="search"
                            value="{{ request('search') }}"
                            placeholder="শিরোনাম খুঁজুন..."
                            class="flex-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-center gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm font-semibold transition-colors">
                            প্রয়োগ করুন
                        </button>
                        <a href="{{ $currentUrl }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded text-sm font-semibold transition-colors">
                            রিসেট
                        </a>
                        <button type="button" onclick="toggleFilter()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded text-sm font-semibold transition-colors">
                            বন্ধ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="container mx-auto px-4 ">

    {{-- Active Filters Display --}}
    @if(request('sort') || request('search') || request('subcategory') || request('post_type'))
    <div class="mb-4 px-4">
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-sm font-semibold text-gray-700">সক্রিয় ফিল্টার:</span>

            {{-- Sort Filter Badge --}}
            @if(request('sort') && request('sort') !== 'latest')
            <span class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 px-3 py-1.5 rounded-full text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                </svg>
                <span>সাজান:
                    @if(request('sort') == 'oldest') পুরাতন
                    @elseif(request('sort') == 'popular') জনপ্রিয়
                    @elseif(request('sort') == 'featured') ফিচারড
                    @endif
                </span>
                <a href="{{ url()->current() }}?{{ http_build_query(array_filter(request()->except('sort'))) }}"
                    class="hover:text-blue-900 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
            @endif

            {{-- Subcategory Filter Badge --}}
            @if(request('subcategory') && $category)
            @php
            $selectedSubcat = $category->children()->where('is_active', true)->where('slug', request('subcategory'))->first();
            @endphp
            @if($selectedSubcat)
            <span class="inline-flex items-center gap-2 bg-purple-100 text-purple-800 px-3 py-1.5 rounded-full text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <span>বিভাগ: {{ $selectedSubcat->name }}</span>
                <a href="{{ url()->current() }}?{{ http_build_query(array_filter(request()->except('subcategory'))) }}"
                    class="hover:text-purple-900 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
            @endif
            @endif

            {{-- Search Filter Badge --}}
            @if(request('search'))
            <span class="inline-flex items-center gap-2 bg-green-100 text-green-800 px-3 py-1.5 rounded-full text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span>অনুসন্ধান: "{{ Str::limit(request('search'), 30) }}"</span>
                <a href="{{ url()->current() }}?{{ http_build_query(array_filter(request()->except('search'))) }}"
                    class="hover:text-green-900 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
            @endif

            {{-- Post Type Filter Badge --}}
            @if(request('post_type'))
            <span class="inline-flex items-center gap-2 bg-orange-100 text-orange-800 px-3 py-1.5 rounded-full text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                </svg>
                <span>পোস্ট টাইপ:
                    @if(request('post_type') == 'article') আর্টিকেল
                    @elseif(request('post_type') == 'video') ভিডিও
                    @endif
                </span>
                <a href="{{ url()->current() }}?{{ http_build_query(array_filter(request()->except('post_type'))) }}"
                    class="hover:text-orange-900 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
            @endif

            {{-- Clear All Button --}}
            <a href="{{ url()->current() }}"
                class="inline-flex items-center gap-1 text-red-600 hover:text-red-800 text-sm font-semibold transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                সব মুছুন
            </a>
        </div>
    </div>
    @endif

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Left Column: Posts Layout (9 columns) --}}
        <div class="lg:col-span-9 space-y-6">

            @if($posts->isNotEmpty())
            {{-- Featured Section: 2/3 + 1/3 Split with Gap --}}
            <div class="bg-white shadow-md overflow-hidden">
                <div class="grid md:grid-cols-3 gap-4 ">
                    {{-- Left 2/3: Main Featured Post --}}
                    @php $featuredPost = $posts->first(); @endphp
                    <article class="md:col-span-2 border-r border-gray-200">
                        {{-- Featured Image or Video --}}
                        <div class="relative overflow-hidden" style="padding-top: 66.67%;">
                            @if(request('post_type') === 'video' && $featuredPost->youtube_embed_url)
                            {{-- Show YouTube Video Embed --}}
                            <iframe
                                class="absolute inset-0 w-full h-full"
                                src="{{ $featuredPost->youtube_embed_url }}"
                                title="{{ $featuredPost->title }}"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                                loading="lazy">
                            </iframe>
                            @elseif($featuredPost->media)
                            {{-- Show Featured Image --}}
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
                                    {{ bengali_date($featuredPost->published_at, 'short_time') }}
                                </span>
                            </div>
                        </div>
                    </article>

                    {{-- Right 1/3: List of Next 2 Posts --}}
                    <div class="md:col-span-1 flex flex-col gap-4">
                        @if($posts->count() > 1)
                        @foreach($posts->skip(1)->take(2) as $index => $story)
                        <article class="flex-1 flex flex-col group bg-white">
                            {{-- Thumbnail or Video --}}
                            <div class="relative overflow-hidden" style="padding-top: 66.67%;">
                                @if(request('post_type') === 'video' && $story->youtube_embed_url)
                                <iframe
                                    class="absolute inset-0 w-full h-full"
                                    src="{{ $story->youtube_embed_url }}"
                                    title="{{ $story->title }}"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                    loading="lazy">
                                </iframe>
                                @elseif($story->media)
                                <img src="{{ $story->media->medium_url }}"
                                    alt="{{ $story->title }}"
                                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                <div class="absolute inset-0 w-full h-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400 text-sm">ছবি নেই</span>
                                </div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="p-4 flex-1">
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
                        @endif
                    </div>
                </div>
            </div>

            {{-- 3-Column News Grid (Posts 4, 5, 6) --}}
            @if($posts->count() > 3)
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($posts->skip(3)->take(3) as $story)
                <article class="bg-white shadow-md overflow-hidden group hover:shadow-lg transition-shadow">
                    {{-- Image or Video --}}
                    <div class="relative h-48 overflow-hidden">
                        @if(request('post_type') === 'video' && $story->youtube_embed_url)
                        <iframe
                            class="w-full h-full"
                            src="{{ $story->youtube_embed_url }}"
                            title="{{ $story->title }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            loading="lazy">
                        </iframe>
                        @elseif($story->media)
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
                            {{ bengali_date($story->published_at, 'short_time') }}
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
            @endif
            @else
            <div class="bg-white shadow-md p-12 text-center">
                <p class="text-gray-500 text-lg">এই বিভাগে কোন পোস্ট পাওয়া যায়নি।</p>
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

            {{-- Latest Video Widget --}}
            @if($latestVideo)
            <x-frontend.video-post-widget
                :videoPost="$latestVideo"
                :categorySlug="$category ? $category->slug : null" />
            @endif

            {{-- Latest & Popular News Tabs (Reusable Component) --}}
            @if($latestPosts && $popularPosts)
            <x-news-tabs :latestPosts="$latestPosts" :popularPosts="$popularPosts" />
            @endif


        </aside>
    </div>

    {{-- Remaining Posts Section - Grid Layout (Image 1/3, Content 2/3) --}}
    @if($posts->count() > 6)
    <div class="mt-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Left Column: Remaining Posts (9 columns) --}}
            <div class="lg:col-span-9">
                {{-- Posts List Container --}}
                <div id="posts-container" class="bg-white shadow-md divide-y divide-gray-200">
                    @foreach($posts->skip(6)->take(8) as $post)
                    <article class="grid grid-cols-3 gap-4 p-4 hover:bg-gray-50 transition-colors group post-item">
                        {{-- Image or Video - 1/3 Width --}}
                        <div class="col-span-1">
                            <div class="relative overflow-hidden rounded" style="padding-top: 66.67%;">
                                @if(request('post_type') === 'video' && $post->youtube_embed_url)
                                <iframe
                                    class="absolute inset-0 w-full h-full rounded"
                                    src="{{ $post->youtube_embed_url }}"
                                    title="{{ $post->title }}"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                    loading="lazy">
                                </iframe>
                                @elseif($post->media)
                                <img src="{{ $post->media->medium_url }}"
                                    alt="{{ $post->title }}"
                                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                <div class="absolute inset-0 w-full h-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400 text-sm">ছবি নেই</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Content - 2/3 Width --}}
                        <div class="col-span-2">
                            <h3 class="font-bold text-lg mb-2 line-clamp-2 leading-tight group-hover:text-blue-600 transition-colors">
                                <a href="{{ url('/' . $post->slug) }}">
                                    {{ $post->title }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-600 line-clamp-2 mb-3 leading-relaxed">
                                {{ Str::limit($post->excerpt, 180) }}
                            </p>
                            <div class="text-xs text-gray-500 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ bengali_date($post->published_at, 'short_time') }}
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>

                {{-- Load More Button --}}
                @if($loadMoreEndpoint && $totalPosts > 14)
                <div class="mt-6 text-center">
                    <button id="load-more-btn"
                        data-page="2"
                        data-endpoint="{{ $loadMoreEndpoint }}"
                        class="bg-red-600 hover:bg-red-700 text-white px-12 py-3 rounded font-bold transition-colors text-sm">
                        আরও পড়ুন
                    </button>
                    <div id="loading-spinner" class="hidden mt-4">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-red-600 border-t-transparent"></div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Right Sidebar (3 columns) --}}
            <aside class="lg:col-span-3">
                <div class="bg-white shadow-md p-6 text-center">
                    <p class="text-gray-500 text-lg font-semibold">শীঘ্রই আসছে</p>
                    <p class="text-gray-400 text-sm mt-2">Coming Soon</p>
                </div>
            </aside>
        </div>
    </div>
    @endif
</div>

{{-- Filter Toggle Script --}}
<script>
    function toggleFilter() {
        const filterSection = document.getElementById('filter-section');

        // Simply toggle filter visibility - breadcrumbs stay visible underneath
        if (filterSection.classList.contains('hidden')) {
            filterSection.classList.remove('hidden');
        } else {
            filterSection.classList.add('hidden');
        }
    }

    // Load More Posts Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const loadMoreBtn = document.getElementById('load-more-btn');

        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                const page = parseInt(this.getAttribute('data-page'));
                const endpoint = this.getAttribute('data-endpoint');
                const container = document.getElementById('posts-container');
                const spinner = document.getElementById('loading-spinner');

                // Show spinner, hide button
                this.classList.add('hidden');
                spinner.classList.remove('hidden');

                // Get current URL parameters to maintain sort and search filters
                const urlParams = new URLSearchParams(window.location.search);
                const sort = urlParams.get('sort') || 'latest';
                const search = urlParams.get('search') || '';
                const subcategory = urlParams.get('subcategory') || '';
                const postType = urlParams.get('post_type') || '';

                // Build API URL with all parameters
                let apiUrl = `${endpoint}?page=${page}&offset=14&sort=${sort}`;
                if (search) {
                    apiUrl += `&search=${encodeURIComponent(search)}`;
                }
                if (subcategory) {
                    apiUrl += `&subcategory=${encodeURIComponent(subcategory)}`;
                }
                if (postType) {
                    apiUrl += `&post_type=${encodeURIComponent(postType)}`;
                }

                // Fetch more posts
                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        if (data.posts && data.posts.length > 0) {
                            // Append new posts
                            data.posts.forEach(post => {
                                const postHtml = `
                                        <article class="grid grid-cols-3 gap-4 p-4 hover:bg-gray-50 transition-colors group post-item">
                                            <div class="col-span-1">
                                                <div class="relative overflow-hidden rounded" style="padding-top: 66.67%;">
                                                    ${post.media_url ? 
                                                        `<img src="${post.media_url}" alt="${post.title}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">` :
                                                        `<div class="absolute inset-0 w-full h-full bg-gray-200 flex items-center justify-center"><span class="text-gray-400 text-sm">ছবি নেই</span></div>`
                                                    }
                                                </div>
                                            </div>
                                            <div class="col-span-2">
                                                <h3 class="font-bold text-lg mb-2 line-clamp-2 leading-tight group-hover:text-blue-600 transition-colors">
                                                    <a href="/${post.slug}">${post.title}</a>
                                                </h3>
                                                <p class="text-sm text-gray-600 line-clamp-2 mb-3 leading-relaxed">
                                                    ${post.excerpt}
                                                </p>
                                                <div class="text-xs text-gray-500 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    ${post.date_bangla}
                                                </div>
                                            </div>
                                        </article>
                                    `;
                                container.insertAdjacentHTML('beforeend', postHtml);
                            });

                            // Update page number
                            loadMoreBtn.setAttribute('data-page', page + 1);

                            // Show button again
                            loadMoreBtn.classList.remove('hidden');
                            spinner.classList.add('hidden');

                            // Hide button if no more posts
                            if (!data.hasMore) {
                                loadMoreBtn.remove();
                                spinner.remove();
                            }
                        } else {
                            // No more posts
                            loadMoreBtn.remove();
                            spinner.remove();
                        }
                    })
                    .catch(error => {
                        console.error('Error loading posts:', error);
                        spinner.classList.add('hidden');
                        loadMoreBtn.classList.remove('hidden');
                        alert('পোস্ট লোড করতে সমস্যা হয়েছে। আবার চেষ্টা করুন।');
                    });
            });
        }
    });
</script>
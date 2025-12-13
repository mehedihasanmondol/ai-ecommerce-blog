{{-- Category Section Partial for Lazy Loading --}}
<div class="lg:col-span-9 space-y-6">
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
                <a href="{{ url('/' . $featuredPost->slug) }}" class="block">
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
                </a>

                {{-- Featured Content --}}
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-3 leading-tight hover:text-blue-600 transition-colors">
                        <a href="{{ url('/' . $featuredPost->slug) }}">
                            {{ $featuredPost->title }}
                        </a>
                    </h3>

                    {{-- Excerpt --}}
                    @if($featuredPost->excerpt)
                    <a href="{{ url('/' . $featuredPost->slug) }}" class="block">
                        <p class="text-gray-700 mb-4 text-sm leading-relaxed line-clamp-3 hover:text-gray-900 transition-colors">
                            {{ $featuredPost->excerpt }}
                        </p>
                    </a>
                    @endif

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
            <div class="md:col-span-1 flex flex-col gap-4 pr-4">
                @if($section['posts']->count() > 1)
                @foreach($section['posts']->skip(1)->take(2) as $post)
                <article class="flex-1 flex flex-col group bg-white">
                    {{-- Thumbnail --}}
                    <a href="{{ url('/' . $post->slug) }}" class="block">
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
                    </a>

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
                            {{ bengali_date($post->published_at, 'short_time') }}
                        </div>
                    </div>
                </article>
                @endforeach
                @endif
            </div>
            @endif
        </div>

        {{-- 3-Column News Grid (Posts 4, 5, 6,) --}}
        @if($section['posts']->count() > 3)
        <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-6 p-4 border-t border-gray-200">
            @foreach($section['posts']->skip(3)->take(4) as $post)

            <article class="bg-white overflow-hidden group ">
                <div class="grid md:grid-cols-2 grid-cols-2 gap-6 ">

                    {{-- Image --}}
                    <a href="{{ url('/' . $post->slug) }}" class="block">
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
                    </a>


                    {{-- Content --}}
                    <div class="">
                        <h4 class="font-bold text-base mb-3 line-clamp-2 leading-snug group-hover:text-blue-600 transition-colors">
                            <a href="{{ url('/' . $post->slug) }}">
                                {{ $post->title }}
                            </a>
                        </h4>
                        @if($post->excerpt)
                        <a href="{{ url('/' . $post->slug) }}" class="block">
                            <p class="text-sm text-gray-600 line-clamp-3 mb-3 leading-relaxed hover:text-gray-900 transition-colors">
                                {{ Str::limit($post->excerpt, 120) }}
                            </p>
                        </a>
                        @endif
                        <div class="text-xs text-gray-500 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ bengali_date($post->published_at, 'short_time') }}
                        </div>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        @endif

        {{-- Read More Button --}}
        <div class="p-4 border-t border-gray-200 text-center">
            <a href="{{ url('/' . $section['category']->slug) }}"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded transition-colors">
                <span>{{ $section['category']->name }} - আরও পড়ুন</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>


</div>

{{-- Right Sidebar (3 columns) --}}
<aside class="lg:col-span-3 space-y-6">



    {{-- Category Sidebar Advertisement --}}
    <x-advertisement.ad-banner slot-slug="sidebar-middle" :categoryId="$section['category']->id" />

    {{-- Latest Video Widget --}}
    @if(isset($section['latestVideo']) && $section['latestVideo'])
    <x-frontend.video-post-widget
        :videoPost="$section['latestVideo']"
        :categorySlug="$section['category']->slug" />
    @endif

</aside>
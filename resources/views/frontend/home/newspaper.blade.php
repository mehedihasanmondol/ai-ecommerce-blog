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
        {{-- Newspaper Header --}}
        <div class=" relative z-50">
            <div class="container mx-auto px-4">
                {{-- Top Header Bar --}}
                <div class="flex items-center justify-between py-3 border-b border-gray-300">
                    {{-- Bengali Date --}}
                    <div class="text-base md:text-lg font-medium text-gray-700">
                        {{ bengali_date() }}
                    </div>

                    {{-- Site Logo/Name --}}
                    <div class="flex-1 text-center">
                        @if($siteLogo = \App\Models\SiteSetting::get('site_logo'))
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('storage/' . $siteLogo) }}"
                                    alt="{{ \App\Models\SiteSetting::get('site_name', config('app.name')) }}"
                                    class="h-16 md:h-20 mx-auto object-contain">
                            </a>
                        @else
                            <h1 class="text-4xl md:text-5xl font-bold text-gray-900">
                                {{ \App\Models\SiteSetting::get('site_name', config('app.name')) }}
                            </h1>
                        @endif
                    </div>

                    {{-- Social Media Icons --}}
                    <div class="flex items-center gap-2">
                        @if($facebookUrl = \App\Models\SiteSetting::get('facebook_url'))
                            <a href="{{ $facebookUrl }}" target="_blank"
                                class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center hover:bg-blue-700 transition">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </a>
                        @endif

                        @if($youtubeUrl = \App\Models\SiteSetting::get('youtube_url'))
                            <a href="{{ $youtubeUrl }}" target="_blank"
                                class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center hover:bg-red-700 transition">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                                </svg>
                            </a>
                        @endif

                        @if($instagramUrl = \App\Models\SiteSetting::get('instagram_url'))
                            <a href="{{ $instagramUrl }}" target="_blank"
                                class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-600 via-pink-600 to-orange-500 flex items-center justify-center hover:opacity-90 transition">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                </svg>
                            </a>
                        @endif

                        @if($twitterUrl = \App\Models\SiteSetting::get('twitter_url'))
                            <a href="{{ $twitterUrl }}" target="_blank"
                                class="w-10 h-10 rounded-full bg-sky-500 flex items-center justify-center hover:bg-sky-600 transition">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                </svg>
                            </a>
                        @endif

                        @if($whatsappUrl = \App\Models\SiteSetting::get('whatsapp_url'))
                            <a href="{{ $whatsappUrl }}" target="_blank"
                                class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center hover:bg-green-700 transition">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            @php
                // Get the menu interaction mode setting
                $interactionMode = \App\Models\HomepageSetting::get('newspaper_menu_interaction_mode', 'hover');
            @endphp

            {{-- Navigation Menu - Full Width Red Background with Mega Menu --}}
            <nav class="bg-red-600 text-white relative"
                x-data="{ activeMenu: null, closeTimeout: null, interactionMode: '{{ $interactionMode }}', clickedMenu: null }"
                @click.outside="if(interactionMode === 'click') clickedMenu = null">
                <div class="container mx-auto px-4">
                    <div class="">
                        <ul class="flex items-center">
                            {{-- Home Icon (First Item) --}}
                            <li class="relative static flex-shrink-0">
                                <a href="{{ route('home') }}"
                                    class="flex items-center px-4 py-3 text-sm font-medium hover:bg-white hover:text-red-600 transition whitespace-nowrap"
                                    aria-label="Home">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                        </path>
                                    </svg>
                                </a>
                            </li>

                            {{-- সর্বশেষ (Latest) --}}
                            <li class="relative static flex-shrink-0">
                                <a href="{{ route('blog.index') }}"
                                    class="block px-4 py-3 text-sm font-medium hover:bg-white hover:text-red-600 transition whitespace-nowrap">
                                    সর্বশেষ
                                </a>
                            </li>

                            {{-- Categories with Posts (Dynamic Limit) --}}
                            @php
                                $menuCategories = \App\Modules\Blog\Models\BlogCategory::where('is_active', true)
                                    ->whereNull('parent_id')
                                    ->whereHas('posts', function ($q) {
                                        $q->where('status', 'published');
                                    })
                                    ->orderBy('sort_order')
                                    ->limit(\App\Models\HomepageSetting::get('newspaper_menu_category_limit', 8))
                                    ->get();

                                // Get the subcategory display style setting
                                $subcategoryStyle = \App\Models\HomepageSetting::get('newspaper_menu_subcategory_style', 'megamenu');
                            @endphp

                            @foreach($menuCategories as $category)
                                {{-- Category Menu Item --}}
                                <li class="relative flex-shrink-0" x-bind="interactionMode === 'hover' ? {
                                                                                                                    '@mouseenter': 'clearTimeout(closeTimeout); activeMenu = \'{{ $category->slug }}\'',
                                                                                                                    '@mouseleave': 'closeTimeout = setTimeout(() => { activeMenu = null }, 200)'
                                                                                                                } : {}">
                                    <a href="{{ route('blog.category', $category->slug) }}"
                                        class="block px-4 py-3 text-sm font-medium hover:bg-white hover:text-red-600 transition whitespace-nowrap"
                                        :class="{ 'bg-white text-red-600': interactionMode === 'click' ? clickedMenu === '{{ $category->slug }}' : activeMenu === '{{ $category->slug }}' }"
                                        @click="if(interactionMode === 'click') { $event.preventDefault(); clickedMenu = (clickedMenu === '{{ $category->slug }}' ? null : '{{ $category->slug }}'); }">
                                        {{ $category->name }}
                                    </a>

                                    {{-- Mega Menu Dropdown (Conditional based on style) --}}
                                    @php
                                        $subcategories = $category->children()->where('is_active', true)->orderBy('sort_order')->get();
                                    @endphp

                                    @if($subcategories->count() > 0)
                                        @if($subcategoryStyle === 'dropdown')
                                            {{-- Dropdown Style: Simple dropdown below menu item (NO mega menu container) --}}
                                            <div x-show="interactionMode === 'click' ? clickedMenu === '{{ $category->slug }}' : activeMenu === '{{ $category->slug }}'"
                                                x-bind="interactionMode === 'hover' ? {
                                                                                '@mouseenter'() { clearTimeout(closeTimeout); activeMenu = '{{ $category->slug }}'; },
                                                                                                                                                                        '@mouseleave'() { closeTimeout = setTimeout(() => { activeMenu = null }, 200); }
                                                                                                                                                                    } : {}"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 translate-y-1"
                                                x-transition:enter-end="opacity-100 translate-y-0"
                                                x-transition:leave="transition ease-in duration-150"
                                                x-transition:leave-start="opacity-100 translate-y-0"
                                                x-transition:leave-end="opacity-0 translate-y-1"
                                                class="absolute left-0 top-full mt-0 min-w-[200px] bg-white shadow-lg border border-gray-200 z-[100]"
                                                style="display: none;">
                                                @foreach($subcategories as $subcategory)
                                                    <a href="{{ route('blog.category', $subcategory->slug) }}"
                                                        class="block px-4 py-2.5 text-sm text-gray-800 hover:bg-gray-50 transition border-b border-gray-100 last:border-b-0">
                                                        {{ $subcategory->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            {{-- Other Styles: Use full-width mega menu container --}}
                                            <div x-show="interactionMode === 'click' ? clickedMenu === '{{ $category->slug }}' : activeMenu === '{{ $category->slug }}'"
                                                x-bind="interactionMode === 'hover' ? {
                                                                                                                                                                    '@mouseenter'() { clearTimeout(closeTimeout); activeMenu = '{{ $category->slug }}'; },
                                                                                                            '@mouseleave'() { closeTimeout = setTimeout(() => { activeMenu = null }, 200); }
                                                                                                                                                                } : {}"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 translate-y-1"
                                                x-transition:enter-end="opacity-100 translate-y-0"
                                                x-transition:leave="transition ease-in duration-150"
                                                x-transition:leave-start="opacity-100 translate-y-0"
                                                x-transition:leave-end="opacity-0 translate-y-1"
                                                class="absolute left-0 right-0 top-full -mt-1 w-full bg-white shadow-2xl rounded-lg border border-gray-200 z-[100]"
                                                style="display: none;">
                                                <div class="container mx-auto px-4">
                                                    <div class="p-8">
                                                        <div class="max-w-7xl">
                                                            {{-- Category Title --}}
                                                            <h3
                                                                class="text-lg font-bold text-gray-900 mb-4 flex items-center border-b-2 border-red-600 pb-2">
                                                                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                                </svg>
                                                                {{ $category->name }}
                                                            </h3>

                                                            @if($subcategoryStyle === 'megamenu')
                                                                {{-- Mega Menu Style: Like All Categories with subcategory links --}}
                                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                                                    @foreach($subcategories->take(12) as $subcategory)
                                                                        <div class="space-y-2">
                                                                            {{-- Subcategory Name --}}
                                                                            <a href="{{ route('blog.category', $subcategory->slug) }}"
                                                                                class="block font-bold text-gray-900 hover:text-red-600 transition flex items-center">
                                                                                <svg class="w-4 h-4 mr-1 text-red-600" fill="none"
                                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                                        stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                                                </svg>
                                                                                {{ $subcategory->name }}
                                                                            </a>

                                                                            {{-- Third Level Links --}}
                                                                            @php
                                                                                $thirdLevelCategories = $subcategory->children()->where('is_active', true)->orderBy('sort_order')->get();
                                                                            @endphp

                                                                            @if($thirdLevelCategories->isNotEmpty())
                                                                                <ul class="ml-5 space-y-1 border-l-2 border-gray-200 pl-3">
                                                                                    @foreach($thirdLevelCategories->take(5) as $thirdLevel)
                                                                                        <li>
                                                                                            <a href="{{ route('blog.category', $thirdLevel->slug) }}"
                                                                                                class="text-sm text-gray-700 hover:text-red-600 transition block">
                                                                                                {{ $thirdLevel->name }}
                                                                                            </a>
                                                                                        </li>
                                                                                    @endforeach

                                                                                    @if($thirdLevelCategories->count() > 5)
                                                                                        <li>
                                                                                            <a href="{{ route('blog.category', $subcategory->slug) }}"
                                                                                                class="text-sm text-red-600 hover:text-red-700 font-medium transition block">
                                                                                                আরো দেখুন →
                                                                                            </a>
                                                                                        </li>
                                                                                    @endif
                                                                                </ul>
                                                                            @else
                                                                                <p class="text-xs text-gray-500 italic ml-5">কোন সাবক্যাটাগরি নেই
                                                                                </p>
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                </div>

                                                            @elseif($subcategoryStyle === 'grid')
                                                                {{-- Grid Style: Subcategories with last 2 posts --}}
                                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                                    @foreach($subcategories->take(12) as $subcategory)
                                                                        @php
                                                                            $recentPosts = $subcategory->posts()
                                                                                ->where('status', 'published')
                                                                                ->orderBy('published_at', 'desc')
                                                                                ->take(2)
                                                                                ->get();
                                                                        @endphp

                                                                        <div
                                                                            class="bg-white rounded-lg border border-gray-200 hover:border-red-500 transition overflow-hidden">
                                                                            {{-- Subcategory Header --}}
                                                                            <div class="bg-red-600 px-3 py-2">
                                                                                <a href="{{ route('blog.category', $subcategory->slug) }}"
                                                                                    class="font-bold text-white hover:text-gray-100 transition flex items-center justify-between text-sm">
                                                                                    <span>{{ $subcategory->name }}</span>
                                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                                            stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                                                    </svg>
                                                                                </a>
                                                                            </div>

                                                                            {{-- Recent Posts --}}
                                                                            <div class="p-3 space-y-2">
                                                                                @if($recentPosts->isNotEmpty())
                                                                                    @foreach($recentPosts as $post)
                                                                                        <a href="{{ url('/' . $post->slug) }}" class="block group">
                                                                                            <h4
                                                                                                class="text-sm text-gray-800 group-hover:text-red-600 transition line-clamp-2 leading-snug">
                                                                                                {{ $post->title }}
                                                                                            </h4>
                                                                                            <p class="text-xs text-gray-500 mt-1 flex items-center">
                                                                                                <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                                                                    viewBox="0 0 20 20">
                                                                                                    <path fill-rule="evenodd"
                                                                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                                                                        clip-rule="evenodd"></path>
                                                                                                </svg>
                                                                                                {{ $post->published_at?->diffForHumans() }}
                                                                                            </p>
                                                                                        </a>
                                                                                        @if(!$loop->last)
                                                                                            <hr class="border-gray-200">
                                                                                        @endif
                                                                                    @endforeach
                                                                                @else
                                                                                    <p class="text-xs text-gray-500 italic text-center py-2">কোন
                                                                                        পোস্ট
                                                                                        নেই
                                                                                    </p>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>

                                                            @else
                                                                {{-- Compact Style: Tag/Badge style --}}
                                                                <div class="flex flex-wrap gap-2">
                                                                    @foreach($subcategories as $subcategory)
                                                                        <a href="{{ route('blog.category', $subcategory->slug) }}"
                                                                            class="inline-flex items-center px-4 py-2 rounded-full bg-gray-100 hover:bg-red-600 hover:text-white text-gray-700 text-sm font-medium transition-all duration-200 hover:shadow-md">
                                                                            <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                                                <path fill-rule="evenodd"
                                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z"
                                                                                    clip-rule="evenodd"></path>
                                                                            </svg>
                                                                            {{ $subcategory->name }}
                                                                        </a>
                                                                    @endforeach
                                                                </div>
                                                            @endif

                                                            {{-- View All Link --}}
                                                            <div class="mt-6 pt-4 border-t border-gray-200 text-center">
                                                                <a href="{{ route('blog.category', $category->slug) }}"
                                                                    class="inline-flex items-center text-sm font-semibold text-red-600 hover:text-red-700 transition">
                                                                    সব দেখুন
                                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </li>
                            @endforeach

                            {{-- ভিডিও গ্যালারি --}}
                            <li class="relative static flex-shrink-0">
                                <a href="#"
                                    class="block px-4 py-3 text-sm font-medium hover:bg-white hover:text-red-600 transition whitespace-nowrap">
                                    ভিডিও গ্যালারি
                                </a>
                            </li>

                            {{-- নির্বাচন --}}
                            <li class="relative static flex-shrink-0">
                                <a href="#"
                                    class="block px-4 py-3 text-sm font-medium hover:bg-white hover:text-red-600 transition whitespace-nowrap">
                                    নির্বাচন
                                </a>
                            </li>

                            {{-- খুজুন --}}
                            <li class="relative static flex-shrink-0">
                                <a href="#"
                                    class="block px-4 py-3 text-sm font-medium hover:bg-white hover:text-red-600 transition whitespace-nowrap">
                                    খুজুন
                                </a>
                            </li>

                            {{-- ই-পেপার --}}
                            <li class="relative static flex-shrink-0">
                                <a href="#"
                                    class="block px-4 py-3 text-sm font-medium hover:bg-white hover:text-red-600 transition whitespace-nowrap">
                                    ই-পেপার
                                </a>
                            </li>

                            {{-- সব বিভাগ (All Categories) with Hamburger Icon --}}
                            <li class="relative static flex-shrink-0 ml-auto" x-bind="interactionMode === 'hover' ? {
                                            '@mouseenter'() { clearTimeout(closeTimeout); activeMenu = 'all-categories'; },
                                            '@mouseleave'() { closeTimeout = setTimeout(() => { activeMenu = null }, 200); }
                                                            } : {}">
                                <a href="#"
                                    class="flex items-center gap-2 px-4 py-3 text-sm font-medium hover:bg-white hover:text-red-600 transition whitespace-nowrap"
                                    :class="{ 'bg-white text-red-600': interactionMode === 'click' ? clickedMenu === 'all-categories' : activeMenu === 'all-categories' }"
                                    @click="if(interactionMode === 'click') { $event.preventDefault(); clickedMenu = (clickedMenu === 'all-categories' ? null : 'all-categories'); }"
                                    aria-label="All Categories">
                                    {{-- Hamburger Icon --}}
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M4 6h16M4 12h16M4 18h16"></path>
                                    </svg>
                                </a>
                            </li>

                            {{-- All Categories Mega Menu Dropdown --}}
                            @php
                                $allCategories = \App\Modules\Blog\Models\BlogCategory::where('is_active', true)
                                    ->whereNull('parent_id')
                                    ->orderBy('sort_order')
                                    ->get();
                            @endphp

                            <div x-show="interactionMode === 'click' ? clickedMenu === 'all-categories' : activeMenu === 'all-categories'"
                                x-bind="interactionMode === 'hover' ? {
                                                            '@mouseenter'() { clearTimeout(closeTimeout); activeMenu = 'all-categories'; },
                                                            '@mouseleave'() { closeTimeout = setTimeout(() => { activeMenu = null }, 200); }
                                                        } : {}" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-1"
                                class="absolute left-0 right-0 top-full -mt-1 w-full bg-white shadow-2xl rounded-lg border border-gray-200 z-[100]"
                                style="display: none;">
                                <div class="container mx-auto px-4">
                                    <div class="p-8">
                                        <div class="max-w-7xl">
                                            {{-- Title --}}
                                            <h3
                                                class="text-lg font-bold text-gray-900 mb-4 border-b-2 border-red-600 pb-2 flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M4 6h16M4 12h16M4 18h16"></path>
                                                </svg>
                                                সব বিভাগ
                                            </h3>

                                            {{-- All Categories Grid with Subcategories --}}
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                                @foreach($allCategories as $cat)
                                                    <div class="space-y-2">
                                                        {{-- Category Name --}}
                                                        <a href="{{ route('blog.category', $cat->slug) }}"
                                                            class="block font-bold text-gray-900 hover:text-red-600 transition flex items-center">
                                                            <svg class="w-4 h-4 mr-1 text-red-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                            </svg>
                                                            {{ $cat->name }}
                                                        </a>

                                                        {{-- Subcategory Links --}}
                                                        @php
                                                            $subcategories = $cat->children()->where('is_active', true)->orderBy('sort_order')->get();
                                                        @endphp

                                                        @if($subcategories->isNotEmpty())
                                                            <ul class="ml-5 space-y-1 border-l-2 border-gray-200 pl-3">
                                                                @foreach($subcategories->take(5) as $subcat)
                                                                    <li>
                                                                        <a href="{{ route('blog.category', $subcat->slug) }}"
                                                                            class="text-sm text-gray-700 hover:text-red-600 transition block">
                                                                            {{ $subcat->name }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach

                                                                @if($subcategories->count() > 5)
                                                                    <li>
                                                                        <a href="{{ route('blog.category', $cat->slug) }}"
                                                                            class="text-sm text-red-600 hover:text-red-700 font-medium transition block">
                                                                            আরো দেখুন →
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        @else
                                                            <p class="text-xs text-gray-500 italic ml-5">কোন সাবক্যাটাগরি নেই</p>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    </div>
    </div>
    </nav>
    </div>

    <div class="container mx-auto px-4 py-8">
        {{-- Blog Posts Content --}}
        <div class="text-center py-20">
            <p class="text-gray-600">Blog content will appear here when blog posts are published.</p>
        </div>
    </div>
    </div>
@endsection
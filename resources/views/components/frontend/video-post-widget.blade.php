@props([
'videoPost' => null,
'categorySlug' => null,
'showMoreLink' => true
])

@if($videoPost)
<div class="bg-white shadow-md overflow-hidden">
    {{-- Widget Header --}}
    <div class="px-4 py-3 bg-red-600 text-white">
        <h3 class="font-bold text-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M10 16.5l6-4.5-6-4.5v9zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" />
            </svg>
            সর্বশেষ ভিডিও
        </h3>
    </div>

    {{-- Video Player --}}
    <div class="relative" style="padding-top: 56.25%;">
        @if($videoPost->youtube_embed_url)
        <iframe
            class="absolute inset-0 w-full h-full"
            src="{{ $videoPost->youtube_embed_url }}"
            title="{{ $videoPost->title }}"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
            loading="lazy">
        </iframe>
        @else
        <div class="absolute inset-0 w-full h-full bg-gray-200 flex items-center justify-center">
            <span class="text-gray-400">ভিডিও লোড করা যায়নি</span>
        </div>
        @endif
    </div>

    {{-- Video Info --}}
    <div class="p-4">
        <h4 class="font-bold text-base mb-2 line-clamp-2 leading-snug hover:text-red-600 transition-colors">
            <a href="{{ url('/' . $videoPost->slug) }}">
                {{ $videoPost->title }}
            </a>
        </h4>

        <div class="text-xs text-gray-500 flex items-center gap-1 mb-3">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ bengali_date($videoPost->published_at, 'short') }}
        </div>

        @if($showMoreLink)
        {{-- See More Videos Link --}}
        <a href="{{ $categorySlug ? url('/' . $categorySlug . '?post_type=video') : url('/blog/latest-news?post_type=video') }}"
            class="inline-flex items-center gap-1 text-sm font-semibold text-red-600 hover:text-red-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            আরও ভিডিও দেখুন
        </a>
        @endif
    </div>
</div>
@endif
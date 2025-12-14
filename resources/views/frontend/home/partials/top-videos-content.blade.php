{{-- Top Videos Content (loaded via AJAX) --}}
@if($topVideosEnabled == '1' && !empty($topVideosByCategory))
<div class="lg:col-span-12">
    <div class="bg-gray-200 shadow-md p-6" x-data="{ activeTab: '{{ array_key_first($topVideosByCategory) }}' }">
        {{-- Section Header with Title and Tabs --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 border-b border-white pb-4">
            {{-- Section Title --}}
            <h2 class="text-2xl font-bold text-gray-900 mb-4 md:mb-0">
                {{ $topVideosTitle }}
            </h2>

            {{-- Category Tabs --}}
            <div class="flex flex-wrap gap-2">
                @foreach($topVideosByCategory as $categoryId => $categoryData)
                <button
                    @click="activeTab = '{{ $categoryId }}'"
                    :class="activeTab === '{{ $categoryId }}' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                    class="px-4 py-2 text-sm font-medium transition-colors duration-200">
                    {{ $categoryData['category']->name }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- Video Content for Each Category --}}
        @foreach($topVideosByCategory as $categoryId => $categoryData)
        <div x-show="activeTab === '{{ $categoryId }}'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100">

            {{-- 4-Column Video Grid --}}
            <div class="grid md:grid-cols-4 gap-6 mb-6">
                @foreach($categoryData['videos']->take(8) as $video)
                <article class="bg-white shadow-md overflow-hidden group hover:shadow-lg transition-shadow">
                    {{-- Video Embed or Thumbnail --}}
                    <a href="{{ url('/' . $video->slug) }}" class="block">
                        <div class="relative h-48 overflow-hidden">
                            @if($video->youtube_embed_url)
                            {{-- Show YouTube Video Embed --}}
                            <iframe
                                class="w-full h-full pointer-events-none"
                                src="{{ $video->youtube_embed_url }}"
                                title="{{ $video->title }}"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                                loading="lazy">
                            </iframe>
                            @elseif($video->media)
                            {{-- Show Featured Image --}}
                            <img src="{{ $video->media->medium_url }}"
                                alt="{{ $video->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                            {{-- No Image Placeholder --}}
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400 text-sm">ছবি নেই</span>
                            </div>
                            @endif
                        </div>
                    </a>

                    {{-- Content --}}
                    <div class="p-4">
                        <h3 class="font-bold text-base mb-3 line-clamp-2 leading-snug group-hover:text-blue-600 transition-colors">
                            <a href="{{ url('/' . $video->slug) }}">
                                {{ $video->title }}
                            </a>
                        </h3>
                        <a href="{{ url('/' . $video->slug) }}" class="block">
                            <p class="text-sm text-gray-600 line-clamp-3 mb-3 leading-relaxed hover:text-gray-900 transition-colors">
                                {{ Str::limit($video->excerpt, 120) }}
                            </p>
                        </a>
                        <div class="text-xs text-gray-500 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ bengali_date($video->published_at, 'short_time') }}
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            {{-- View All Button --}}
            <div class="text-center border-t border-gray-200 pt-4">
                <a href="{{ url('/' . $categoryData['category']->slug) }}"
                    class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-md font-medium transition-colors duration-200">
                    <span>সকল ভিডিও দেখুন</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
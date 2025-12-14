<div x-data="{ focused: false }">
    {{-- Search Input --}}
    <div class="relative">
        <input type="text" wire:model.live.debounce.300ms="search"
            @focus="focused = true"
            @blur="setTimeout(() => focused = false, 200)"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Search video posts...">

        <div x-show="focused"
            x-transition class="absolute z-10 w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-lg max-h-96 overflow-y-auto">
            @if($posts->isEmpty())
            <div class="px-4 py-8 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                    </path>
                </svg>
                <p>No video posts found</p>
                <p class="text-sm text-gray-400 mt-1">Try searching for posts with YouTube videos</p>
            </div>
            @else
            @foreach($posts as $post)
            <div class="border-b border-gray-100 last:border-b-0">
                <div class="px-4 py-3 hover:bg-gray-50">
                    <div class="flex items-start space-x-3">
                        {{-- Video Thumbnail --}}
                        @if($post['youtube_embed_url'])
                        <div class="flex-shrink-0 w-24 h-16 bg-gray-200 rounded overflow-hidden">
                            <img src="https://img.youtube.com/vi/{{ basename($post['youtube_embed_url']) }}/default.jpg"
                                alt="{{ $post['title'] }}" class="w-full h-full object-cover"
                                onerror="this.onerror=null; this.src='{{ $post['featured_image'] ? asset('storage/' . $post['featured_image']) : '' }}';">
                        </div>
                        @elseif($post['featured_image'])
                        <div class="flex-shrink-0 w-24 h-16 bg-gray-200 rounded overflow-hidden">
                            <img src="{{ asset('storage/' . $post['featured_image']) }}"
                                alt="{{ $post['title'] }}" class="w-full h-full object-cover">
                        </div>
                        @else
                        <div class="flex-shrink-0 w-24 h-16 bg-gray-200 rounded flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        @endif

                        {{-- Post Info --}}
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 truncate">{{ $post['title'] }}</h4>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="text-xs text-gray-500">{{ $post['category'] }}</span>
                                @if($post['published_at'])
                                <span class="text-xs text-gray-400">• {{ $post['published_at'] }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Add Button --}}
                        <div class="flex-shrink-0">
                            @if($post['exists'])
                            <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                Already Added
                            </span>
                            @else
                            <button wire:click="addPost({{ $post['id'] }})" type="button"
                                class="px-3 py-1 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition">
                                Add
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>

    {{-- Help Text --}}
    <p class="mt-2 text-sm text-gray-600">
        @if(strlen($search) > 0)
        Searching for "{{ $search }}"... Showing latest video posts not in top videos.
        @else
        Showing 10 latest video posts. Type to search by title.
        @endif
    </p>
</div>
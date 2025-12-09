<div class="relative w-full" x-data="{ 
    focused: false,
    showResults: @entangle('showResults'),
    query: @entangle('query'),
    maintainFocus: false
}" x-on:click.away="if (!maintainFocus) $wire.hideResults()" @maintain-focus.window="
    maintainFocus = true;
    $nextTick(() => {
        $refs.searchInput.focus();
        focused = true;
        setTimeout(() => { maintainFocus = false; }, 300);
    });
">

    {{-- Search Input Container --}}
    <div class="relative">
        {{-- Main Search Input --}}
        <div class="relative">
            <input type="text" wire:model.live.debounce.300ms="query" x-ref="searchInput" x-on:focus="focused = true"
                x-on:blur="setTimeout(() => { if (!maintainFocus) focused = false; }, 200)"
                placeholder="পোস্ট, ক্যাটাগরি বা ট্যাগ খুঁজুন..."
                class="w-full px-4 py-2.5 pr-24 text-gray-900 bg-white border border-red-400 focus:outline-none focus:ring-1 focus:ring-red-300 focus:border-red-300 placeholder-gray-400"
                autocomplete="off" @keydown.escape="$wire.hideResults()">

            {{-- Clear Button --}}
            <button x-show="query.length > 0" wire:click="clearSearch"
                class="absolute right-14 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-75"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-75">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            {{-- Search Button --}}
            <button wire:click="search"
                class="absolute right-1 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-red-600 text-white hover:bg-red-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </div>

        {{-- Search Results Dropdown (Two-Panel Layout) --}}
        <div x-show="query.length >= 1 && (focused || maintainFocus)"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="absolute top-full left-0 right-0 mt-2 bg-white shadow-2xl border border-gray-200 z-50 overflow-hidden"
            style="display: none;">

            {{-- Close Button --}}
            <div class="absolute top-2 right-2 z-10">
                <button @click="focused = false; $wire.hideResults()"
                    class="flex items-center justify-center w-8 h-8 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            {{-- Two-Panel Layout --}}
            <div class="flex">
                {{-- Left Panel: Search Suggestions (1/3 width) --}}
                <div class="w-1/3 bg-gray-50 border-r border-gray-200">
                    <div class="p-3 border-b border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-900">Search Suggestions</h4>
                    </div>

                    {{-- Loading State --}}
                    <div wire:loading wire:target="query" class="p-4 text-center">
                        <div class="inline-flex items-center text-gray-600">
                            <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span class="text-sm">খুঁজছি...</span>
                        </div>
                    </div>

                    <div wire:loading.remove wire:target="query" class="max-h-80 overflow-y-auto">
                        {{-- Auto-complete Suggestions --}}
                        @if($searchSuggestions->count() > 0)
                            @foreach($searchSuggestions as $suggestion)
                                <button wire:click="updateQuery('{{ $suggestion }}')"
                                    class="w-full px-4 py-2 text-left hover:bg-gray-100 transition-colors flex items-center text-sm">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <span class="text-gray-900">{{ $suggestion }}</span>
                                </button>
                            @endforeach
                        @endif

                        {{-- Popular Searches (when no suggestions) --}}
                        @if($searchSuggestions->count() === 0 && strlen($query) < 2)
                            <div class="p-3 border-b border-gray-200 bg-white">
                                <h5 class="text-xs font-medium text-gray-700 mb-2">জনপ্রিয় বিভাগ</h5>
                            </div>
                            @foreach(array_slice($popularSearches, 0, 6) as $popular)
                                <button wire:click="updateQuery('{{ $popular }}')"
                                    class="w-full px-4 py-2 text-left hover:bg-gray-100 transition-colors flex items-center text-sm">
                                    <svg class="w-4 h-4 mr-3 text-orange-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    <span class="text-gray-900">{{ $popular }}</span>
                                </button>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Right Panel: Results (2/3 width) --}}
                <div class="flex-1">
                    <div wire:loading.remove wire:target="query">
                        {{-- When there's a query --}}
                        <div x-show="query.length >= 2">
                            {{-- Results Header --}}
                            <div class="flex border-b border-gray-200 bg-gray-50">
                                <div
                                    class="px-4 py-3 text-sm font-medium text-red-600 border-b-2 border-red-500 bg-white">
                                    All Results
                                    ({{ $postSuggestions->count() + $categorySuggestions->count() + $tagSuggestions->count() }})
                                </div>
                            </div>

                            {{-- Results Content --}}
                            <div class="max-h-96 overflow-y-auto">
                                {{-- Blog Posts Section --}}
                                @if($postSuggestions->count() > 0)
                                    <div class="border-b border-gray-100">
                                        <div class="px-4 py-3 bg-gray-50 flex items-center justify-between">
                                            <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                                    </path>
                                                </svg>
                                                Blog Posts
                                            </h3>
                                        </div>
                                        <div class="py-2">
                                            @foreach($postSuggestions as $post)
                                                <a href="{{ route('products.show', $post->slug) }}"
                                                    class="block w-full px-4 py-3 hover:bg-gray-50 transition-colors text-left flex items-center space-x-3">

                                                    {{-- Post Thumbnail --}}
                                                    <div class="w-16 h-16 flex-shrink-0 overflow-hidden bg-gray-100">
                                                        @if($post->media)
                                                            <img src="{{ $post->media->small_url }}" alt="{{ $post->title }}"
                                                                class="w-full h-full object-cover">
                                                        @else
                                                            <div
                                                                class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    {{-- Post Info --}}
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="text-sm font-medium text-gray-900 line-clamp-2">
                                                            {{ $post->title }}</h4>
                                                        <div class="flex items-center space-x-2 mt-1">
                                                            <span
                                                                class="text-xs text-gray-500">{{ $post->published_at?->format('M d, Y') ?? $post->created_at->format('M d, Y') }}</span>
                                                            @if($post->category)
                                                                <span
                                                                    class="text-xs text-red-600">{{ $post->category->name }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Categories & Tags --}}
                                @if($categorySuggestions->count() > 0 || $tagSuggestions->count() > 0)
                                    <div class="px-4 py-3">
                                        <div class="grid grid-cols-2 gap-4">
                                            {{-- Categories --}}
                                            @if($categorySuggestions->count() > 0)
                                                <div>
                                                    <h4 class="text-xs font-medium text-gray-700 mb-2">ক্যাটাগরি</h4>
                                                    @foreach($categorySuggestions as $category)
                                                        <a href="{{ route('blog.category', $category->slug) }}"
                                                            class="block w-full text-left px-2 py-1 rounded hover:bg-gray-100 transition-colors">
                                                            <span class="text-sm text-gray-900">{{ $category->name }}</span>
                                                            <span
                                                                class="text-xs text-gray-500 ml-1">({{ $category->posts_count }})</span>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif

                                            {{-- Tags --}}
                                            @if($tagSuggestions->count() > 0)
                                                <div>
                                                    <h4 class="text-xs font-medium text-gray-700 mb-2">ট্যাগ</h4>
                                                    @foreach($tagSuggestions as $tag)
                                                        <a href="{{ route('blog.tag', $tag->slug) }}"
                                                            class="block w-full text-left px-2 py-1 rounded hover:bg-gray-100 transition-colors">
                                                            <span class="text-sm text-gray-900">{{ $tag->name }}</span>
                                                            <span
                                                                class="text-xs text-gray-500 ml-1">({{ $tag->posts_count }})</span>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- No Results --}}
                                @if($postSuggestions->count() === 0 && $categorySuggestions->count() === 0 && $tagSuggestions->count() === 0)
                                    <div class="px-6 py-8 text-center">
                                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">কোনো ফলাফল পাওয়া যায়নি</h3>
                                        <p class="text-sm text-gray-600 mb-4">ভিন্ন কীওয়ার্ড দিয়ে খুঁজে দেখুন</p>
                                        <button wire:click="search"
                                            class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 transition-colors">
                                            সব পোস্ট দেখুন
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
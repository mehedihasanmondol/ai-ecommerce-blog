@props(['latestPosts' => collect(), 'popularPosts' => collect()])

{{-- Latest & Popular News Tabs Component --}}
@if($latestPosts->isNotEmpty() || $popularPosts->isNotEmpty())
    <div class="bg-white shadow-md overflow-hidden">
        {{-- Tab Headers --}}
        <div class="flex border-b border-gray-200">
            <button onclick="switchNewsTab('latest')" 
                id="news-tab-latest"
                class="news-tab flex-1 px-4 py-3 text-sm font-bold transition-colors border-b-2 border-green-600 bg-green-50 text-green-700">
                সর্বশেষ সংবাদ
            </button>
            <button onclick="switchNewsTab('popular')" 
                id="news-tab-popular"
                class="news-tab flex-1 px-4 py-3 text-sm font-bold transition-colors border-b-2 border-transparent hover:bg-gray-50 text-gray-600">
                জনপ্রিয় সংবাদ
            </button>
        </div>

        {{-- Tab Content Container with Fixed Height and Scroll --}}
        <div class="relative">
            {{-- Latest News Tab --}}
            <div id="news-content-latest" class="news-tab-content overflow-y-auto" style="max-height: 400px;">
                <div class="p-4 space-y-4">
                    @foreach($latestPosts as $index => $post)
                        <article class="group flex gap-3">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center w-8 h-8 bg-green-100 text-green-700 font-bold rounded text-base">
                                    {{ bengali_number($index + 1) }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-sm mb-1 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                    <a href="{{ url('/' . $post->slug) }}">
                                        {{ $post->title }}
                                    </a>
                                </h4>
                                <div class="text-xs text-gray-500">
                                    {{ $post->published_at?->diffForHumans() }}
                                </div>
                            </div>
                        </article>
                        @if(!$loop->last)
                            <hr class="border-gray-200">
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Popular News Tab --}}
            <div id="news-content-popular" class="news-tab-content overflow-y-auto hidden" style="max-height: 400px;">
                <div class="p-4 space-y-4">
                    @foreach($popularPosts as $index => $post)
                        <article class="flex gap-3 group">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center w-8 h-8 bg-orange-100 text-orange-600 font-bold rounded text-base">
                                    {{ bengali_number($index + 1) }}
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
        </div>
    </div>

    {{-- Tab Switching Script --}}
    @once
    <script>
        function switchNewsTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.news-tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active styles from all tabs
            document.querySelectorAll('.news-tab').forEach(tab => {
                tab.classList.remove('border-green-600', 'bg-green-50', 'text-green-700', 'border-orange-600', 'bg-orange-50', 'text-orange-600');
                tab.classList.add('border-transparent', 'text-gray-600');
            });

            // Show selected tab content
            document.getElementById('news-content-' + tabName).classList.remove('hidden');

            // Add active styles to selected tab
            const activeTab = document.getElementById('news-tab-' + tabName);
            activeTab.classList.remove('border-transparent', 'text-gray-600');
            
            if (tabName === 'latest') {
                activeTab.classList.add('border-green-600', 'bg-green-50', 'text-green-700');
            } else if (tabName === 'popular') {
                activeTab.classList.add('border-orange-600', 'bg-orange-50', 'text-orange-600');
            }
        }
    </script>
    @endonce
@endif

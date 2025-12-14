{{-- Top Videos Section with Lazy Loading --}}
@if($topVideosEnabled == '1')
<div class="container mx-auto px-4 py-8">
    <div class="lazy-top-videos-section" data-loaded="false">
        {{-- Loading Skeleton --}}
        <div class="bg-gray-200 shadow-md p-6">
            <div class="animate-pulse">
                {{-- Header Skeleton --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 border-b border-gray-300 pb-4">
                    <div class="h-8 bg-gray-300 rounded w-1/4 mb-4 md:mb-0"></div>
                    <div class="flex gap-2">
                        <div class="h-10 w-24 bg-gray-300 rounded"></div>
                        <div class="h-10 w-24 bg-gray-300 rounded"></div>
                        <div class="h-10 w-24 bg-gray-300 rounded"></div>
                    </div>
                </div>

                {{-- Video Grid Skeleton --}}
                <div class="grid md:grid-cols-4 gap-6 mb-6">
                    @for($i = 0; $i < 8; $i++)
                        <div class="bg-white shadow-md overflow-hidden">
                        {{-- Video Thumbnail Skeleton --}}
                        <div class="h-48 bg-gray-300"></div>

                        {{-- Content Skeleton --}}
                        <div class="p-4">
                            <div class="h-4 bg-gray-300 rounded w-full mb-2"></div>
                            <div class="h-4 bg-gray-300 rounded w-4/5 mb-3"></div>
                            <div class="h-3 bg-gray-300 rounded w-3/4 mb-2"></div>
                            <div class="h-3 bg-gray-300 rounded w-2/3 mb-3"></div>
                            <div class="h-3 bg-gray-300 rounded w-1/3"></div>
                        </div>
                </div>
                @endfor
            </div>

            {{-- Button Skeleton --}}
            <div class="text-center border-t border-gray-300 pt-4">
                <div class="h-12 w-48 bg-gray-300 rounded-md mx-auto"></div>
            </div>
        </div>
    </div>
</div>
</div>
@endif
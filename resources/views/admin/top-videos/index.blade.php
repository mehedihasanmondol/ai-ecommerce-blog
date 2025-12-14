@extends('layouts.admin')

@section('title', 'শীর্ষ ভিডিও Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Section Settings --}}
    @if(auth()->user()->hasPermission('top-videos.edit'))
    <x-admin.section-settings :sectionEnabled="$sectionEnabled" :sectionTitle="$sectionTitle"
        toggleRoute="{{ route('admin.top-videos.toggle-section') }}"
        updateTitleRoute="{{ route('admin.top-videos.update-title') }}" sectionName="Top Videos" />
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">শীর্ষ ভিডিও Management</h1>
            <p class="text-gray-600 mt-1">Manage video posts displayed in top videos section on newspaper homepage</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Add Video Post Search --}}
        @if(auth()->user()->hasPermission('top-videos.create'))
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Add Video to Top Videos</h2>

                {{-- Livewire Post Selector --}}
                @livewire('admin.top-video-post-selector')

                <div class="mt-6 p-4 bg-blue-50 rounded-md">
                    <h3 class="text-sm font-semibold text-blue-900 mb-2">Tips:</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Search by video post title</li>
                        <li>• Only posts with YouTube videos appear</li>
                        <li>• Drag and drop to reorder videos</li>
                        <li>• Toggle status to show/hide videos</li>
                    </ul>
                </div>
            </div>
        </div>
        @endif

        {{-- Top Videos List --}}
        <div class="{{ auth()->user()->hasPermission('top-videos.create') ? 'lg:col-span-2' : 'lg:col-span-3' }}">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    Current Top Videos ({{ $topVideos->count() }})
                </h2>

                @if($topVideos->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                        </path>
                    </svg>
                    <p class="text-gray-600">No top videos added yet. Add your first video above.</p>
                </div>
                @else
                <div id="top-videos-list" class="space-y-3">
                    @foreach($topVideos as $video)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:shadow-md transition cursor-move"
                        data-id="{{ $video->id }}">
                        <div class="flex items-center space-x-4 flex-1">
                            {{-- Drag Handle --}}
                            <div class="text-gray-400 hover:text-gray-600 cursor-move">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 8h16M4 16h16"></path>
                                </svg>
                            </div>

                            @if($video->post)
                            {{-- Video Thumbnail --}}
                            @if($video->post->youtube_embed_url)
                            <img src="https://img.youtube.com/vi/{{ basename($video->post->youtube_embed_url) }}/default.jpg"
                                alt="{{ $video->post->title }}"
                                class="w-24 h-16 object-cover rounded-md">
                            @elseif($video->post->featured_image)
                            <img src="{{ asset('storage/' . $video->post->featured_image) }}"
                                alt="{{ $video->post->title }}"
                                class="w-24 h-16 object-cover rounded-md">
                            @else
                            <div class="w-24 h-16 bg-gray-200 rounded-md flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            @endif

                            {{-- Post Info --}}
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $video->post->title }}</h3>
                                <div class="flex items-center space-x-3 mt-1">
                                    <span class="text-sm text-gray-600">Order: {{ $video->display_order }}</span>
                                    @if($video->post->categories->first())
                                    <span class="text-sm text-gray-500">{{ $video->post->categories->first()->name }}</span>
                                    @endif
                                    @if($video->post->published_at)
                                    <span class="text-sm text-gray-400">{{ $video->post->published_at->format('M d, Y') }}</span>
                                    @endif
                                </div>
                            </div>
                            @else
                            {{-- Post Deleted/Missing --}}
                            <div class="w-24 h-16 bg-red-100 rounded-md flex items-center justify-center">
                                <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-red-600">Post Deleted</h3>
                                <p class="text-sm text-gray-600">This post no longer exists</p>
                            </div>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center space-x-2">
                            @if(auth()->user()->hasPermission('top-videos.edit'))
                            {{-- Toggle Status --}}
                            <form action="{{ route('admin.top-videos.toggle-status', $video) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="px-3 py-1 rounded-md text-sm font-medium transition {{ $video->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    {{ $video->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                            @endif

                            @if(auth()->user()->hasPermission('top-videos.delete'))
                            {{-- Delete --}}
                            <form action="{{ route('admin.top-videos.destroy', $video) }}" method="POST" class="inline"
                                id="delete-form-{{ $video->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" @click="$dispatch('confirm-modal', {
                                                                    title: 'Remove Top Video',
                                                                    message: 'Are you sure you want to remove {{ $video->post ? addslashes($video->post->title) : 'this item' }} from top videos?',
                                                                    onConfirm: () => document.getElementById('delete-form-{{ $video->id }}').submit()
                                                                })" class="p-2 text-red-600 hover:bg-red-50 rounded-md transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                            @endif

                            @if(!auth()->user()->hasPermission('top-videos.edit') && !auth()->user()->hasPermission('top-videos.delete'))
                            <span class="text-xs text-gray-400">Read Only</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const list = document.getElementById('top-videos-list');

        if (list) {
            new Sortable(list, {
                animation: 150,
                handle: '.cursor-move',
                onEnd: function(evt) {
                    // Get all items in new order
                    const items = list.querySelectorAll('[data-id]');
                    const orders = Array.from(items).map((item, index) => ({
                        id: item.dataset.id,
                        display_order: index + 1
                    }));

                    // Send to server
                    fetch('{{ route("admin.top-videos.reorder") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                orders: orders
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update order numbers in UI
                                items.forEach((item, index) => {
                                    const orderSpan = item.querySelector('.text-sm.text-gray-600');
                                    if (orderSpan) {
                                        orderSpan.textContent = 'Order: ' + (index + 1);
                                    }
                                });
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });
        }
    });
</script>
@endpush
@endsection
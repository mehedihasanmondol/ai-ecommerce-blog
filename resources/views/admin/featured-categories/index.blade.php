@extends('layouts.admin')

@section('title', 'প্রধান খবর Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Section Settings --}}
    @if(auth()->user()->hasPermission('featured-categories.edit'))
    <x-admin.section-settings :sectionEnabled="$sectionEnabled" :sectionTitle="$sectionTitle"
        toggleRoute="{{ route('admin.featured-categories.toggle-section') }}"
        updateTitleRoute="{{ route('admin.featured-categories.update-title') }}" sectionName="Featured Categories" />
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">প্রধান খবর Management</h1>
            <p class="text-gray-600 mt-1">Manage blog categories displayed in featured section on newspaper homepage</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Add Category Form --}}
        @if(auth()->user()->hasPermission('featured-categories.create'))
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Add Category to Featured List</h2>

                <form action="{{ route('admin.featured-categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="blog_category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Select Category
                        </label>
                        <select name="blog_category_id" id="blog_category_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Select a category --</option>
                            @foreach(\App\Modules\Blog\Models\BlogCategory::where('is_active', true)
                            ->withCount(['posts' => function($q) { $q->where('status', 'published'); }])
                            ->having('posts_count', '>', 0)
                            ->whereNotIn('id', $featuredCategories->pluck('blog_category_id'))
                            ->orderBy('name')
                            ->get() as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }} ({{ $category->posts_count }} posts)
                            </option>
                            @endforeach
                        </select>
                        @error('blog_category_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                        Add to Featured List
                    </button>
                </form>

                <div class="mt-6 p-4 bg-blue-50 rounded-md">
                    <h3 class="text-sm font-semibold text-blue-900 mb-2">Tips:</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Select categories with published posts</li>
                        <li>• Drag and drop to reorder categories</li>
                        <li>• Toggle status to show/hide categories</li>
                        <li>• Each category shows 4 recent posts</li>
                    </ul>
                </div>
            </div>
        </div>
        @endif

        {{-- Featured Categories List --}}
        <div class="{{ auth()->user()->hasPermission('featured-categories.create') ? 'lg:col-span-2' : 'lg:col-span-3' }}">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    Current Featured Categories ({{ $featuredCategories->count() }})
                </h2>

                @if($featuredCategories->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                    <p class="text-gray-600">No featured categories added yet. Add your first category above.</p>
                </div>
                @else
                <div id="featured-categories-list" class="space-y-3">
                    @foreach($featuredCategories as $featured)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:shadow-md transition cursor-move"
                        data-id="{{ $featured->id }}">
                        <div class="flex items-center space-x-4 flex-1">
                            {{-- Drag Handle --}}
                            <div class="text-gray-400 hover:text-gray-600 cursor-move">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 8h16M4 16h16"></path>
                                </svg>
                            </div>

                            @if($featured->category)
                            {{-- Category Image --}}
                            @if($featured->category->getImageUrl())
                            <img src="{{ $featured->category->getImageUrl() }}" alt="{{ $featured->category->name }}"
                                class="w-16 h-16 object-cover rounded-md">
                            @else
                            <div class="w-16 h-16 bg-gray-200 rounded-md flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                    </path>
                                </svg>
                            </div>
                            @endif

                            {{-- Category Info --}}
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $featured->category->name }}</h3>
                                <div class="flex items-center space-x-3 mt-1">
                                    <span class="text-sm text-gray-600">Order: {{ $featured->display_order }}</span>
                                    <span class="text-sm text-gray-500">{{ $featured->category->posts->where('status', 'published')->count() }} published posts</span>
                                </div>
                            </div>
                            @else
                            {{-- Category Deleted/Missing --}}
                            <div class="w-16 h-16 bg-red-100 rounded-md flex items-center justify-center">
                                <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-red-600">Category Deleted</h3>
                                <p class="text-sm text-gray-600">This category no longer exists</p>
                            </div>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center space-x-2">
                            @if(auth()->user()->hasPermission('featured-categories.edit'))
                            {{-- Toggle Status --}}
                            <form action="{{ route('admin.featured-categories.toggle-status', $featured) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="px-3 py-1 rounded-md text-sm font-medium transition {{ $featured->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    {{ $featured->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                            @endif

                            @if(auth()->user()->hasPermission('featured-categories.delete'))
                            {{-- Delete --}}
                            <form action="{{ route('admin.featured-categories.destroy', $featured) }}" method="POST" class="inline"
                                id="delete-form-{{ $featured->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" @click="$dispatch('confirm-modal', {
                                                                    title: 'Remove Featured Category',
                                                                    message: 'Are you sure you want to remove {{ $featured->category ? addslashes($featured->category->name) : 'this category' }} from featured list?',
                                                                    onConfirm: () => document.getElementById('delete-form-{{ $featured->id }}').submit()
                                                                })" class="p-2 text-red-600 hover:bg-red-50 rounded-md transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                            @endif

                            @if(!auth()->user()->hasPermission('featured-categories.edit') && !auth()->user()->hasPermission('featured-categories.delete'))
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
        const list = document.getElementById('featured-categories-list');

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
                    fetch('{{ route("admin.featured-categories.reorder") }}', {
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
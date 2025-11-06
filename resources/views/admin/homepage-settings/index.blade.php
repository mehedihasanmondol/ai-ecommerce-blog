@extends('layouts.admin')

@section('title', 'Homepage Settings')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Homepage Settings</h1>
                <p class="text-gray-600 mt-2">Manage your homepage content, hero sliders, and promotional banners</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Preview Homepage
                </a>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="mb-8" x-data="{ activeTab: 'sliders' }">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <nav class="flex space-x-1 p-1">
                <button @click="activeTab = 'sliders'" 
                        :class="activeTab === 'sliders' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
                        class="flex-1 py-3 px-4 rounded-lg font-medium text-sm transition-all duration-200 ease-in-out">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Hero Sliders</span>
                    </div>
                </button>
                <button @click="activeTab = 'general'" 
                        :class="activeTab === 'general' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
                        class="flex-1 py-3 px-4 rounded-lg font-medium text-sm transition-all duration-200 ease-in-out">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>General Settings</span>
                    </div>
                </button>
            </nav>
        </div>

        <!-- Hero Sliders Tab -->
        <div x-show="activeTab === 'sliders'" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <!-- Section Header -->
                <div class="px-6 py-5 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Hero Sliders</h2>
                            <p class="text-sm text-gray-600 mt-1">Create and manage homepage banner sliders</p>
                        </div>
                        <button @click="$dispatch('open-dialog-add-slider')" 
                                class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 ease-in-out hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add New Slider
                        </button>
                    </div>
                </div>

                <!-- Sliders Content -->
                <div class="p-6">

                    @if($sliders->isEmpty())
                        <div class="text-center py-16">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No sliders yet</h3>
                            <p class="text-gray-600 mb-6">Get started by creating your first hero slider</p>
                            <button @click="$dispatch('open-dialog-add-slider')" 
                                    class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create First Slider
                            </button>
                        </div>
                    @else
                        <!-- Drag & Drop Info -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-blue-900">Drag & Drop to Reorder</p>
                                    <p class="text-sm text-blue-700 mt-1">Click and hold the drag handle (≡) to reorder sliders. Changes save automatically.</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4" id="sliders-container">
                            @foreach($sliders as $slider)
                                <div class="bg-white border border-gray-200 rounded-lg p-5 hover:border-gray-300 hover:shadow-sm transition-all duration-200" data-slider-id="{{ $slider->id }}">
                                    <div class="flex items-start gap-5">
                                        <!-- Drag Handle -->
                                        <div class="cursor-move text-gray-400 hover:text-gray-700 transition-colors pt-1">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                            </svg>
                                        </div>

                                        <!-- Image Preview -->
                                        <div class="flex-shrink-0">
                                            <img src="{{ $slider->image_url }}" alt="{{ $slider->title }}" class="w-40 h-24 object-cover rounded-lg border border-gray-200">
                                        </div>

                                        <!-- Content -->
                                        <div class="flex-grow">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-grow">
                                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $slider->title }}</h3>
                                                    @if($slider->subtitle)
                                                        <p class="text-sm text-gray-600 mb-3">{{ $slider->subtitle }}</p>
                                                    @endif
                                                    <div class="flex items-center gap-4 mt-3">
                                                        <span class="inline-flex items-center text-sm text-gray-600">
                                                            <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                                            </svg>
                                                            Order: {{ $slider->order }}
                                                        </span>
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $slider->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $slider->is_active ? 'bg-green-500' : 'bg-gray-500' }}"></span>
                                                            {{ $slider->is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                        @if($slider->link)
                                                            <a href="{{ $slider->link }}" target="_blank" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700">
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                                </svg>
                                                                View Link
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Actions -->
                                                <div class="flex items-center gap-2 ml-4">
                                                    <button @click="$dispatch('open-dialog-edit-slider-{{ $slider->id }}')" 
                                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                                            title="Edit slider">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </button>
                                                    <form id="delete-slider-form-{{ $slider->id }}" 
                                                          action="{{ route('admin.homepage-settings.slider.destroy', $slider) }}" 
                                                          method="POST" 
                                                          style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <button type="button"
                                                            @click="$dispatch('confirm-modal', {
                                                                title: 'Delete Slider',
                                                                message: 'Are you sure you want to delete &quot;{{ addslashes($slider->title) }}&quot;? This action cannot be undone.',
                                                                onConfirm: () => document.getElementById('delete-slider-form-{{ $slider->id }}').submit()
                                                            })"
                                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                                            title="Delete slider">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <!-- Edit Slider Dialog -->
                            <x-dialog-with-footer name="edit-slider-{{ $slider->id }}" title="Edit Slider" max-width="2xl">
                                <form id="edit-slider-form-{{ $slider->id }}" action="{{ route('admin.homepage-settings.slider.update', $slider) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    @include('admin.homepage-settings.partials.slider-form', ['slider' => $slider])
                                </form>
                                
                                <x-slot name="footer">
                                    <div class="flex justify-end gap-3">
                                        <button type="button" 
                                                @click="$dispatch('close-dialog-edit-slider-{{ $slider->id }}')"
                                                class="px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 font-medium transition-colors duration-200">
                                            Cancel
                                        </button>
                                        <button type="submit" 
                                                form="edit-slider-form-{{ $slider->id }}"
                                                class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 ease-in-out hover:shadow-md">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Update Slider
                                        </button>
                                    </div>
                                </x-slot>
                            </x-dialog-with-footer>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- General Settings Tab -->
        <div x-show="activeTab === 'general'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <!-- Section Header -->
                <div class="px-6 py-5 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">General Settings</h2>
                    <p class="text-sm text-gray-600 mt-1">Configure homepage sections and promotional content</p>
                </div>

                <!-- Settings Form -->
                <div class="p-6">
                
                <form action="{{ route('admin.homepage-settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @foreach($settings as $group => $groupSettings)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4 capitalize border-b pb-2">{{ $group }}</h3>
                            
                            <div class="space-y-4">
                                @foreach($groupSettings as $setting)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            {{ ucwords(str_replace('_', ' ', $setting['key'])) }}
                                        </label>
                                        
                                        @if($setting['description'])
                                            <p class="text-xs text-gray-500 mb-2">{{ $setting['description'] }}</p>
                                        @endif

                                        @if($setting['type'] === 'textarea')
                                            <textarea name="settings[{{ $setting['key'] }}]" 
                                                      rows="3" 
                                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $setting['value'] }}</textarea>
                                        
                                        @elseif($setting['type'] === 'boolean')
                                            <label class="flex items-center">
                                                <input type="checkbox" 
                                                       name="settings[{{ $setting['key'] }}]" 
                                                       value="1"
                                                       {{ $setting['value'] ? 'checked' : '' }}
                                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                <span class="ml-2 text-sm text-gray-600">Enable</span>
                                            </label>
                                        
                                        @elseif($setting['type'] === 'image')
                                            <div class="space-y-2">
                                                @if($setting['value'])
                                                    <img src="{{ Storage::url($setting['value']) }}" alt="Current" class="w-32 h-32 object-cover rounded">
                                                @endif
                                                <input type="file" 
                                                       name="settings[{{ $setting['key'] }}]" 
                                                       accept="image/*"
                                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                            </div>
                                        
                                        @else
                                            <input type="text" 
                                                   name="settings[{{ $setting['key'] }}]" 
                                                   value="{{ $setting['value'] }}"
                                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="flex justify-end pt-6 border-t border-gray-200">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 ease-in-out hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Slider Dialog -->
<x-dialog-with-footer name="add-slider" title="Add New Slider" max-width="2xl">
    <form id="add-slider-form" action="{{ route('admin.homepage-settings.slider.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.homepage-settings.partials.slider-form')
    </form>
    
    <x-slot name="footer">
        <div class="flex justify-end gap-3">
            <button type="button" 
                    @click="$dispatch('close-dialog-add-slider')"
                    class="px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 font-medium transition-colors duration-200">
                Cancel
            </button>
            <button type="submit" 
                    form="add-slider-form"
                    class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 ease-in-out hover:shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Add Slider
            </button>
        </div>
    </x-slot>
</x-dialog-with-footer>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    // Initialize Sortable for drag and drop
    const container = document.getElementById('sliders-container');
    if (container) {
        new Sortable(container, {
            animation: 150,
            handle: '.cursor-move',
            onEnd: function(evt) {
                const sliders = [];
                document.querySelectorAll('[data-slider-id]').forEach((el, index) => {
                    sliders.push({
                        id: el.dataset.sliderId,
                        order: index
                    });
                });

                fetch('{{ route("admin.homepage-settings.slider.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ sliders })
                });
            }
        });
    }
</script>
@endpush
@endsection

@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-2">
            <a href="{{ route('admin.advertisements.slots.index') }}"
                class="text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Create Ad Slot</h1>
        </div>
        <p class="text-sm text-gray-600">Define a new advertisement placement location</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.advertisements.slots.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Slot Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        required
                        placeholder="e.g., Homepage Header Banner"
                        class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('name') border-red-500 @enderror">
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">
                        Slug <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        name="slug"
                        id="slug"
                        value="{{ old('slug') }}"
                        required
                        placeholder="e.g., homepage-header"
                        class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('slug') border-red-500 @enderror">
                    <p class="mt-1.5 text-xs text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Use lowercase with hyphens (e.g., my-custom-slot)
                    </p>
                    @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">
                        Location <span class="text-red-500">*</span>
                    </label>
                    <select name="location"
                        id="location"
                        required
                        class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('location') border-red-500 @enderror">
                        <option value="header" {{ old('location') == 'header' ? 'selected' : '' }}>Header</option>
                        <option value="footer" {{ old('location') == 'footer' ? 'selected' : '' }}>Footer</option>
                        <option value="sidebar" {{ old('location') == 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                        <option value="inline" {{ old('location') == 'inline' ? 'selected' : '' }}>Inline (Content)</option>
                        <option value="popup" {{ old('location') == 'popup' ? 'selected' : '' }}>Popup</option>
                        <option value="native" {{ old('location') == 'native' ? 'selected' : '' }}>Native (Feed)</option>
                    </select>
                    @error('location')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description"
                        id="description"
                        rows="3"
                        placeholder="Describe where this ad slot appears and its purpose..."
                        class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="default_width" class="block text-sm font-semibold text-gray-700 mb-2">
                            Width (px)
                        </label>
                        <input type="number"
                            name="default_width"
                            id="default_width"
                            value="{{ old('default_width') }}"
                            placeholder="728"
                            min="1"
                            class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('default_width') border-red-500 @enderror">
                        @error('default_width')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="default_height" class="block text-sm font-semibold text-gray-700 mb-2">
                            Height (px)
                        </label>
                        <input type="number"
                            name="default_height"
                            id="default_height"
                            value="{{ old('default_height') }}"
                            placeholder="90"
                            min="1"
                            class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('default_height') border-red-500 @enderror">
                        @error('default_height')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <p class="text-xs text-gray-500 -mt-2">Leave empty for responsive ads</p>

                <div class="flex items-center space-x-6 pt-2 pb-2 border-t border-gray-200">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox"
                            name="is_active"
                            value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-3 text-sm font-medium text-gray-700">Active</span>
                    </label>

                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox"
                            name="lazy_load"
                            value="1"
                            {{ old('lazy_load', true) ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-3 text-sm font-medium text-gray-700">Lazy Load</span>
                    </label>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.advertisements.slots.index') }}"
                        class="flex-1 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all text-center">
                        Cancel
                    </a>
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Slot
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function(e) {
        const slug = document.getElementById('slug');
        if (!slug.value || slug.dataset.manual !== 'true') {
            slug.value = e.target.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
    });

    // Mark slug as manually edited
    document.getElementById('slug').addEventListener('input', function() {
        this.dataset.manual = 'true';
    });
</script>
@endpush
@endsection
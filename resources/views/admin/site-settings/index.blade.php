{{-- 
/**
 * ModuleName: Site Settings Management
 * Purpose: Admin interface for managing site-wide settings including logo
 * 
 * @category Admin Views
 * @package  Resources\Views\Admin
 * @created  2025-11-11
 */
--}}

@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Site Settings</h1>
        <p class="text-gray-600 mt-2">Manage your site's general settings, appearance, and branding</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Settings Form -->
    <form action="{{ route('admin.site-settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @foreach($settings as $group => $groupSettings)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <!-- Group Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-xl font-semibold text-gray-900 capitalize">
                        {{ str_replace('_', ' ', $group) }} Settings
                    </h2>
                </div>

                <!-- Group Settings -->
                <div class="p-6 space-y-6">
                    @foreach($groupSettings as $setting)
                        <div class="setting-item">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $setting->label }}
                                @if($setting->description)
                                    <span class="block text-xs text-gray-500 font-normal mt-1">{{ $setting->description }}</span>
                                @endif
                            </label>

                            @if($setting->type === 'text')
                                <input 
                                    type="text" 
                                    name="settings[{{ $setting->key }}]" 
                                    value="{{ old('settings.' . $setting->key, $setting->value) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >

                            @elseif($setting->type === 'textarea')
                                <textarea 
                                    name="settings[{{ $setting->key }}]" 
                                    rows="4"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >{{ old('settings.' . $setting->key, $setting->value) }}</textarea>

                            @elseif($setting->type === 'image')
                                <div class="space-y-3">
                                    @if($setting->value)
                                        <div class="relative inline-block">
                                            <img 
                                                src="{{ asset('storage/' . $setting->value) }}" 
                                                alt="{{ $setting->label }}"
                                                class="max-h-32 rounded-lg border border-gray-200"
                                                id="preview-{{ $setting->key }}"
                                            >
                                            <button 
                                                type="button"
                                                onclick="removeLogo('{{ $setting->key }}')"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition"
                                                title="Remove image"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        <div class="text-gray-500 text-sm" id="no-image-{{ $setting->key }}">
                                            No image uploaded
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-center space-x-3">
                                        <label class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-700">Choose File</span>
                                            <input 
                                                type="file" 
                                                name="settings[{{ $setting->key }}]" 
                                                accept="image/*"
                                                class="hidden"
                                                onchange="previewImage(this, '{{ $setting->key }}')"
                                            >
                                        </label>
                                        <span class="text-xs text-gray-500">PNG, JPG, WEBP (Max 2MB)</span>
                                    </div>
                                </div>

                            @elseif($setting->type === 'boolean')
                                <label class="flex items-center cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="settings[{{ $setting->key }}]" 
                                        value="1"
                                        {{ old('settings.' . $setting->key, $setting->value) ? 'checked' : '' }}
                                        class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                    >
                                    <span class="ml-2 text-sm text-gray-700">Enable</span>
                                </label>

                            @endif

                            @error('settings.' . $setting->key)
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <!-- Submit Button -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.dashboard') }}" 
               class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                Cancel
            </a>
            <button 
                type="submit"
                class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Save Settings
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Preview image before upload
    function previewImage(input, key) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const previewId = 'preview-' + key;
                const noImageId = 'no-image-' + key;
                
                let preview = document.getElementById(previewId);
                const noImage = document.getElementById(noImageId);
                
                if (!preview) {
                    // Create preview image if it doesn't exist
                    const container = input.closest('.space-y-3');
                    const div = document.createElement('div');
                    div.className = 'relative inline-block';
                    div.innerHTML = `
                        <img 
                            src="${e.target.result}" 
                            alt="Preview"
                            class="max-h-32 rounded-lg border border-gray-200"
                            id="${previewId}"
                        >
                    `;
                    container.insertBefore(div, container.firstChild);
                    
                    if (noImage) {
                        noImage.style.display = 'none';
                    }
                } else {
                    preview.src = e.target.result;
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Remove logo via AJAX
    function removeLogo(key) {
        if (!confirm('Are you sure you want to remove this image?')) {
            return;
        }

        fetch('{{ route("admin.site-settings.remove-logo") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ key: key })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove preview image
                const preview = document.getElementById('preview-' + key);
                if (preview) {
                    preview.closest('.relative').remove();
                }
                
                // Show "no image" message
                const noImage = document.getElementById('no-image-' + key);
                if (noImage) {
                    noImage.style.display = 'block';
                }
                
                // Show success message
                alert(data.message);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while removing the image.');
        });
    }
</script>
@endpush
@endsection

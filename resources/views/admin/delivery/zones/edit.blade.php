@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Sticky Top Bar -->
    <div class="bg-white border-b border-gray-200 -mx-4 -mt-6 px-4 py-3 mb-6 sticky top-16 z-10 shadow-sm">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.delivery.zones.index') }}" 
                   class="text-gray-600 hover:text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Delivery Zones
                </a>
                <span class="text-gray-300">|</span>
                <h1 class="text-xl font-semibold text-gray-900">Edit Delivery Zone</h1>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.delivery.zones.index') }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" form="zoneForm"
                        class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Update Zone
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto">
        <form action="{{ route('admin.delivery.zones.update', $zone) }}" method="POST" id="zoneForm">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
                
                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Zone Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $zone->name) }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Code -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">
                            Code
                        </label>
                        <input type="text" 
                               name="code" 
                               id="code" 
                               value="{{ old('code', $zone->code) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('code') border-red-500 @enderror">
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $zone->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sort Order & Status -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">
                                Sort Order
                            </label>
                            <input type="number" 
                                   name="sort_order" 
                                   id="sort_order" 
                                   value="{{ old('sort_order', $zone->sort_order) }}"
                                   min="0"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sort_order') border-red-500 @enderror">
                            @error('sort_order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Status
                            </label>
                            <label class="inline-flex items-center mt-2">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $zone->is_active) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Geographic Coverage -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-globe mr-2 text-blue-600"></i>
                    Geographic Coverage
                </h2>
                
                <div class="space-y-4">
                    <!-- Countries -->
                    <div>
                        <label for="countries" class="block text-sm font-medium text-gray-700 mb-1">
                            Countries <span class="text-xs text-gray-500">(Comma-separated country codes)</span>
                        </label>
                        <input type="text" 
                               name="countries" 
                               id="countries" 
                               value="{{ old('countries', is_array($zone->countries) ? implode(', ', $zone->countries) : '') }}"
                               placeholder="BD, IN, US"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('countries') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Example: BD, IN, US, UK</p>
                        @error('countries')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- States -->
                    <div>
                        <label for="states" class="block text-sm font-medium text-gray-700 mb-1">
                            States/Provinces <span class="text-xs text-gray-500">(Comma-separated)</span>
                        </label>
                        <input type="text" 
                               name="states" 
                               id="states" 
                               value="{{ old('states', is_array($zone->states) ? implode(', ', $zone->states) : '') }}"
                               placeholder="Dhaka, California, Maharashtra"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('states') border-red-500 @enderror">
                        @error('states')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cities -->
                    <div>
                        <label for="cities" class="block text-sm font-medium text-gray-700 mb-1">
                            Cities <span class="text-xs text-gray-500">(Comma-separated)</span>
                        </label>
                        <input type="text" 
                               name="cities" 
                               id="cities" 
                               value="{{ old('cities', is_array($zone->cities) ? implode(', ', $zone->cities) : '') }}"
                               placeholder="Dhaka City, Los Angeles, Mumbai"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cities') border-red-500 @enderror">
                        @error('cities')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Postal Codes -->
                    <div>
                        <label for="postal_codes" class="block text-sm font-medium text-gray-700 mb-1">
                            Postal Codes <span class="text-xs text-gray-500">(Comma-separated)</span>
                        </label>
                        <input type="text" 
                               name="postal_codes" 
                               id="postal_codes" 
                               value="{{ old('postal_codes', is_array($zone->postal_codes) ? implode(', ', $zone->postal_codes) : '') }}"
                               placeholder="1000, 1200, 90001"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('postal_codes') border-red-500 @enderror">
                        @error('postal_codes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('admin.delivery.zones.index') }}" 
                   class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Update Zone
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('zoneForm').addEventListener('submit', function(e) {
    // Convert comma-separated strings to arrays
    const fields = ['countries', 'states', 'cities', 'postal_codes'];
    
    fields.forEach(field => {
        const input = document.querySelector(`input[name="${field}"]`);
        if (input && input.value) {
            // Split by comma, trim whitespace, filter empty values
            const values = input.value.split(',')
                .map(v => v.trim())
                .filter(v => v.length > 0);
            
            // Remove original input
            input.remove();
            
            // Add hidden inputs for each value
            values.forEach((value, index) => {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = `${field}[]`;
                hidden.value = value;
                this.appendChild(hidden);
            });
        }
    });
});
</script>
@endpush
@endsection

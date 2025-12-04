@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Sticky Top Bar -->
    <div class="bg-white border-b border-gray-200 -mx-4 -mt-6 px-4 py-3 mb-6 sticky top-16 z-10 shadow-sm">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.delivery.rates.index') }}" 
                   class="text-gray-600 hover:text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Delivery Rates
                </a>
                <span class="text-gray-300">|</span>
                <h1 class="text-xl font-semibold text-gray-900">Create Delivery Rate</h1>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.delivery.rates.index') }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" form="delivery-rate-form"
                        class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Create Rate
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto">
        <form action="{{ route('admin.delivery.rates.store') }}" method="POST" id="delivery-rate-form">
        @csrf

        <div class="space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
                
                <div class="space-y-4">
                    <!-- Zone -->
                    <div>
                        <label for="delivery_zone_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Delivery Zone <span class="text-red-500">*</span>
                        </label>
                        <select name="delivery_zone_id" 
                                id="delivery_zone_id"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('delivery_zone_id') border-red-500 @enderror">
                            <option value="">Select Zone</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->id }}" {{ old('delivery_zone_id') == $zone->id ? 'selected' : '' }}>
                                    {{ $zone->name }} ({{ $zone->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('delivery_zone_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Method -->
                    <div>
                        <label for="delivery_method_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Delivery Method <span class="text-red-500">*</span>
                        </label>
                        <select name="delivery_method_id" 
                                id="delivery_method_id"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('delivery_method_id') border-red-500 @enderror">
                            <option value="">Select Method</option>
                            @foreach($methods as $method)
                                <option value="{{ $method->id }}" {{ old('delivery_method_id') == $method->id ? 'selected' : '' }}>
                                    {{ $method->name }} ({{ ucfirst(str_replace('_', ' ', $method->type)) }})
                                </option>
                            @endforeach
                        </select>
                        @error('delivery_method_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Base Rate -->
                    <div>
                        <label for="base_rate" class="block text-sm font-medium text-gray-700 mb-1">
                            Base Rate (৳) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="base_rate" 
                               id="base_rate" 
                               value="{{ old('base_rate', 0) }}"
                               step="0.01"
                               min="0"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('base_rate') border-red-500 @enderror">
                        @error('base_rate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Status
                        </label>
                        <label class="inline-flex items-center mt-2">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Additional Fees -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-money-bill-wave mr-2 text-blue-600"></i>
                    Additional Fees
                </h2>
                
                <div class="space-y-4">
                    <!-- Handling Fee -->
                    <div>
                        <label for="handling_fee" class="block text-sm font-medium text-gray-700 mb-1">
                            Handling Fee (৳)
                        </label>
                        <input type="number" 
                               name="handling_fee" 
                               id="handling_fee" 
                               value="{{ old('handling_fee', 0) }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('handling_fee') border-red-500 @enderror">
                        @error('handling_fee')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Insurance Fee -->
                    <div>
                        <label for="insurance_fee" class="block text-sm font-medium text-gray-700 mb-1">
                            Insurance Fee (৳)
                        </label>
                        <input type="number" 
                               name="insurance_fee" 
                               id="insurance_fee" 
                               value="{{ old('insurance_fee', 0) }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('insurance_fee') border-red-500 @enderror">
                        @error('insurance_fee')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- COD Fee -->
                    <div>
                        <label for="cod_fee" class="block text-sm font-medium text-gray-700 mb-1">
                            Cash on Delivery Fee (৳)
                        </label>
                        <input type="number" 
                               name="cod_fee" 
                               id="cod_fee" 
                               value="{{ old('cod_fee', 0) }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cod_fee') border-red-500 @enderror">
                        @error('cod_fee')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('admin.delivery.rates.index') }}" 
                   class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Create Rate
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

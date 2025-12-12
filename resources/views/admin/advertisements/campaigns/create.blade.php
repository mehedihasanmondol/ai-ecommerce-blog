@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-6">
    {{-- Sticky Top Bar --}}
    <div class="bg-white border-b border-gray-200 -mx-4 -mt-6 px-4 py-3 mb-6 sticky top-16 z-10 shadow-sm">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.advertisements.campaigns.index') }}"
                    class="text-gray-600 hover:text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Campaigns
                </a>
                <span class="text-gray-300">|</span>
                <h1 class="text-xl font-semibold text-gray-900">Create Campaign</h1>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.advertisements.campaigns.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" form="campaign-form"
                    class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Create Campaign
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto">
        <form action="{{ route('admin.advertisements.campaigns.store') }}" method="POST" id="campaign-form">
            @csrf

            <div class="space-y-6">
                {{-- Basic Information --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-bullhorn mr-2 text-blue-600"></i>
                        Basic Information
                    </h2>

                    <div class="space-y-4">
                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Campaign Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                name="name"
                                id="name"
                                value="{{ old('name') }}"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Description
                            </label>
                            <textarea name="description"
                                id="description"
                                rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status & Priority --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select name="status"
                                    id="status"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="paused" {{ old('status') == 'paused' ? 'selected' : '' }}>Paused</option>
                                    <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                </select>
                                @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">
                                    Priority <span class="text-red-500">*</span>
                                </label>
                                <input type="number"
                                    name="priority"
                                    id="priority"
                                    value="{{ old('priority', 5) }}"
                                    min="1"
                                    max="10"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('priority') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">1 (lowest) to 10 (highest)</p>
                                @error('priority')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Schedule & Limits --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                        Schedule & Limits
                    </h2>

                    <div class="space-y-4">
                        {{-- Dates --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    Start Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date"
                                    name="start_date"
                                    id="start_date"
                                    value="{{ old('start_date', date('Y-m-d')) }}"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('start_date') border-red-500 @enderror">
                                @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    End Date <span class="text-xs text-gray-500">(Optional)</span>
                                </label>
                                <input type="date"
                                    name="end_date"
                                    id="end_date"
                                    value="{{ old('end_date') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('end_date') border-red-500 @enderror">
                                @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Impression Limits --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="daily_impression_limit" class="block text-sm font-medium text-gray-700 mb-1">
                                    Daily Impression Limit <span class="text-xs text-gray-500">(Optional)</span>
                                </label>
                                <input type="number"
                                    name="daily_impression_limit"
                                    id="daily_impression_limit"
                                    value="{{ old('daily_impression_limit') }}"
                                    min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('daily_impression_limit') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Leave empty for unlimited</p>
                                @error('daily_impression_limit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="total_impression_limit" class="block text-sm font-medium text-gray-700 mb-1">
                                    Total Impression Limit <span class="text-xs text-gray-500">(Optional)</span>
                                </label>
                                <input type="number"
                                    name="total_impression_limit"
                                    id="total_impression_limit"
                                    value="{{ old('total_impression_limit') }}"
                                    min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('total_impression_limit') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Campaign stops when reached</p>
                                @error('total_impression_limit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Click Limits --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="daily_click_limit" class="block text-sm font-medium text-gray-700 mb-1">
                                    Daily Click Limit <span class="text-xs text-gray-500">(Optional)</span>
                                </label>
                                <input type="number"
                                    name="daily_click_limit"
                                    id="daily_click_limit"
                                    value="{{ old('daily_click_limit') }}"
                                    min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('daily_click_limit') border-red-500 @enderror">
                                @error('daily_click_limit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="total_click_limit" class="block text-sm font-medium text-gray-700 mb-1">
                                    Total Click Limit <span class="text-xs text-gray-500">(Optional)</span>
                                </label>
                                <input type="number"
                                    name="total_click_limit"
                                    id="total_click_limit"
                                    value="{{ old('total_click_limit') }}"
                                    min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('total_click_limit') border-red-500 @enderror">
                                @error('total_click_limit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Info Box --}}
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
                        <div>
                            <h3 class="text-sm font-semibold text-blue-900 mb-1">Next Steps</h3>
                            <p class="text-sm text-blue-800">
                                After creating the campaign, you'll be able to add creatives (ads) to it.
                                Each creative can target specific ad slots and categories.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.advertisements.campaigns.index') }}"
                        class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Create Campaign
                    </button>
                </div>
            </div>
        </form>
    </div>
    @endsection
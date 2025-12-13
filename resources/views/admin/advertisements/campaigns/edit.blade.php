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
                <h1 class="text-xl font-semibold text-gray-900">Edit Campaign: {{ $campaign->name }}</h1>
            </div>
            <div class="flex items-center space-x-3">
                @if(auth()->user()->hasPermission('advertisements.analytics'))
                <a href="{{ route('admin.advertisements.analytics.campaign', $campaign) }}"
                    class="px-4 py-2 text-sm font-medium text-purple-700 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100">
                    <i class="fas fa-chart-line mr-2"></i>View Analytics
                </a>
                @endif
                <a href="{{ route('admin.advertisements.campaigns.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" form="campaign-form"
                    class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Creatives Management (Outside of form) --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-images mr-2 text-blue-600"></i>
                            Ad Creatives ({{ $campaign->creatives->count() }})
                        </h2>
                        <button type="button"
                            onclick="openCreativeModal()"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-plus mr-2"></i>Add Creative
                        </button>
                    </div>

                    @if($campaign->creatives->count() > 0)
                    <div class="space-y-3" id="creatives-list">
                        @foreach($campaign->creatives->sortBy('sort_order') as $creative)
                        <div class="border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors creative-item" data-creative-id="{{ $creative->id }}">
                            <div class="flex items-start p-4">
                                {{-- Drag Handle --}}
                                <div class="drag-handle cursor-move mr-3 text-gray-400 hover:text-gray-600 flex-shrink-0">
                                    <i class="fas fa-grip-vertical"></i>
                                </div>
                                {{-- Left: Image/Icon + Basic Info --}}
                                <div class="flex items-start space-x-4 flex-1">
                                    {{-- Thumbnail/Icon --}}
                                    @if($creative->type === 'image' && $creative->image_path)
                                    <img src="{{ asset('storage/' . $creative->image_path) }}"
                                        alt="{{ $creative->name }}"
                                        class="w-20 h-20 object-cover rounded border border-gray-200">
                                    @else
                                    <div class="w-20 h-20 bg-gray-100 rounded border border-gray-200 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-{{ $creative->type === 'video' ? 'video' : ($creative->type === 'gif' ? 'file-image' : 'code') }} text-2xl text-gray-400"></i>
                                    </div>
                                    @endif

                                    {{-- Details --}}
                                    <div class="flex-1 min-w-0">
                                        {{-- Name & Type --}}
                                        <div class="flex items-center gap-2 mb-2">
                                            <h4 class="text-sm font-semibold text-gray-900">{{ $creative->name }}</h4>
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $creative->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                                {{ $creative->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>

                                        {{-- Type & Dimensions --}}
                                        <div class="text-xs text-gray-600 mb-2">
                                            <span class="inline-flex items-center gap-1">
                                                <i class="fas fa-tag"></i>
                                                <span class="capitalize">{{ $creative->type }}</span>
                                            </span>
                                            @if($creative->width && $creative->height)
                                            <span class="mx-1">•</span>
                                            <span class="inline-flex items-center gap-1">
                                                <i class="fas fa-ruler-combined"></i>
                                                {{ $creative->width }}×{{ $creative->height }}px
                                            </span>
                                            @endif
                                        </div>

                                        {{-- Targeted Slots --}}
                                        @if($creative->slots->count() > 0)
                                        <div class="mb-2">
                                            <div class="text-xs font-medium text-gray-700 mb-1">
                                                <i class="fas fa-th-large mr-1"></i>Targeted Slots:
                                            </div>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($creative->slots as $slot)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-blue-50 text-blue-700 border border-blue-200">
                                                    {{ $slot->name }}
                                                </span>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif

                                        {{-- Targeted Categories --}}
                                        @if($creative->categories->count() > 0)
                                        <div class="mb-2">
                                            <div class="text-xs font-medium text-gray-700 mb-1">
                                                <i class="fas fa-folder mr-1"></i>Targeted Categories:
                                            </div>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($creative->categories->take(3) as $category)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-purple-50 text-purple-700 border border-purple-200">
                                                    {{ $category->name }}
                                                </span>
                                                @endforeach
                                                @if($creative->categories->count() > 3)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-50 text-gray-600">
                                                    +{{ $creative->categories->count() - 3 }} more
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        @else
                                        <div class="text-xs text-gray-500 mb-2">
                                            <i class="fas fa-globe mr-1"></i>All Categories
                                        </div>
                                        @endif

                                        {{-- Link URL --}}
                                        @if($creative->link_url)
                                        <div class="text-xs text-gray-600 mb-2">
                                            <i class="fas fa-link mr-1"></i>
                                            <a href="{{ $creative->link_url }}" target="_blank" class="text-blue-600 hover:underline truncate inline-block max-w-xs">
                                                {{ Str::limit($creative->link_url, 50) }}
                                            </a>
                                        </div>
                                        @endif

                                        {{-- Performance Metrics --}}
                                        <div class="flex items-center gap-4 text-xs text-gray-600 mt-2 pt-2 border-t border-gray-100">
                                            <span class="inline-flex items-center gap-1" title="Impressions">
                                                <i class="fas fa-eye text-purple-600"></i>
                                                <span class="font-medium">{{ number_format($creative->getTotalImpressions()) }}</span>
                                            </span>
                                            <span class="inline-flex items-center gap-1" title="Clicks">
                                                <i class="fas fa-mouse-pointer text-indigo-600"></i>
                                                <span class="font-medium">{{ number_format($creative->getTotalClicks()) }}</span>
                                            </span>
                                            <span class="inline-flex items-center gap-1" title="Click-Through Rate">
                                                <i class="fas fa-chart-line text-green-600"></i>
                                                <span class="font-medium {{ $creative->getCTR() > 2 ? 'text-green-600' : '' }}">
                                                    CTR: {{ $creative->getCTR() }}%
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Right: Actions --}}
                                <div class="flex items-start space-x-2 ml-4">
                                    <button type="button"
                                        onclick="openEditModal({{ $creative->id }})"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded transition-colors"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button"
                                        onclick="deleteCreative({{ $creative->id }})"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded transition-colors"
                                        title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-images text-4xl text-gray-300 mb-2"></i>
                        <p>No creatives added yet.</p>
                    </div>
                    @endif
                </div>

                <form action="{{ route('admin.advertisements.campaigns.update', $campaign) }}" method="POST" id="campaign-form">
                    @csrf
                    @method('PUT')

                    {{-- Basic Information --}}
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
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
                                    value="{{ old('name', $campaign->name) }}"
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
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $campaign->description) }}</textarea>
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
                                        <option value="active" {{ old('status', $campaign->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="paused" {{ old('status', $campaign->status) == 'paused' ? 'selected' : '' }}>Paused</option>
                                        <option value="scheduled" {{ old('status', $campaign->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        <option value="completed" {{ old('status', $campaign->status) == 'completed' ? 'selected' : '' }}>Completed</option>
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
                                        value="{{ old('priority', $campaign->priority) }}"
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
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
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
                                        value="{{ old('start_date', $campaign->start_date->format('Y-m-d')) }}"
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
                                        value="{{ old('end_date', $campaign->end_date?->format('Y-m-d')) }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('end_date') border-red-500 @enderror">
                                    @error('end_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Limits --}}
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <label for="daily_impression_limit" class="block text-sm font-medium text-gray-700 mb-1">
                                        Daily Impressions
                                    </label>
                                    <input type="number"
                                        name="daily_impression_limit"
                                        id="daily_impression_limit"
                                        value="{{ old('daily_impression_limit', $campaign->daily_impression_limit) }}"
                                        min="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>

                                <div>
                                    <label for="total_impression_limit" class="block text-sm font-medium text-gray-700 mb-1">
                                        Total Impressions
                                    </label>
                                    <input type="number"
                                        name="total_impression_limit"
                                        id="total_impression_limit"
                                        value="{{ old('total_impression_limit', $campaign->total_impression_limit) }}"
                                        min="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>

                                <div>
                                    <label for="daily_click_limit" class="block text-sm font-medium text-gray-700 mb-1">
                                        Daily Clicks
                                    </label>
                                    <input type="number"
                                        name="daily_click_limit"
                                        id="daily_click_limit"
                                        value="{{ old('daily_click_limit', $campaign->daily_click_limit) }}"
                                        min="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>

                                <div>
                                    <label for="total_click_limit" class="block text-sm font-medium text-gray-700 mb-1">
                                        Total Clicks
                                    </label>
                                    <input type="number"
                                        name="total_click_limit"
                                        id="total_click_limit"
                                        value="{{ old('total_click_limit', $campaign->total_click_limit) }}"
                                        min="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Campaign Statistics --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Impressions</span>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($campaign->getTotalImpressions()) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Clicks</span>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($campaign->getTotalClicks()) }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t">
                            <span class="text-sm font-medium text-gray-700">CTR</span>
                            <span class="text-lg font-bold {{ $campaign->getCTR() > 2 ? 'text-green-600' : 'text-gray-900' }}">
                                {{ $campaign->getCTR() }}%
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Quick Info --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Info</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-gray-600">Created:</span>
                            <span class="text-gray-900">{{ $campaign->created_at->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Creatives:</span>
                            <span class="text-gray-900">{{ $campaign->creatives->count() }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Status:</span>
                            <span class="text-gray-900 capitalize">{{ $campaign->status }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Priority:</span>
                            <span class="text-gray-900">{{ $campaign->priority }}/10</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add Creative Modal (Glassmorphism with Sticky Header/Footer) --}}
<div id="addCreativeModal" class="hidden fixed inset-0 z-50">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeCreativeModal()"></div>

    {{-- Modal Content --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl w-full max-w-2xl border border-white/20 transform transition-all max-h-[90vh] flex flex-col">
            {{-- Gradient Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 via-transparent to-blue-500/10 rounded-2xl pointer-events-none"></div>

            {{-- Sticky Header --}}
            <div class="relative flex justify-between items-center p-6 pb-4 border-b border-gray-200/50 bg-white rounded-t-2xl">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center shadow-lg">
                        <i class="fas fa-images text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Add Creative</h3>
                </div>
                <button type="button"
                    onclick="closeCreativeModal()"
                    class="text-gray-400 hover:text-gray-600 transition-colors p-1 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            {{-- Scrollable Content --}}
            <div class="relative flex-1 overflow-y-auto">
                <div class="p-6 pt-4 bg-white">
                    {{-- Error Messages --}}
                    <div id="creativeErrors" class="hidden mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle text-red-600 mt-0.5 mr-2"></i>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-red-800 mb-1">There were some errors with your submission:</h4>
                                <ul id="creativeErrorList" class="text-sm text-red-700 list-disc list-inside space-y-1"></ul>
                            </div>
                        </div>
                    </div>

                    <form id="creativeForm" class="space-y-4">
                        @csrf

                        {{-- Name & Type --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="creative_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                    name="name"
                                    id="creative_name"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="creative_type" class="block text-sm font-medium text-gray-700 mb-1">
                                    Type <span class="text-red-500">*</span>
                                </label>
                                <select name="type"
                                    id="creative_type"
                                    required
                                    onchange="handleTypeChange(this.value)"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="image">Image</option>
                                    <option value="gif">GIF</option>
                                    <option value="video">Video</option>
                                    <option value="html">HTML Code</option>
                                    <option value="script">Script/Third-party</option>
                                </select>
                            </div>
                        </div>

                        {{-- Ad Slot Selection (Multiple) - MOVED TO TOP --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Target Ad Slots <span class="text-xs text-gray-500">(Select one or more)</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto p-3 border border-gray-200 rounded-lg bg-gray-50">
                                @forelse($slots as $slot)
                                <label class="flex items-start p-3 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-500 hover:bg-green-50 transition-all">
                                    <input type="checkbox"
                                        name="slot_ids[]"
                                        value="{{ $slot->id }}"
                                        data-width="{{ $slot->default_width }}"
                                        data-height="{{ $slot->default_height }}"
                                        onchange="handleSlotSelection(this)"
                                        class="mt-1 w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                    <div class="ml-3 flex-1">
                                        <div class="font-semibold text-gray-900">{{ $slot->name }}</div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-map-marker-alt mr-1 text-xs"></i>
                                                {{ ucfirst($slot->location) }}
                                            </span>
                                            @if($slot->default_width && $slot->default_height)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-ruler-combined mr-1 text-xs"></i>
                                                {{ $slot->default_width }}×{{ $slot->default_height }}px
                                            </span>
                                            @endif
                                        </div>
                                        @if($slot->description)
                                        <p class="text-xs text-gray-600 mt-1">{{ Str::limit($slot->description, 50) }}</p>
                                        @endif
                                    </div>
                                </label>
                                @empty
                                <div class="col-span-2 text-center py-4 text-gray-500">
                                    No ad slots available. Create slots first.
                                </div>
                                @endforelse
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Dimensions will auto-fill from the first selected slot, but you can adjust them manually.
                            </p>
                        </div>

                        {{-- Category Selection - MOVED TO TOP --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Target Categories <span class="text-xs text-gray-500">(Optional - Leave empty for all)</span>
                            </label>
                            <div class="max-h-48 overflow-y-auto p-3 border border-gray-200 rounded-lg bg-gray-50">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @forelse($categories as $category)
                                    <label class="flex items-center p-2 bg-white border border-gray-200 rounded hover:bg-blue-50 cursor-pointer">
                                        <input type="checkbox"
                                            name="category_ids[]"
                                            value="{{ $category->id }}"
                                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-900">{{ $category->name }}</span>
                                    </label>
                                    @empty
                                    <div class="col-span-2 text-center py-4 text-gray-500">
                                        No categories available.
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- Image Upload (for image/gif types) --}}
                        <div id="field_image" class="field-group">
                            <label for="creative_image" class="block text-sm font-medium text-gray-700 mb-1">
                                Image Upload <span class="text-green-600">(Required for Image/GIF)</span>
                            </label>
                            <input type="file"
                                name="image"
                                id="creative_image"
                                accept="image/*"
                                onchange="previewImage(this)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                            <p class="mt-1 text-xs text-gray-500">Max size: 5MB. Supported formats: JPG, PNG, GIF, WebP</p>

                            {{-- Image Preview --}}
                            <div id="image_preview" class="hidden mt-3">
                                <div class="relative inline-block">
                                    <img id="image_preview_img" src="" alt="Preview" class="max-w-full max-h-64 rounded-lg border-2 border-green-200 shadow-sm">
                                    <button type="button"
                                        onclick="clearImagePreview()"
                                        class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-md transition-all">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Video URL (for video type) --}}
                        <div id="field_video" class="field-group hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="video_url" class="block text-sm font-medium text-gray-700 mb-1">
                                        Video URL <span class="text-green-600">(Required for Video)</span>
                                    </label>
                                    <input type="url"
                                        name="video_url"
                                        id="video_url"
                                        placeholder="https://example.com/video.mp4"
                                        oninput="previewVideo(this.value)"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label for="video_type" class="block text-sm font-medium text-gray-700 mb-1">
                                        Video Type
                                    </label>
                                    <select name="video_type"
                                        id="video_type"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        <option value="">Select type</option>
                                        <option value="pre-roll">Pre-roll (Before content)</option>
                                        <option value="mid-roll">Mid-roll (During content)</option>
                                        <option value="post-roll">Post-roll (After content)</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Video Preview --}}
                            <div id="video_preview" class="hidden mt-3">
                                <div class="relative inline-block w-full max-w-2xl">
                                    <div id="video_preview_player" class="rounded-lg border-2 border-green-200 shadow-sm overflow-hidden bg-black"></div>
                                    <button type="button"
                                        onclick="clearVideoPreview()"
                                        class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-md transition-all z-10">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- HTML/Script Content --}}
                        <div id="field_content" class="field-group hidden">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">
                                <span id="content_label">HTML Code</span> <span class="text-green-600">(Required for HTML/Script)</span>
                            </label>
                            <textarea name="content"
                                id="content"
                                rows="6"
                                placeholder="Paste your HTML or script code here..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent font-mono text-sm"></textarea>
                            <p class="mt-1 text-xs text-gray-500">Enter the complete HTML or third-party ad script code</p>
                        </div>

                        {{-- Link URL & Target --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label for="creative_link" class="block text-sm font-medium text-gray-700 mb-1">
                                    Link URL <span class="text-xs text-gray-500">(Optional)</span>
                                </label>
                                <input type="url"
                                    name="link_url"
                                    id="creative_link"
                                    placeholder="https://example.com/landing-page"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="link_target" class="block text-sm font-medium text-gray-700 mb-1">
                                    Link Target <span class="text-red-500">*</span>
                                </label>
                                <select name="link_target"
                                    id="link_target"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="_blank">New Tab</option>
                                    <option value="_self">Same Tab</option>
                                </select>
                            </div>
                        </div>



                        {{-- Dimensions --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="creative_width" class="block text-sm font-medium text-gray-700 mb-1">
                                    Width (px) <span class="text-xs text-gray-500">(Optional)</span>
                                </label>
                                <input type="number"
                                    name="width"
                                    id="creative_width"
                                    placeholder="728"
                                    min="1"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="creative_height" class="block text-sm font-medium text-gray-700 mb-1">
                                    Height (px) <span class="text-xs text-gray-500">(Optional)</span>
                                </label>
                                <input type="number"
                                    name="height"
                                    id="creative_height"
                                    placeholder="90"
                                    min="1"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                        </div>

                        {{-- Alt Text --}}
                        <div>
                            <label for="alt_text" class="block text-sm font-medium text-gray-700 mb-1">
                                Alt Text <span class="text-xs text-gray-500">(For accessibility)</span>
                            </label>
                            <input type="text"
                                name="alt_text"
                                id="alt_text"
                                placeholder="Description of the advertisement"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        {{-- Active Status & Sort Order --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox"
                                        name="is_active"
                                        id="is_active"
                                        value="1"
                                        checked
                                        class="w-5 h-5 rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                    <span class="ml-3 text-sm font-medium text-gray-700">Active (Display this creative)</span>
                                </label>
                            </div>
                            <div>
                                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">
                                    Sort Order <span class="text-xs text-gray-500">(Optional)</span>
                                </label>
                                <input type="number"
                                    name="sort_order"
                                    id="sort_order"
                                    value="0"
                                    min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Sticky Footer --}}
            <div class="relative flex justify-end space-x-3 p-6 pt-4 border-t border-gray-200/50 bg-white rounded-b-2xl">
                <button type="button"
                    onclick="closeCreativeModal()"
                    class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all">
                    Cancel
                </button>
                <button type="button"
                    onclick="submitCreativeForm()"
                    class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                    <i class="fas fa-plus mr-2"></i>Add Creative
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Edit Creative Modal (Dedicated, Separate from Add Modal) --}}
<div id="editCreativeModal" class="hidden fixed inset-0 z-50">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>

    {{-- Modal Content --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl w-full max-w-2xl border border-white/20 transform transition-all max-h-[90vh] flex flex-col">
            {{-- Gradient Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 via-transparent to-purple-500/10 rounded-2xl pointer-events-none"></div>

            {{-- Sticky Header --}}
            <div class="relative flex justify-between items-center p-6 pb-4 border-b border-gray-200/50 bg-white rounded-t-2xl">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-lg">
                        <i class="fas fa-edit text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Edit Creative</h3>
                </div>
                <button type="button"
                    onclick="closeEditModal()"
                    class="text-gray-400 hover:text-gray-600 transition-colors p-1 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            {{-- Scrollable Content --}}
            <div class="relative flex-1 overflow-y-auto">
                <div class="p-6 pt-4 bg-white">
                    {{-- Error Messages --}}
                    <div id="editCreativeErrors" class="hidden mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle text-red-600 mt-0.5 mr-2"></i>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-red-800 mb-1">There were some errors with your submission:</h4>
                                <ul id="editCreativeErrorList" class="text-sm text-red-700 list-disc list-inside space-y-1"></ul>
                            </div>
                        </div>
                    </div>

                    <form id="editCreativeForm" class="space-y-4">
                        @csrf
                        <input type="hidden" id="edit_creative_id" name="creative_id">

                        {{-- Name & Type --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="edit_creative_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                    name="name"
                                    id="edit_creative_name"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="edit_creative_type" class="block text-sm font-medium text-gray-700 mb-1">
                                    Type <span class="text-red-500">*</span>
                                </label>
                                <select name="type"
                                    id="edit_creative_type"
                                    required
                                    onchange="handleEditTypeChange(this.value)"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="image">Image</option>
                                    <option value="gif">GIF</option>
                                    <option value="video">Video</option>
                                    <option value="html">HTML Code</option>
                                    <option value="script">Script/Third-party</option>
                                </select>
                            </div>
                        </div>

                        {{-- Ad Slot Selection (Multiple) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Target Ad Slots <span class="text-xs text-gray-500">(Select one or more)</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto p-3 border border-gray-200 rounded-lg bg-gray-50">
                                @forelse($slots as $slot)
                                <label class="flex items-start p-3 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all">
                                    <input type="checkbox"
                                        name="slot_ids[]"
                                        value="{{ $slot->id }}"
                                        data-width="{{ $slot->default_width }}"
                                        data-height="{{ $slot->default_height }}"
                                        class="edit-slot-checkbox mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <div class="ml-3 flex-1">
                                        <div class="font-semibold text-gray-900">{{ $slot->name }}</div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-map-marker-alt mr-1 text-xs"></i>
                                                {{ ucfirst($slot->location) }}
                                            </span>
                                            @if($slot->default_width && $slot->default_height)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-ruler-combined mr-1 text-xs"></i>
                                                {{ $slot->default_width }}×{{ $slot->default_height }}px
                                            </span>
                                            @endif
                                        </div>
                                        @if($slot->description)
                                        <p class="text-xs text-gray-600 mt-1">{{ Str::limit($slot->description, 50) }}</p>
                                        @endif
                                    </div>
                                </label>
                                @empty
                                <div class="col-span-2 text-center py-4 text-gray-500">
                                    No ad slots available.
                                </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Category Selection --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Target Categories <span class="text-xs text-gray-500">(Optional - Leave empty for all)</span>
                            </label>
                            <div class="max-h-48 overflow-y-auto p-3 border border-gray-200 rounded-lg bg-gray-50">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @forelse($categories as $category)
                                    <label class="flex items-center p-2 bg-white border border-gray-200 rounded hover:bg-blue-50 cursor-pointer">
                                        <input type="checkbox"
                                            name="category_ids[]"
                                            value="{{ $category->id }}"
                                            class="edit-category-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-900">{{ $category->name }}</span>
                                    </label>
                                    @empty
                                    <div class="col-span-2 text-center py-4 text-gray-500">
                                        No categories available.
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- Image Upload (for image/gif types) --}}
                        <div id="edit_field_image" class="field-group">
                            <label for="edit_creative_image" class="block text-sm font-medium text-gray-700 mb-1">
                                Image Upload <span class="text-blue-600">(Optional - Leave empty to keep current)</span>
                            </label>
                            <input type="file"
                                name="image"
                                id="edit_creative_image"
                                accept="image/*"
                                onchange="previewEditImage(this)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-xs text-gray-500">Max size: 5MB. Supported formats: JPG, PNG, GIF, WebP</p>

                            {{-- Image Preview --}}
                            <div id="edit_image_preview" class="hidden mt-3">
                                <div class="relative inline-block">
                                    <img id="edit_image_preview_img" src="" alt="Preview" class="max-w-full max-h-64 rounded-lg border-2 border-blue-200 shadow-sm">
                                    <button type="button"
                                        onclick="clearEditImagePreview()"
                                        class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-md transition-all">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Video URL (for video type) --}}
                        <div id="edit_field_video" class="field-group hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="edit_video_url" class="block text-sm font-medium text-gray-700 mb-1">
                                        Video URL <span class="text-blue-600">(Required for Video)</span>
                                    </label>
                                    <input type="url"
                                        name="video_url"
                                        id="edit_video_url"
                                        placeholder="https://example.com/video.mp4"
                                        oninput="previewEditVideo(this.value)"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label for="edit_video_type" class="block text-sm font-medium text-gray-700 mb-1">
                                        Video Type
                                    </label>
                                    <select name="video_type"
                                        id="edit_video_type"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Select type</option>
                                        <option value="pre-roll">Pre-roll (Before content)</option>
                                        <option value="mid-roll">Mid-roll (During content)</option>
                                        <option value="post-roll">Post-roll (After content)</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Video Preview --}}
                            <div id="edit_video_preview" class="hidden mt-3">
                                <div class="relative inline-block w-full max-w-2xl">
                                    <div id="edit_video_preview_player" class="rounded-lg border-2 border-blue-200 shadow-sm overflow-hidden bg-black"></div>
                                    <button type="button"
                                        onclick="clearEditVideoPreview()"
                                        class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-md transition-all z-10">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- HTML/Script Content --}}
                        <div id="edit_field_content" class="field-group hidden">
                            <label for="edit_content" class="block text-sm font-medium text-gray-700 mb-1">
                                <span id="edit_content_label">HTML Code</span> <span class="text-blue-600">(Required for HTML/Script)</span>
                            </label>
                            <textarea name="content"
                                id="edit_content"
                                rows="6"
                                placeholder="Paste your HTML or script code here..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm"></textarea>
                            <p class="mt-1 text-xs text-gray-500">Enter the complete HTML or third-party ad script code</p>
                        </div>

                        {{-- Link URL & Target --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label for="edit_creative_link" class="block text-sm font-medium text-gray-700 mb-1">
                                    Link URL <span class="text-xs text-gray-500">(Optional)</span>
                                </label>
                                <input type="url"
                                    name="link_url"
                                    id="edit_creative_link"
                                    placeholder="https://example.com/landing-page"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="edit_link_target" class="block text-sm font-medium text-gray-700 mb-1">
                                    Link Target <span class="text-red-500">*</span>
                                </label>
                                <select name="link_target"
                                    id="edit_link_target"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="_blank">New Tab</option>
                                    <option value="_self">Same Tab</option>
                                </select>
                            </div>
                        </div>

                        {{-- Dimensions --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="edit_creative_width" class="block text-sm font-medium text-gray-700 mb-1">
                                    Width (px) <span class="text-xs text-gray-500">(Optional)</span>
                                </label>
                                <input type="number"
                                    name="width"
                                    id="edit_creative_width"
                                    placeholder="728"
                                    min="1"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="edit_creative_height" class="block text-sm font-medium text-gray-700 mb-1">
                                    Height (px) <span class="text-xs text-gray-500">(Optional)</span>
                                </label>
                                <input type="number"
                                    name="height"
                                    id="edit_creative_height"
                                    placeholder="90"
                                    min="1"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        {{-- Alt Text --}}
                        <div>
                            <label for="edit_alt_text" class="block text-sm font-medium text-gray-700 mb-1">
                                Alt Text <span class="text-xs text-gray-500">(For accessibility)</span>
                            </label>
                            <input type="text"
                                name="alt_text"
                                id="edit_alt_text"
                                placeholder="Description of the advertisement"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        {{-- Active Status & Sort Order --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox"
                                        name="is_active"
                                        id="edit_is_active"
                                        value="1"
                                        checked
                                        class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-3 text-sm font-medium text-gray-700">Active (Display this creative)</span>
                                </label>
                            </div>
                            <div>
                                <label for="edit_sort_order" class="block text-sm font-medium text-gray-700 mb-1">
                                    Sort Order <span class="text-xs text-gray-500">(Optional)</span>
                                </label>
                                <input type="number"
                                    name="sort_order"
                                    id="edit_sort_order"
                                    value="0"
                                    min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Sticky Footer --}}
            <div class="relative flex justify-end space-x-3 p-6 pt-4 border-t border-gray-200/50 bg-white rounded-b-2xl">
                <button type="button"
                    onclick="closeEditModal()"
                    class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all">
                    Cancel
                </button>
                <button type="button"
                    id="editCreativeSubmitBtn"
                    onclick="submitEditForm()"
                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                    <i class="fas fa-save mr-2"></i>Update Creative
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Open creative modal
    function openCreativeModal() {
        document.getElementById('addCreativeModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        // Reset form and show appropriate fields for default type (image)
        document.getElementById('creativeForm').reset();
        handleTypeChange('image');
        hideErrors();
    }

    // Close creative modal
    function closeCreativeModal() {
        document.getElementById('addCreativeModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('creativeForm').reset();
        hideErrors();
    }

    // Handle type change - show/hide conditional fields
    function handleTypeChange(type) {
        const imageField = document.getElementById('field_image');
        const videoField = document.getElementById('field_video');
        const contentField = document.getElementById('field_content');
        const contentLabel = document.getElementById('content_label');

        // Hide all conditional fields first
        imageField.classList.add('hidden');
        videoField.classList.add('hidden');
        contentField.classList.add('hidden');

        // Show relevant fields based on type
        if (type === 'image' || type === 'gif') {
            imageField.classList.remove('hidden');
        } else if (type === 'video') {
            videoField.classList.remove('hidden');
        } else if (type === 'html') {
            contentField.classList.remove('hidden');
            contentLabel.textContent = 'HTML Code';
        } else if (type === 'script') {
            contentField.classList.remove('hidden');
            contentLabel.textContent = 'Script Code';
        }
    }

    // Handle slot selection - auto-fill dimensions from first selected slot
    function handleSlotSelection(checkbox) {
        // Only auto-fill if this is being checked (not unchecked)
        if (!checkbox.checked) {
            return;
        }

        const width = checkbox.dataset.width;
        const height = checkbox.dataset.height;

        // Only auto-fill if slot has dimensions and creative dimensions are empty
        if (width && height) {
            const widthInput = document.getElementById('creative_width');
            const heightInput = document.getElementById('creative_height');

            // Auto-fill if fields are empty
            if (!widthInput.value && !heightInput.value) {
                widthInput.value = width;
                heightInput.value = height;

                // Show visual feedback
                widthInput.classList.add('bg-green-50');
                heightInput.classList.add('bg-green-50');

                setTimeout(() => {
                    widthInput.classList.remove('bg-green-50');
                    heightInput.classList.remove('bg-green-50');
                }, 1000);
            }
        }
    }

    // Hide error messages
    function hideErrors() {
        // Assuming there's a div with id="creativeErrors" and a ul with id="creativeErrorList"
        const errorContainer = document.getElementById('creativeErrors');
        if (errorContainer) {
            errorContainer.classList.add('hidden');
        }
        const errorList = document.getElementById('creativeErrorList');
        if (errorList) {
            errorList.innerHTML = '';
        }
    }

    // Show error messages
    function showErrors(errors) {
        const errorContainer = document.getElementById('creativeErrors');
        const errorList = document.getElementById('creativeErrorList');

        if (!errorContainer || !errorList) {
            console.error('Error display elements not found: creativeErrors or creativeErrorList');
            return;
        }

        errorList.innerHTML = '';

        // errors can be an object {field: [messages]} or array of strings
        if (typeof errors === 'object' && !Array.isArray(errors)) {
            Object.keys(errors).forEach(field => {
                errors[field].forEach(message => {
                    const li = document.createElement('li');
                    li.textContent = message;
                    errorList.appendChild(li);
                });
            });
        } else if (Array.isArray(errors)) {
            errors.forEach(message => {
                const li = document.createElement('li');
                li.textContent = message;
                errorList.appendChild(li);
            });
        } else {
            const li = document.createElement('li');
            li.textContent = errors;
            errorList.appendChild(li);
        }

        errorContainer.classList.remove('hidden');
        // Scroll to top of modal to show errors
        const modalContent = document.querySelector('#addCreativeModal .overflow-y-auto');
        if (modalContent) {
            modalContent.scrollTop = 0;
        }
    }

    // Submit creative form via AJAX
    function submitCreativeForm() {
        const form = document.getElementById('creativeForm');
        const formData = new FormData(form);
        const submitButton = document.querySelector('button[form="creativeForm"]');

        // Add file if selected
        const imageInput = document.getElementById('creative_image');
        if (imageInput && imageInput.files.length > 0) {
            formData.append('image', imageInput.files[0]);
        }

        // Disable submit button
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding...';
        }

        // Hide previous errors
        hideErrors();

        // Submit via AJAX
        fetch('{{ route("admin.advertisements.creatives.store", $campaign) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || form.querySelector('[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => Promise.reject(data));
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Success - reload page to show new creative
                    window.location.reload();
                } else {
                    // Show error message
                    showErrors(data.message || 'Failed to create creative');
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.innerHTML = '<i class="fas fa-plus mr-2"></i>Add Creative';
                    }
                }
            })
            .catch(error => {
                // Handle validation errors or other errors
                if (error.errors) {
                    showErrors(error.errors);
                } else if (error.message) {
                    showErrors(error.message);
                } else {
                    showErrors('An error occurred. Please try again.');
                }

                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="fas fa-plus mr-2"></i>Add Creative';
                }
            });

        return false; // Prevent default form submission
    }

    // Delete creative via AJAX
    function deleteCreative(creativeId) {
        window.dispatchEvent(new CustomEvent('confirm-modal', {
            detail: {
                title: 'Delete Ad Creative',
                message: 'Are you sure you want to delete this creative? This action cannot be undone.',
                onConfirm: () => {
                    const creativeElement = document.getElementById(`creative-${creativeId}`);

                    fetch(`/admin/advertisements/creatives/${creativeId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('[name="_token"]').value,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remove the creative element from DOM
                                if (creativeElement) {
                                    creativeElement.remove();
                                }

                                // Update the creative count
                                const countElements = document.querySelectorAll('.text-lg.font-semibold.text-gray-900');
                                countElements.forEach(el => {
                                    if (el.textContent.includes('Ad Creatives')) {
                                        const currentCount = parseInt(el.textContent.match(/\d+/)[0]);
                                        el.innerHTML = `<i class="fas fa-images mr-2 text-blue-600"></i>Ad Creatives (${currentCount - 1})`;
                                    }
                                });

                                // Show empty state if no creatives left
                                const creativesContainer = creativeElement?.parentElement;
                                if (creativesContainer && creativesContainer.children.length === 0) {
                                    creativesContainer.innerHTML = `
                                    <div class="text-center py-8 text-gray-500">
                                        <i class="fas fa-images text-4xl text-gray-300 mb-2"></i>
                                        <p>No creatives added yet.</p>
                                    </div>
                                `;
                                }

                                // Show success message (optional - you can add a toast notification here)
                                console.log(data.message);
                            } else {
                                alert('Error: ' + (data.message || 'Failed to delete creative'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Failed to delete creative. Please try again.');
                        });
                }
            }
        }));
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreativeModal();
        }
    });

    // Handle form submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('creativeForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                submitCreativeForm();
            });
        }

        // Initialize SortableJS for drag-and-drop
        initializeSortable();
    });

    // Initialize SortableJS
    function initializeSortable() {
        const creativesList = document.getElementById('creatives-list');
        if (creativesList) {
            new Sortable(creativesList, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'bg-blue-50',
                onEnd: function(evt) {
                    updateSortOrder();
                }
            });
        }
    }

    // Update sort order after drag-and-drop
    function updateSortOrder() {
        const creativeItems = document.querySelectorAll('.creative-item');
        const creativeIds = Array.from(creativeItems).map(item => item.dataset.creativeId);

        fetch('{{ route("admin.advertisements.creatives.update-sort-order") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    creative_ids: creativeIds
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Sort order updated successfully');
                } else {
                    alert('Failed to update sort order: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update sort order. Please try again.');
            });
    }

    // Open edit modal (dedicated modal, separate from add)
    function openEditModal(creativeId) {
        const modal = document.getElementById('editCreativeModal');
        const form = document.getElementById('editCreativeForm');

        // Show modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Reset form first
        form.reset();
        hideEditErrors();

        // Store creative ID
        document.getElementById('edit_creative_id').value = creativeId;

        // Fetch creative data via AJAX to populate form
        fetch(`/admin/advertisements/campaigns/{{ $campaign->id }}/creatives`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                const creative = data.creatives.find(c => c.id == creativeId);
                if (creative) {
                    // Populate form fields with edit_ prefix
                    document.getElementById('edit_creative_name').value = creative.name || '';
                    document.getElementById('edit_creative_type').value = creative.type || 'image';
                    document.getElementById('edit_creative_link').value = creative.link_url || '';
                    document.getElementById('edit_link_target').value = creative.link_target || '_blank';
                    document.getElementById('edit_creative_width').value = creative.width || '';
                    document.getElementById('edit_creative_height').value = creative.height || '';
                    document.getElementById('edit_alt_text').value = creative.alt_text || '';
                    document.getElementById('edit_is_active').checked = creative.is_active;
                    document.getElementById('edit_sort_order').value = creative.sort_order || 0;

                    // Handle type-specific fields
                    handleEditTypeChange(creative.type);

                    if (creative.type === 'html' || creative.type === 'script') {
                        document.getElementById('edit_content').value = creative.content || '';
                    }
                    if (creative.type === 'video') {
                        document.getElementById('edit_video_url').value = creative.video_url || '';
                        document.getElementById('edit_video_type').value = creative.video_type || '';
                    }

                    // Populate slot checkboxes (using edit modal specific class)
                    const slotCheckboxes = document.querySelectorAll('.edit-slot-checkbox');
                    slotCheckboxes.forEach(checkbox => {
                        checkbox.checked = creative.slots && creative.slots.includes(parseInt(checkbox.value));
                    });

                    // Populate category checkboxes (using edit modal specific class)
                    const categoryCheckboxes = document.querySelectorAll('.edit-category-checkbox');
                    categoryCheckboxes.forEach(checkbox => {
                        checkbox.checked = creative.categories && creative.categories.includes(parseInt(checkbox.value));
                    });

                    // Show existing image preview if available
                    if ((creative.type === 'image' || creative.type === 'gif') && creative.image_url) {
                        const preview = document.getElementById('edit_image_preview');
                        const previewImg = document.getElementById('edit_image_preview_img');
                        previewImg.src = creative.image_url;
                        preview.classList.remove('hidden');
                    }

                    // Show existing video preview if available
                    if (creative.type === 'video' && creative.video_url) {
                        previewEditVideo(creative.video_url);
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching creative data:', error);
                alert('Failed to load creative data');
                closeEditModal();
            });
    }

    // Close edit modal
    function closeEditModal() {
        document.getElementById('editCreativeModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('editCreativeForm').reset();
        hideEditErrors();

        // Clear image preview
        const imgPreview = document.getElementById('edit_image_preview');
        if (imgPreview) {
            imgPreview.classList.add('hidden');
            document.getElementById('edit_image_preview_img').src = '';
        }

        // Clear video preview
        const videoPreview = document.getElementById('edit_video_preview');
        if (videoPreview) {
            videoPreview.classList.add('hidden');
            document.getElementById('edit_video_preview_player').innerHTML = '';
        }
    }

    // Handle edit type change - show/hide conditional fields
    function handleEditTypeChange(type) {
        const imageField = document.getElementById('edit_field_image');
        const videoField = document.getElementById('edit_field_video');
        const contentField = document.getElementById('edit_field_content');
        const contentLabel = document.getElementById('edit_content_label');

        // Hide all conditional fields first
        imageField.classList.add('hidden');
        videoField.classList.add('hidden');
        contentField.classList.add('hidden');

        // Show relevant fields based on type
        if (type === 'image' || type === 'gif') {
            imageField.classList.remove('hidden');
        } else if (type === 'video') {
            videoField.classList.remove('hidden');
        } else if (type === 'html') {
            contentField.classList.remove('hidden');
            contentLabel.textContent = 'HTML Code';
        } else if (type === 'script') {
            contentField.classList.remove('hidden');
            contentLabel.textContent = 'Script Code';
        }
    }

    // Hide edit error messages
    function hideEditErrors() {
        const errorContainer = document.getElementById('editCreativeErrors');
        if (errorContainer) {
            errorContainer.classList.add('hidden');
        }
        const errorList = document.getElementById('editCreativeErrorList');
        if (errorList) {
            errorList.innerHTML = '';
        }
    }

    // Show edit error messages
    function showEditErrors(errors) {
        const errorContainer = document.getElementById('editCreativeErrors');
        const errorList = document.getElementById('editCreativeErrorList');

        if (!errorContainer || !errorList) {
            console.error('Error display elements not found');
            return;
        }

        errorList.innerHTML = '';

        // errors can be an object {field: [messages]} or array of strings
        if (typeof errors === 'object' && !Array.isArray(errors)) {
            Object.keys(errors).forEach(field => {
                errors[field].forEach(message => {
                    const li = document.createElement('li');
                    li.textContent = message;
                    errorList.appendChild(li);
                });
            });
        } else if (Array.isArray(errors)) {
            errors.forEach(message => {
                const li = document.createElement('li');
                li.textContent = message;
                errorList.appendChild(li);
            });
        } else {
            const li = document.createElement('li');
            li.textContent = errors;
            errorList.appendChild(li);
        }

        errorContainer.classList.remove('hidden');
        // Scroll to top of modal to show errors
        const modalContent = document.querySelector('#editCreativeModal .overflow-y-auto');
        if (modalContent) {
            modalContent.scrollTop = 0;
        }
    }

    // Submit edit form
    function submitEditForm() {
        const creativeId = document.getElementById('edit_creative_id').value;
        const form = document.getElementById('editCreativeForm');
        const formData = new FormData(form);
        const submitButton = document.getElementById('editCreativeSubmitBtn');

        // Add file if selected
        const imageInput = document.getElementById('edit_creative_image');
        if (imageInput && imageInput.files.length > 0) {
            formData.append('image', imageInput.files[0]);
        }

        // Disable submit button
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';

        // Hide previous errors
        hideEditErrors();

        // Submit via AJAX
        fetch(`/admin/advertisements/creatives/${creativeId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Accept': 'application/json',
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload page to show updated creative
                    window.location.reload();
                } else {
                    showEditErrors(data.errors || data.message);
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="fas fa-save mr-2"></i>Update Creative';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showEditErrors('Failed to update creative. Please try again.');
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-save mr-2"></i>Update Creative';
            });
    }

    // =============== MEDIA PREVIEW FUNCTIONS ===============

    // Preview image for ADD modal
    function previewImage(input) {
        const preview = document.getElementById('image_preview');
        const previewImg = document.getElementById('image_preview_img');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            };

            reader.readAsDataURL(input.files[0]);
        } else {
            clearImagePreview();
        }
    }

    // Clear image preview for ADD modal
    function clearImagePreview() {
        const preview = document.getElementById('image_preview');
        const previewImg = document.getElementById('image_preview_img');
        const input = document.getElementById('creative_image');

        preview.classList.add('hidden');
        previewImg.src = '';
        input.value = '';
    }

    // Preview video for ADD modal
    function previewVideo(url) {
        const preview = document.getElementById('video_preview');
        const player = document.getElementById('video_preview_player');

        if (!url) {
            clearVideoPreview();
            return;
        }

        // Extract YouTube ID
        let youtubeMatch = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i);
        if (youtubeMatch) {
            const youtubeId = youtubeMatch[1];
            player.innerHTML = `<iframe width="100%" height="400" src="https://www.youtube.com/embed/${youtubeId}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
            preview.classList.remove('hidden');
            return;
        }

        // Extract Vimeo ID
        let vimeoMatch = url.match(/vimeo\.com\/(\d+)/);
        if (vimeoMatch) {
            const vimeoId = vimeoMatch[1];
            player.innerHTML = `<iframe src="https://player.vimeo.com/video/${vimeoId}" width="100%" height="400" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>`;
            preview.classList.remove('hidden');
            return;
        }

        // If not YouTube or Vimeo, show message
        player.innerHTML = `<div class="flex items-center justify-center h-64 text-gray-400"><i class="fas fa-video mr-2"></i>Video preview not available for this URL type</div>`;
        preview.classList.remove('hidden');
    }

    // Clear video preview for ADD modal
    function clearVideoPreview() {
        const preview = document.getElementById('video_preview');
        const player = document.getElementById('video_preview_player');
        const input = document.getElementById('video_url');

        preview.classList.add('hidden');
        player.innerHTML = '';
        input.value = '';
    }

    // =============== EDIT MODAL PREVIEW FUNCTIONS ===============

    // Preview image for EDIT modal
    function previewEditImage(input) {
        const preview = document.getElementById('edit_image_preview');
        const previewImg = document.getElementById('edit_image_preview_img');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            };

            reader.readAsDataURL(input.files[0]);
        } else {
            clearEditImagePreview();
        }
    }

    // Clear image preview for EDIT modal
    function clearEditImagePreview() {
        const preview = document.getElementById('edit_image_preview');
        const previewImg = document.getElementById('edit_image_preview_img');
        const input = document.getElementById('edit_creative_image');

        preview.classList.add('hidden');
        previewImg.src = '';
        input.value = '';
    }

    // Preview video for EDIT modal
    function previewEditVideo(url) {
        const preview = document.getElementById('edit_video_preview');
        const player = document.getElementById('edit_video_preview_player');

        if (!url) {
            clearEditVideoPreview();
            return;
        }

        // Extract YouTube ID
        let youtubeMatch = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i);
        if (youtubeMatch) {
            const youtubeId = youtubeMatch[1];
            player.innerHTML = `<iframe width="100%" height="400" src="https://www.youtube.com/embed/${youtubeId}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
            preview.classList.remove('hidden');
            return;
        }

        // Extract Vimeo ID
        let vimeoMatch = url.match(/vimeo\.com\/(\d+)/);
        if (vimeoMatch) {
            const vimeoId = vimeoMatch[1];
            player.innerHTML = `<iframe src="https://player.vimeo.com/video/${vimeoId}" width="100%" height="400" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>`;
            preview.classList.remove('hidden');
            return;
        }

        // If not YouTube or Vimeo, show message
        player.innerHTML = `<div class="flex items-center justify-center h-64 text-gray-400"><i class="fas fa-video mr-2"></i>Video preview not available for this URL type</div>`;
        preview.classList.remove('hidden');
    }

    // Clear video preview for EDIT modal
    function clearEditVideoPreview() {
        const preview = document.getElementById('edit_video_preview');
        const player = document.getElementById('edit_video_preview_player');
        const input = document.getElementById('edit_video_url');

        preview.classList.add('hidden');
        player.innerHTML = '';
        input.value = '';
    }
</script>

<!-- SortableJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

@endpush
@endsection
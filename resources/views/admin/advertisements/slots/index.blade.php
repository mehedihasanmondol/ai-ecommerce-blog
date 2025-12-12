@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Ad Slots</h1>
                <p class="mt-1 text-sm text-gray-600">Manage advertisement placement locations across your site</p>
            </div>
            @if(auth()->user()->hasPermission('advertisements.create'))
            <button type="button"
                onclick="openCreateModal()"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors shadow-lg hover:shadow-xl">
                <i class="fas fa-plus mr-2"></i>
                Create Slot
            </button>
            @endif
        </div>
    </div>

    {{-- Statistics Cards --}}
    @php
    $slots = \App\Modules\Advertisement\Models\AdSlot::with('campaigns')->get();
    $statistics = [
    'total' => $slots->count(),
    'active' => $slots->where('is_active', true)->count(),
    'inactive' => $slots->where('is_active', false)->count(),
    'total_campaigns' => $slots->sum(fn($s) => $s->campaigns->count()),
    ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-th-large text-2xl text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Slots</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['active'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-times-circle text-2xl text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Inactive</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['inactive'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-bullhorn text-2xl text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Campaigns</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['total_campaigns'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Slots Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Slot
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Location
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Size
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Campaigns
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($slots as $slot)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $slot->name }}</div>
                            @if($slot->description)
                            <div class="text-sm text-gray-500">{{ $slot->description }}</div>
                            @endif
                            <div class="mt-1">
                                <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $slot->slug }}</code>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 capitalize">
                            {{ $slot->location }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        @if($slot->default_width && $slot->default_height)
                        {{ $slot->default_width }}×{{ $slot->default_height }}px
                        @else
                        <span class="text-gray-400 italic">Responsive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if(auth()->user()->hasPermission('advertisements.edit'))
                        <button onclick="toggleSlotStatus({{ $slot->id }})"
                            class="status-toggle-{{ $slot->id }}">
                            @if($slot->is_active)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Inactive
                            </span>
                            @endif
                        </button>
                        @else
                        @if($slot->is_active)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Active
                        </span>
                        @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Inactive
                        </span>
                        @endif
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <span class="inline-flex items-center">
                            <i class="fas fa-bullhorn text-purple-600 mr-1"></i>
                            {{ $slot->campaigns->count() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            @if(auth()->user()->hasPermission('advertisements.delete'))
                            <form id="delete-slot-{{ $slot->id }}"
                                action="{{ route('admin.advertisements.slots.destroy', $slot) }}"
                                method="POST"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    onclick="window.dispatchEvent(new CustomEvent('confirm-modal', {
                                        detail: {
                                            title: 'Delete Ad Slot',
                                            message: 'Are you sure you want to delete &quot;{{ $slot->name }}&quot;?{{ $slot->campaigns->count() > 0 ? ' This will affect ' . $slot->campaigns->count() . ' campaign(s).' : '' }} This action cannot be undone.',
                                            onConfirm: function() { document.getElementById('delete-slot-{{ $slot->id }}').submit(); }
                                        }
                                    }))"
                                    class="text-red-600 hover:text-red-900"
                                    title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <i class="fas fa-th-large text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No ad slots found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Usage Examples --}}
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-code mr-2 text-blue-600"></i>
            Usage Examples
        </h2>
        <p class="text-sm text-gray-600 mb-4">Use these Blade components in your templates to display ads:</p>

        <div class="space-y-4">
            <div>
                <div class="text-sm text-gray-700 mb-2">
                    <strong>Banner Ads:</strong><br>
                    <code class="text-xs text-gray-800">&lt;x-advertisement.ad-banner slot-slug="header-banner" /&gt;</code><br>
                    <code class="text-xs text-gray-800">&lt;x-advertisement.ad-banner slot-slug="sidebar-top" :categoryId="$category->id" /&gt;</code>
                </div>

                <div class="text-sm text-gray-700 mb-2">
                    <strong>Inline Ads:</strong><br>
                    <code class="text-xs text-gray-800">&lt;x-advertisement.ad-inline slot-slug="content-middle" :categoryId="$post->blog_category_id" /&gt;</code>
                </div>

                <div class="text-sm text-gray-700 mb-2">
                    <strong>Native Ads:</strong><br>
                    <code class="text-xs text-gray-800">&lt;x-advertisement.ad-native slot-slug="native-feed" :categoryId="$category->id" /&gt;</code>
                </div>

                <div class="text-sm text-gray-700">
                    <strong>Popup Ads:</strong><br>
                    <code class="text-xs text-gray-800">&lt;x-advertisement.ad-popup /&gt;</code>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Create Slot Modal (Glassmorphism with Sticky Header/Footer) --}}
<div id="createSlotModal" class="hidden fixed inset-0 z-50">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeCreateModal()"></div>

    {{-- Modal Content --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl w-full max-w-lg border border-white/20 transform transition-all max-h-[90vh] flex flex-col">
            {{-- Gradient Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 via-transparent to-purple-500/10 rounded-2xl pointer-events-none"></div>

            {{-- Sticky Header --}}
            <div class="relative flex justify-between items-center p-6 pb-4 border-b border-gray-200/50 bg-white rounded-t-2xl">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-lg">
                        <i class="fas fa-plus text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Create Ad Slot</h3>
                </div>
                <button type="button"
                    onclick="closeCreateModal()"
                    class="text-gray-400 hover:text-gray-600 transition-colors p-1 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            {{-- Scrollable Content --}}
            <div class="relative flex-1 overflow-y-auto">
                <div class="p-6 pt-4 bg-white">
                    <form action="{{ route('admin.advertisements.slots.store') }}" method="POST" id="createSlotForm">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Slot Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                    name="name"
                                    id="name"
                                    required
                                    class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>

                            <div>
                                <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Slug <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                    name="slug"
                                    id="slug"
                                    required
                                    class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <p class="mt-1.5 text-xs text-gray-500 flex items-center">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Use lowercase with hyphens (e.g., my-custom-slot)
                                </p>
                            </div>

                            <div>
                                <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Location <span class="text-red-500">*</span>
                                </label>
                                <select name="location"
                                    id="location"
                                    required
                                    class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="header">Header</option>
                                    <option value="footer">Footer</option>
                                    <option value="sidebar">Sidebar</option>
                                    <option value="inline">Inline (Content)</option>
                                    <option value="popup">Popup</option>
                                    <option value="native">Native (Feed)</option>
                                </select>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea name="description"
                                    id="description"
                                    rows="3"
                                    class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="default_width" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Width (px)
                                    </label>
                                    <input type="number"
                                        name="default_width"
                                        id="default_width"
                                        placeholder="728"
                                        class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                </div>
                                <div>
                                    <label for="default_height" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Height (px)
                                    </label>
                                    <input type="number"
                                        name="default_height"
                                        id="default_height"
                                        placeholder="90"
                                        class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                </div>
                            </div>

                            <div class="flex items-center space-x-6 pt-2 pb-2">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox"
                                        name="is_active"
                                        value="1"
                                        checked
                                        class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-3 text-sm font-medium text-gray-700">Active</span>
                                </label>

                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox"
                                        name="lazy_load"
                                        value="1"
                                        checked
                                        class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-3 text-sm font-medium text-gray-700">Lazy Load</span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Sticky Footer --}}
            <div class="relative flex justify-end space-x-3 p-6 pt-4 border-t border-gray-200/50 bg-white rounded-b-2xl">
                <button type="button"
                    onclick="closeCreateModal()"
                    class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all">
                    Cancel
                </button>
                <button type="submit"
                    form="createSlotForm"
                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                    <i class="fas fa-plus mr-2"></i>Create Slot
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Create Modal Functions
    function openCreateModal() {
        document.getElementById('createSlotModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeCreateModal() {
        document.getElementById('createSlotModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('createSlotForm').reset();
    }

    // Close modals on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreateModal();
        }
    });

    function toggleSlotStatus(slotId) {
        fetch(`/admin/advertisements/slots/${slotId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    window.dispatchEvent(new CustomEvent('alert-toast', {
                        detail: {
                            type: 'error',
                            message: 'Failed to update status: ' + data.message
                        }
                    }));
                }
            })
            .catch(error => {
                window.dispatchEvent(new CustomEvent('alert-toast', {
                    detail: {
                        type: 'error',
                        message: 'Error: ' + error
                    }
                }));
            });
    }
</script>
@endpush
@endsection
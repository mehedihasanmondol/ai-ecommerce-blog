@extends('layouts.admin')

@section('title', 'Permission Settings')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Sticky Top Bar -->
    <div class="bg-white border-b border-gray-200 -mx-4 -mt-6 px-4 py-3 mb-6 sticky top-16 z-10 shadow-sm">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.system-settings.index') }}" 
                   class="text-gray-600 hover:text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    System Settings
                </a>
                <span class="text-gray-300">|</span>
                <h1 class="text-xl font-semibold text-gray-900">Permission Settings</h1>
                <!-- Status Counters -->
                <div class="hidden md:flex items-center gap-2 text-sm">
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-green-50 text-green-700 rounded-lg">
                        <i class="fas fa-check-circle"></i>
                        <span class="font-medium" x-text="selectedCount">0</span>
                        <span class="text-green-600">enabled</span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-red-50 text-red-700 rounded-lg">
                        <i class="fas fa-ban"></i>
                        <span class="font-medium" x-text="totalCount - selectedCount">0</span>
                        <span class="text-red-600">disabled</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.system-settings.index') }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" form="permission-settings-form"
                        class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Update Settings
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto">
        <form action="{{ route('admin.module-settings.update') }}" method="POST" id="permission-settings-form" class="bg-white rounded-lg shadow" x-data="{
            selectedCount: 0,
            totalCount: {{ $permissions->count() }},
            init() {
                this.updateCount();
            },
            updateCount() {
                this.selectedCount = this.$el.querySelectorAll('input[name=\"permissions[]\"]:checked').length;
            }
        }" @change="updateCount()">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Permission Configuration</h3>
                    <p class="text-sm text-gray-600">
                        Enable or disable individual permissions at the system level. Disabled permissions will not be available for assignment in roles and will not work even if previously assigned.
                    </p>
                </div>

                <!-- Permissions -->
                @if($permissions->count() > 0)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Enable/Disable Permissions</h3>
                    
                    @php
                        $groupedPermissions = $permissions->groupBy('module');
                    @endphp

                    <div class="space-y-6">
                        @foreach($groupedPermissions as $module => $modulePermissions)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3 capitalize">
                                <i class="fas fa-folder mr-2 text-blue-600"></i>
                                {{ $module ?? 'General' }} Module
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($modulePermissions as $permission)
                                @php
                                    $isRequired = $permission->slug === 'permissions.manage';
                                @endphp
                                <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg {{ $isRequired ? 'bg-blue-50 border-blue-300' : 'hover:bg-gray-50' }} {{ $isRequired ? 'cursor-not-allowed' : 'cursor-pointer' }}" 
                                       {{ $isRequired ? 'title=This permission is required to access Permission Settings and cannot be disabled' : '' }}>
                                    <input type="checkbox" 
                                           name="permissions[]" 
                                           value="{{ $permission->id }}"
                                           {{ in_array($permission->id, old('permissions', $enabledPermissions)) || $isRequired ? 'checked' : '' }}
                                           {{ $isRequired ? 'disabled' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 {{ $isRequired ? 'opacity-50' : '' }}">
                                    @if($isRequired)
                                        <input type="hidden" name="permissions[]" value="{{ $permission->id }}">
                                    @endif
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-medium {{ $isRequired ? 'text-blue-700' : 'text-gray-700' }}">{{ $permission->name }}</span>
                                            @if($isRequired)
                                                <i class="fas fa-lock text-xs text-blue-600" title="Required permission"></i>
                                            @endif
                                        </div>
                                        <p class="text-xs {{ $isRequired ? 'text-blue-600' : 'text-gray-500' }}">{{ $permission->slug }}</p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Warning -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-yellow-800">Warning</h4>
                            <p class="text-sm text-yellow-700 mt-1">
                                Disabling a permission will prevent it from being assigned to any role and will make it non-functional even if already assigned. 
                                This is a system-level control that takes precedence over all role-based permissions.
                                Changes will affect all users across the entire system.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection

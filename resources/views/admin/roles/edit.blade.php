@extends('layouts.admin')

@section('title', 'Edit Role')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Sticky Top Bar -->
    <div class="bg-white border-b border-gray-200 -mx-4 -mt-6 px-4 py-3 mb-6 sticky top-16 z-10 shadow-sm">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.roles.index') }}" 
                   class="text-gray-600 hover:text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Roles
                </a>
                <span class="text-gray-300">|</span>
                <h1 class="text-xl font-semibold text-gray-900">Edit Role: {{ $role->name }}</h1>
                <!-- Permission Counter -->
                <div class="hidden md:flex items-center gap-2 text-sm">
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg">
                        <i class="fas fa-shield-alt"></i>
                        <span class="font-medium" x-text="selectedCount">0</span>
                        <span class="text-blue-600">/</span>
                        <span class="font-medium" x-text="totalCount">{{ $permissions->count() }}</span>
                    </div>
                    @if($role->users->count() > 0)
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-yellow-50 text-yellow-700 rounded-lg">
                        <i class="fas fa-users"></i>
                        <span class="font-medium">{{ $role->users->count() }}</span>
                        <span class="text-yellow-600">users</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.roles.index') }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" form="role-form"
                        class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Update Role
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto">
        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" id="role-form" class="bg-white rounded-lg shadow" x-data="{
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
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Role Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Role Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name', $role->name) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Slug <span class="text-gray-500 text-xs">(Auto-updated with name)</span>
                            </label>
                            <input type="text" 
                                   name="slug" 
                                   value="{{ old('slug', $role->slug) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('slug') border-red-500 @enderror"
                                   readonly>
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" 
                                      rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $role->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Permissions -->
                @if($permissions->count() > 0 && auth()->user()->hasPermission('roles.assign-permissions'))
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Assign Permissions</h3>
                    
                    @php
                        $groupedPermissions = $permissions->groupBy('module');
                        $rolePermissionIds = $role->permissions->pluck('id')->toArray();
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
                                <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" 
                                           name="permissions[]" 
                                           value="{{ $permission->id }}"
                                           {{ in_array($permission->id, old('permissions', $rolePermissionIds)) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-gray-700">{{ $permission->name }}</span>
                                        <p class="text-xs text-gray-500">{{ $permission->slug }}</p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Status -->
                <div>
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', $role->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Active Role</span>
                    </label>
                </div>

                <!-- Warning if role has users -->
                @if($role->users->count() > 0)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-yellow-800">Warning</h4>
                            <p class="text-sm text-yellow-700 mt-1">
                                This role is currently assigned to {{ $role->users->count() }} user(s). 
                                Changes to permissions will affect all users with this role.
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

        </form>
    </div>
</div>
@endsection

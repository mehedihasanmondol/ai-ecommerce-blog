{{-- 
/**
 * Admin Sidebar Menu Component
 * Purpose: Reusable sidebar menu with permission-based visibility
 * 
 * @category Components
 * @package  Admin
 * @created  2025-11-17
 */
--}}

@props(['mobile' => false])

<!-- Dashboard -->
<a href="{{ route('admin.dashboard') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-tachometer-alt w-5 mr-3"></i>
    <span>Dashboard</span>
</a>

<!-- User Management Section -->
@if(auth()->user()->hasPermission('users.view'))
<div class="pt-4 pb-2">
    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">User Management</p>
</div>

<a href="{{ route('admin.users.index') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-users w-5 mr-3"></i>
    <span>Users</span>
</a>

<a href="{{ route('admin.roles.index') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.roles.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-shield-alt w-5 mr-3"></i>
    <span>Roles & Permissions</span>
</a>
@endif

<!-- Blog Section -->
@if(auth()->user()->hasPermission('posts.view'))
<div class="pt-4 pb-2">
    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Blog</p>
</div>

<a href="{{ route('admin.blog.posts.index') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.blog.posts.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-file-alt w-5 mr-3"></i>
    <span>Posts</span>
</a>

<a href="{{ route('admin.blog.categories.index') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.blog.categories.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-folder w-5 mr-3"></i>
    <span>Categories</span>
</a>

<a href="{{ route('admin.blog.tags.index') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.blog.tags.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-tag w-5 mr-3"></i>
    <span>Tags</span>
</a>

<a href="{{ route('admin.blog.comments.index') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.blog.comments.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-comments w-5 mr-3"></i>
    <span>Comments</span>
</a>
@endif

<?php

namespace App\Livewire\User;

use App\Modules\User\Services\RoleService;
use App\Modules\User\Repositories\PermissionRepository;
use Livewire\Component;

/**
 * ModuleName: User Management
 * Purpose: Livewire component for editing roles without page reload
 * 
 * Key Methods:
 * - mount(): Initialize component with role data
 * - updateRole(): Update role and show toast notification
 * 
 * Dependencies:
 * - RoleService
 * - PermissionRepository
 * 
 * @author AI Assistant
 * @date 2025-12-06
 */
class RoleEdit extends Component
{
    public $roleId;
    public $name;
    public $slug;
    public $description;
    public $permissions = [];
    public $isActive = true;
    public $role;
    public $allPermissions;
    public $userCount = 0;

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255',
        'description' => 'nullable|string',
        'permissions' => 'array',
        'isActive' => 'boolean',
    ];

    public function mount($roleId, RoleService $roleService, PermissionRepository $permissionRepository)
    {
        abort_if(!auth()->user()->hasPermission('roles.edit'), 403, 'You do not have permission to edit roles.');

        $this->roleId = $roleId;
        $this->role = $roleService->getRoleById($roleId);

        if (!$this->role) {
            abort(404, 'Role not found');
        }

        // Initialize form fields
        $this->name = $this->role->name;
        $this->slug = $this->role->slug;
        $this->description = $this->role->description;
        $this->isActive = $this->role->is_active;

        // Get all enabled permissions (filtered by module settings)
        $this->allPermissions = $permissionRepository->getActiveByEnabledModules();

        // Get role's permissions and filter by module settings
        $allRolePermissionIds = $this->role->permissions->pluck('id')->toArray();
        $enabledPermissionIds = $this->allPermissions->pluck('id')->toArray();

        // Only include permissions that are both assigned to role AND enabled in module settings
        // Use array_values to re-index the array (remove keys from array_intersect)
        $this->permissions = array_values(array_intersect($allRolePermissionIds, $enabledPermissionIds));

        $this->userCount = $this->role->users->count();
    }

    public function toggleAllPermissionsInModule($module)
    {
        abort_if(!auth()->user()->hasPermission('roles.edit'), 403, 'You do not have permission to edit roles.');

        // Ensure permissions is always an array
        if (!is_array($this->permissions)) {
            $this->permissions = [];
        }

        // Get all permission IDs for this module
        $modulePermissionIds = $this->allPermissions
            ->where('module', $module)
            ->pluck('id')
            ->toArray();

        // Check if all permissions in this module are already selected
        $allSelected = empty(array_diff($modulePermissionIds, $this->permissions));

        if ($allSelected) {
            // Unmark all - remove all module permissions from selected permissions
            $this->permissions = array_values(array_diff($this->permissions, $modulePermissionIds));
        } else {
            // Mark all - add all module permissions to selected permissions
            $this->permissions = array_values(array_unique(array_merge($this->permissions, $modulePermissionIds)));
        }
    }

    public function updateRole(RoleService $roleService)
    {
        abort_if(!auth()->user()->hasPermission('roles.edit'), 403, 'You do not have permission to edit roles.');

        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'permissions' => $this->permissions,
            'is_active' => $this->isActive,
        ];

        $result = $roleService->updateRole($this->roleId, $data);

        if ($result['success']) {
            // Refresh role data
            $this->role = $roleService->getRoleById($this->roleId);
            $this->userCount = $this->role->users->count();

            // Dispatch toast notification
            $this->dispatch('show-toast', [
                'message' => $result['message'],
                'type' => 'success'
            ]);
        } else {
            $this->dispatch('show-toast', [
                'message' => $result['message'],
                'type' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.user.role-edit');
    }
}

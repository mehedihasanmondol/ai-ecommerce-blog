<?php

namespace App\Modules\User\Repositories;

use App\Models\SystemSetting;
use App\Modules\User\Models\Permission;
use Illuminate\Database\Eloquent\Collection;

/**
 * ModuleName: User Management
 * Purpose: Handle all database operations for permissions
 * 
 * Key Methods:
 * - getAll(): Get all permissions
 * - getByModule(): Get permissions by module
 * - getActive(): Get active permissions
 * - getActiveByEnabledModules(): Get permissions filtered by enabled modules
 * - create(): Create new permission
 * 
 * Dependencies:
 * - Permission Model
 * - SystemSetting Model
 * 
 * @author AI Assistant
 * @date 2025-11-04
 */
class PermissionRepository
{
    /**
     * Get all permissions
     */
    public function getAll(): Collection
    {
        return Permission::all();
    }

    /**
     * Get active permissions
     */
    public function getActive(): Collection
    {
        return Permission::active()->get();
    }

    /**
     * Get active permissions filtered by system-level permission settings
     * This is the main method to use for role configuration UI
     * Only returns permissions that are enabled at the system level
     */
    public function getActiveByEnabledModules(): Collection
    {
        $allPermissions = Permission::active()
            ->orderBy('module')
            ->orderBy('name')
            ->get();
        
        // Filter by system-level permission settings
        return $allPermissions->filter(function ($permission) {
            $key = "permission_{$permission->slug}_enabled";
            return SystemSetting::get($key, true); // Default to enabled if not set
        });
    }

    /**
     * Get list of enabled modules from system settings
     */
    private function getEnabledModules(): array
    {
        $modules = [
            'user', 'product', 'order', 'delivery', 'stock',
            'blog', 'content', 'reports', 'finance', 'system',
            'feedback', 'appointments'
        ];

        $enabled = [];
        foreach ($modules as $module) {
            if (SystemSetting::get("module_{$module}_enabled", true)) {
                $enabled[] = $module;
            }
        }

        return $enabled;
    }

    /**
     * Get permissions by module
     */
    public function getByModule(string $module): Collection
    {
        return Permission::byModule($module)->get();
    }

    /**
     * Find permission by ID
     */
    public function findById(int $id): ?Permission
    {
        return Permission::find($id);
    }

    /**
     * Create new permission
     */
    public function create(array $data): Permission
    {
        return Permission::create($data);
    }

    /**
     * Update permission
     */
    public function update(int $id, array $data): bool
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return false;
        }

        return $permission->update($data);
    }

    /**
     * Delete permission
     */
    public function delete(int $id): bool
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return false;
        }

        return $permission->delete();
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Modules\User\Repositories\PermissionRepository;
use App\Traits\ModuleAccess;
use Illuminate\Http\Request;

/**
 * ModuleName: System Settings
 * Purpose: Manage permission-level enable/disable settings
 * 
 * This controller handles the system-level permission settings that control
 * which features/permissions are enabled or disabled. When a permission is 
 * disabled, it won't be available for assignment in roles and won't work even 
 * if assigned.
 * 
 * @author AI Assistant
 * @date 2025-12-04
 */
class ModuleSettingsController extends Controller
{
    use ModuleAccess;

    protected $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Display module settings page
     * Shows all permissions grouped by module, exactly like roles edit page
     */
    public function index()
    {
        // Get all active permissions (same as RoleController)
        $permissions = $this->permissionRepository->getActive();
        
        // Get currently enabled permissions from system settings
        $enabledPermissions = $this->getEnabledPermissions();
        
        return view('admin.module-settings.index', compact('permissions', 'enabledPermissions'));
    }

    /**
     * Update module settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $selectedPermissions = $request->input('permissions', []);
        $allPermissions = $this->permissionRepository->getActive();

        // Save enabled/disabled status for each permission
        foreach ($allPermissions as $permission) {
            $key = "permission_{$permission->slug}_enabled";
            $enabled = in_array($permission->id, $selectedPermissions);
            
            SystemSetting::set($key, $enabled ? 'true' : 'false', 'boolean', 'permissions');
        }

        // Clear caches
        $this->clearModuleCache();
        SystemSetting::clearCache();

        return redirect()
            ->route('admin.module-settings.index')
            ->with('success', 'Permission settings updated successfully!');
    }

    /**
     * Get list of enabled permission IDs
     */
    private function getEnabledPermissions(): array
    {
        $allPermissions = $this->permissionRepository->getActive();
        $enabled = [];

        foreach ($allPermissions as $permission) {
            $key = "permission_{$permission->slug}_enabled";
            // Default to true if not set
            if (SystemSetting::get($key, true)) {
                $enabled[] = $permission->id;
            }
        }

        return $enabled;
    }

    /**
     * Get all available modules with their current status
     */
    private function getModules(): array
    {
        $modulesList = [
            [
                'key' => 'user',
                'name' => 'User Management',
                'description' => 'Manage users, roles, and permissions',
                'icon' => 'users',
                'category' => 'core',
            ],
            [
                'key' => 'product',
                'name' => 'Product Management',
                'description' => 'Products, categories, brands, attributes, Q&A, and reviews',
                'icon' => 'box',
                'category' => 'ecommerce',
            ],
            [
                'key' => 'order',
                'name' => 'Order Management',
                'description' => 'Orders, customers, and coupons',
                'icon' => 'shopping-cart',
                'category' => 'ecommerce',
            ],
            [
                'key' => 'delivery',
                'name' => 'Delivery Management',
                'description' => 'Delivery zones, methods, and rates',
                'icon' => 'truck',
                'category' => 'ecommerce',
            ],
            [
                'key' => 'stock',
                'name' => 'Stock Management',
                'description' => 'Inventory, warehouses, and suppliers',
                'icon' => 'warehouse',
                'category' => 'ecommerce',
            ],
            [
                'key' => 'blog',
                'name' => 'Blog',
                'description' => 'Blog posts, categories, tags, and comments',
                'icon' => 'file-alt',
                'category' => 'content',
            ],
            [
                'key' => 'content',
                'name' => 'Content Management',
                'description' => 'Homepage, banners, menus, and footer',
                'icon' => 'layout',
                'category' => 'content',
            ],
            [
                'key' => 'reports',
                'name' => 'Reports & Analytics',
                'description' => 'Sales, inventory, product, and customer reports',
                'icon' => 'chart-line',
                'category' => 'analytics',
            ],
            [
                'key' => 'finance',
                'name' => 'Finance',
                'description' => 'Payment gateways, transactions, and finance reports',
                'icon' => 'dollar-sign',
                'category' => 'finance',
            ],
            [
                'key' => 'system',
                'name' => 'System Settings',
                'description' => 'Site settings, cache, maintenance, and logs',
                'icon' => 'cog',
                'category' => 'core',
            ],
            [
                'key' => 'feedback',
                'name' => 'Customer Feedback',
                'description' => 'Manage customer feedback and testimonials',
                'icon' => 'star',
                'category' => 'engagement',
            ],
            [
                'key' => 'appointments',
                'name' => 'Appointments',
                'description' => 'Appointment scheduling and chamber management',
                'icon' => 'calendar-check',
                'category' => 'engagement',
            ],
        ];

        // Add current enabled status
        foreach ($modulesList as &$module) {
            $module['enabled'] = $this->isModuleEnabled($module['key']);
        }

        return $modulesList;
    }
}

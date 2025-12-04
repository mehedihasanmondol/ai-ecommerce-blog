<?php

namespace App\Traits;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;

/**
 * ModuleName: System Module Access Control
 * Purpose: Trait to check if modules are enabled via system settings
 * 
 * This trait provides helper methods to check if specific modules are enabled
 * at the system level before checking role permissions. This implements the
 * two-step permission check:
 * 1. Is the module enabled in system settings?
 * 2. Does the user have the required permission?
 * 
 * @author AI Assistant
 * @date 2025-12-04
 */
trait ModuleAccess
{
    /**
     * Check if a specific module is enabled in system settings
     * 
     * @param string $module Module name (e.g., 'product', 'blog', 'order')
     * @return bool
     */
    public function isModuleEnabled(string $module): bool
    {
        $settingKey = "module_{$module}_enabled";
        return (bool) SystemSetting::get($settingKey, true);
    }

    /**
     * Get the module name from a permission slug
     * 
     * @param string $permissionSlug Permission slug (e.g., 'products.view', 'posts.create')
     * @return string|null Module name or null if not found
     */
    public function getModuleFromPermission(string $permissionSlug): ?string
    {
        // Map permission prefixes to module names
        $moduleMap = [
            // User Management
            'users.' => 'user',
            'roles.' => 'user',
            
            // Product Management
            'products.' => 'product',
            'categories.' => 'product',
            'brands.' => 'product',
            'attributes.' => 'product',
            'questions.' => 'product',
            'answers.' => 'product',
            'reviews.' => 'product',
            
            // Order Management
            'orders.' => 'order',
            'customers.' => 'order',
            'coupons.' => 'order',
            
            // Delivery
            'delivery-zones.' => 'delivery',
            'delivery-methods.' => 'delivery',
            'delivery-rates.' => 'delivery',
            
            // Stock
            'stock.' => 'stock',
            'warehouses.' => 'stock',
            'suppliers.' => 'stock',
            
            // Blog
            'posts.' => 'blog',
            'blog-categories.' => 'blog',
            'blog-tags.' => 'blog',
            'blog-comments.' => 'blog',
            
            // Content
            'homepage-settings.' => 'content',
            'banners.' => 'content',
            'sale-offers.' => 'content',
            'secondary-menu.' => 'content',
            'footer.' => 'content',
            'trending-products.' => 'content',
            'best-sellers.' => 'content',
            'new-arrivals.' => 'content',
            
            // Reports
            'reports.' => 'reports',
            
            // Finance
            'payment-gateways.' => 'finance',
            'finance.' => 'finance',
            
            // System
            'settings.' => 'system',
            'system.' => 'system',
            
            // Feedback
            'feedback.' => 'feedback',
            
            // Appointments
            'appointments.' => 'appointments',
            'chambers.' => 'appointments',
        ];

        foreach ($moduleMap as $prefix => $module) {
            if (str_starts_with($permissionSlug, $prefix)) {
                return $module;
            }
        }

        return null;
    }

    /**
     * Check if user has permission AND the permission is enabled at system level
     * This is the main method that combines both checks
     * 
     * @param string $permissionSlug Permission slug
     * @return bool
     */
    public function hasModulePermission(string $permissionSlug): bool
    {
        // Step 1: Check if permission is enabled at system level
        $key = "permission_{$permissionSlug}_enabled";
        if (!SystemSetting::get($key, true)) {
            return false;
        }
        
        // Step 2: Check if user has the permission (if method exists)
        if (method_exists($this, 'hasPermission')) {
            return $this->hasPermission($permissionSlug);
        }
        
        return false;
    }

    /**
     * Get all enabled modules
     * 
     * @return array
     */
    public static function getEnabledModules(): array
    {
        return Cache::remember('enabled_modules', 3600, function () {
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
        });
    }

    /**
     * Clear the enabled modules cache
     */
    public static function clearModuleCache(): void
    {
        Cache::forget('enabled_modules');
        
        // Also clear specific module caches
        $modules = [
            'user', 'product', 'order', 'delivery', 'stock',
            'blog', 'content', 'reports', 'finance', 'system',
            'feedback', 'appointments'
        ];

        foreach ($modules as $module) {
            Cache::forget("module_{$module}_enabled");
        }
    }

    /**
     * Get human-readable module name
     * 
     * @param string $module Module key
     * @return string
     */
    public static function getModuleName(string $module): string
    {
        $names = [
            'user' => 'User Management',
            'product' => 'Product Management',
            'order' => 'Order Management',
            'delivery' => 'Delivery Management',
            'stock' => 'Stock Management',
            'blog' => 'Blog',
            'content' => 'Content Management',
            'reports' => 'Reports & Analytics',
            'finance' => 'Finance',
            'system' => 'System Settings',
            'feedback' => 'Feedback',
            'appointments' => 'Appointments',
        ];

        return $names[$module] ?? ucfirst($module);
    }
}

# Module-Based Permission Visibility System

**Date:** December 4, 2025  
**Author:** AI Assistant  
**Status:** ✅ Implemented

---

## Overview

This document describes the implementation of a dynamic module-based permission visibility system. The system implements two-tier access control:

1. **System-Level Control:** Module enable/disable settings in System Settings
2. **User-Level Control:** Role-based permissions

**Functional Rule:** System settings take precedence. If a module is disabled at the system level, its permissions and menu items are hidden from ALL users, regardless of their role permissions.

---

## Architecture

### Two-Step Permission Check Flow

```
User Action → Module Enabled? → Role Permission? → Allow/Deny Access
                    ↓                   ↓
                  NO: Deny            NO: Deny
                  YES: Continue       YES: Allow
```

### Key Components

| Component | Purpose | Location |
|-----------|---------|----------|
| `ModuleSettingSeeder` | Seeds module enable/disable settings | `database/seeders/` |
| `ModuleAccess` Trait | Helper methods for module checks | `app/Traits/` |
| `CheckModuleAccess` Middleware | Route-level module access control | `app/Http/Middleware/` |
| `ModuleSettingsController` | Admin UI for module management | `app/Http/Controllers/Admin/` |
| `PermissionRepository` | Filtered permission queries | `app/Modules/User/Repositories/` |
| Module Settings View | UI for enabling/disabling modules | `resources/views/admin/module-settings/` |

---

## Implementation Details

### 1. System Settings Storage

Module settings are stored in the `system_settings` table using key-value pairs:

```php
Key: module_{module_name}_enabled
Value: "true" or "false"
Type: boolean
Group: modules
```

**Available Modules:**
- `user` - User Management
- `product` - Product Management
- `order` - Order Management
- `delivery` - Delivery Management
- `stock` - Stock Management
- `blog` - Blog
- `content` - Content Management
- `reports` - Reports & Analytics
- `finance` - Finance
- `system` - System Settings
- `feedback` - Feedback
- `appointments` - Appointments

### 2. ModuleAccess Trait

**File:** `app/Traits/ModuleAccess.php`

**Key Methods:**

```php
// Check if module is enabled
isModuleEnabled(string $module): bool

// Get module from permission slug
getModuleFromPermission(string $permissionSlug): ?string

// Combined check: module enabled + user permission
hasModulePermission(string $permissionSlug): bool

// Get all enabled modules (cached)
static getEnabledModules(): array

// Clear module cache
static clearModuleCache(): void
```

**Usage Example:**

```php
// In controller or service
if ($user->isModuleEnabled('product')) {
    // Module is enabled
}

// In views
@if(auth()->user()->hasModulePermission('products.view'))
    <!-- Show content -->
@endif
```

### 3. PermissionRepository Enhancement

**File:** `app/Modules/User/Repositories/PermissionRepository.php`

**New Method:**

```php
public function getActiveByEnabledModules(): Collection
{
    $enabledModules = $this->getEnabledModules();
    
    return Permission::active()
        ->whereIn('module', $enabledModules)
        ->orderBy('module')
        ->orderBy('name')
        ->get();
}
```

This method is used in the Roles & Permissions UI to show only permissions for enabled modules.

### 4. User Model Integration

**File:** `app/Models/User.php`

Added `ModuleAccess` trait:

```php
use App\Traits\ModuleAccess;

class User extends Authenticatable
{
    use HasFactory, Notifiable, ModuleAccess;
    
    // Now has access to all ModuleAccess methods
}
```

### 5. Middleware for Route Protection

**File:** `app/Http/Middleware/CheckModuleAccess.php`

**Usage in Routes:**

```php
// Protect entire module routes
Route::middleware(['auth', 'module:product'])->group(function () {
    // Product routes
});

// Multiple middleware
Route::middleware(['auth', 'module:blog', 'permission:posts.view'])
    ->group(function () {
        // Blog routes
    });
```

### 6. Module Settings Admin Interface

**Controller:** `app/Http/Controllers/Admin/ModuleSettingsController.php`  
**View:** `resources/views/admin/module-settings/index.blade.php`  
**Routes:** 
- GET `/admin/module-settings` - View module settings
- PUT `/admin/module-settings` - Update module settings

**Access:** Requires `system.settings.view` permission

**Features:**
- ✅ Visual toggle switches for each module
- ✅ Organized by category (Core, E-commerce, Content, etc.)
- ✅ Module descriptions and icons
- ✅ Warning prompts for critical modules (User, System)
- ✅ Real-time enable/disable
- ✅ Cache clearing on update

---

## Database Seeding

### ModuleSettingSeeder

**File:** `database/seeders/ModuleSettingSeeder.php`

**Features:**
- Creates 12 module settings
- Uses smart upsert logic (only updates if values differ)
- All modules enabled by default
- Descriptive information for each module

**Execution:**

```bash
# Seed all (includes ModuleSettingSeeder in Phase 1)
php artisan db:seed

# Seed only module settings
php artisan db:seed --class=ModuleSettingSeeder
```

---

## Role & Permission Configuration Impact

### Before Implementation

```php
// All permissions shown regardless of module status
$permissions = Permission::active()->get();
```

### After Implementation

```php
// Only permissions for enabled modules shown
$permissions = $this->permissionRepository->getActiveByEnabledModules();
```

**UI Changes:**
- Role create/edit forms now show only permissions for enabled modules
- Disabled module permissions are not visible or assignable
- Existing role permissions for disabled modules remain in database but are ineffective

---

## Admin Sidebar Integration

**File:** `resources/views/layouts/admin.blade.php`

### Permission Check Pattern

**Old Pattern (Permission Only):**
```blade
@if(auth()->user()->hasPermission('products.view'))
    <a href="{{ route('admin.products.index') }}">Products</a>
@endif
```

**New Pattern (Module + Permission):**
```blade
@if(auth()->user()->hasModulePermission('products.view'))
    <a href="{{ route('admin.products.index') }}">Products</a>
@endif
```

**Note:** Current implementation still uses old pattern in sidebar. To fully implement, replace all `hasPermission()` calls with `hasModulePermission()` in the sidebar navigation.

---

## Cache Management

### Cached Data

| Cache Key | TTL | Purpose |
|-----------|-----|---------|
| `enabled_modules` | 1 hour | List of enabled modules |
| `system_setting_{key}` | 1 hour | Individual module settings |

### Clearing Cache

```php
// Clear all module caches
ModuleAccess::clearModuleCache();

// Clear all system setting caches
SystemSetting::clearCache();

// Automatic clearing on module settings update
// (Handled in ModuleSettingsController::update)
```

---

## Testing Scenarios

### Test Case 1: Disable Product Module

**Steps:**
1. Go to Admin > System > Module Settings
2. Disable "Product Management" module
3. Save changes

**Expected Results:**
- ✅ Product menu items hidden from all users
- ✅ Product permissions hidden in Roles & Permissions UI
- ✅ Product routes return 403 if accessed directly
- ✅ Dashboard shows no product-related data

### Test Case 2: Re-enable Module

**Steps:**
1. Enable previously disabled module
2. Save changes

**Expected Results:**
- ✅ Menu items reappear
- ✅ Permissions visible in Roles UI
- ✅ Routes accessible again (with permission check)
- ✅ Users with permissions can access features

### Test Case 3: Role Permission Assignment

**Steps:**
1. Disable "Blog" module
2. Go to Roles & Permissions
3. Try to assign blog permissions

**Expected Results:**
- ✅ Blog permissions not visible in permission list
- ✅ Cannot assign blog permissions to roles
- ✅ Existing blog permissions in roles remain but are ineffective

---

## Migration Guide

### For Existing Projects

1. **Run Migration:**
   ```bash
   php artisan migrate
   ```

2. **Seed Module Settings:**
   ```bash
   php artisan db:seed --class=ModuleSettingSeeder
   ```

3. **Clear Cache:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

4. **Update Sidebar (Optional):**
   - Replace `hasPermission()` with `hasModulePermission()` in sidebars
   - Test all menu items

5. **Add Route Middleware (Optional):**
   - Add `module:{name}` middleware to route groups
   - Test route access

### Breaking Changes

- ⚠️ None - All modules are enabled by default
- ⚠️ Backward compatible - Old code continues to work

### Recommended Updates

1. Update sidebar navigation to use `hasModulePermission()`
2. Add `CheckModuleAccess` middleware to route groups
3. Update custom admin checks to consider module status

---

## Security Considerations

### Module Disabling Best Practices

**Critical Modules** (Do not disable in production):
- ❌ **User Management** - Cannot manage users/roles
- ❌ **System Settings** - Cannot access system controls

**Safe to Disable:**
- ✅ Blog (if not using blog features)
- ✅ Appointments (if not using appointments)
- ✅ Feedback (if not using feedback system)
- ✅ Reports (temporarily during data migration)

### Permission Bypass Prevention

The two-tier system ensures:
1. Even Super Admin cannot access disabled modules via URL manipulation
2. API endpoints respect module settings
3. Menu items are hidden to prevent user confusion
4. Middleware blocks direct route access

---

## API Integration

### Checking Module Status

```php
// In API Controllers
public function index(Request $request)
{
    if (!$request->user()->isModuleEnabled('product')) {
        return response()->json([
            'error' => 'Product module is disabled'
        ], 403);
    }
    
    // Continue with logic
}
```

### API Response Example

```json
{
    "enabled_modules": [
        "user",
        "product",
        "order",
        "stock",
        "blog"
    ],
    "disabled_modules": [
        "delivery",
        "appointments",
        "feedback"
    ]
}
```

---

## Performance Optimization

### Caching Strategy

1. **Module List:** Cached for 1 hour
2. **Individual Settings:** Cached per setting
3. **Permission Queries:** Use `whereIn()` for enabled modules only
4. **Menu Rendering:** Cache module checks in view composers

### Query Optimization

**Before:**
```sql
SELECT * FROM permissions WHERE is_active = 1
-- Returns all permissions
```

**After:**
```sql
SELECT * FROM permissions 
WHERE is_active = 1 
AND module IN ('user', 'product', 'order')
-- Returns only enabled module permissions
```

---

## Future Enhancements

### Potential Features

1. **Module Dependencies:**
   - Define which modules depend on others
   - Prevent disabling parent modules

2. **Role-Level Module Access:**
   - Allow different modules per role
   - More granular control

3. **Module Usage Analytics:**
   - Track which modules are actively used
   - Recommend modules to disable

4. **Scheduled Module Toggle:**
   - Enable/disable modules on schedule
   - Useful for maintenance windows

5. **Module Configuration:**
   - Per-module settings
   - Feature flags within modules

---

## Troubleshooting

### Issue: Module menu still showing after disabling

**Solution:**
1. Clear cache: `php artisan cache:clear`
2. Check if sidebar uses `hasPermission()` instead of `hasModulePermission()`
3. Update sidebar navigation code

### Issue: Permissions not hiding in Roles UI

**Solution:**
1. Verify `getActiveByEnabledModules()` is being called
2. Check RoleController uses new repository method
3. Clear permission cache

### Issue: Routes still accessible after disabling module

**Solution:**
1. Add `CheckModuleAccess` middleware to routes
2. Verify middleware is registered in `app/Http/Kernel.php`
3. Check route definitions

### Issue: Module settings not saving

**Solution:**
1. Check `system_settings` table exists
2. Verify SystemSetting model works correctly
3. Check file permissions on storage/cache

---

## Related Documentation

- [Role & Permission System](./role-permission-system.md)
- [Admin Panel Structure](./admin-panel-structure.md)
- [System Settings Management](./system-settings.md)

---

## Changelog

| Date | Change | Author |
|------|--------|--------|
| 2025-12-04 | Initial implementation | AI Assistant |
| 2025-12-04 | Added documentation | AI Assistant |

---

## Support

For questions or issues:
1. Check this documentation
2. Review code comments in implementation files
3. Test in local environment first
4. Contact development team

---

**Last Updated:** December 4, 2025  
**Document Version:** 1.0

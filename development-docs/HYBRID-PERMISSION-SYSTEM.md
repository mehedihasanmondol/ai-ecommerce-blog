# Hybrid Permission System Documentation

## Overview
The hybrid permission system allows menu items and features to check for specific permissions first, then fall back to inherited/parent permissions if the specific permission doesn't exist in the database.

## Purpose
- **Granular Control**: Use specific permissions when they exist (e.g., `roles.view`, `orders.view`)
- **Graceful Fallback**: Automatically fall back to broader permissions when specific ones aren't defined (e.g., fall back to `users.view` if `roles.view` doesn't exist)
- **Future-Proof**: Add new specific permissions without breaking existing functionality

## Implementation

### 1. Core Method: `canAccess()`

Location: `app/Models/User.php`

```php
/**
 * Hybrid permission check: tries specific permission first, then falls back to inherited
 * 
 * @param string $specificPermission The specific permission to check (e.g., 'roles.view')
 * @param string|array $fallbackPermissions Fallback permission(s) if specific doesn't exist (e.g., 'users.view')
 * @return bool
 */
public function canAccess(string $specificPermission, $fallbackPermissions = null): bool
{
    // First, check if the specific permission exists in the system
    $permissionExists = \App\Models\Permission::where('slug', $specificPermission)
        ->where('is_active', true)
        ->exists();
    
    // If specific permission exists, use it
    if ($permissionExists) {
        return $this->hasPermission($specificPermission);
    }
    
    // If specific permission doesn't exist, check fallback permission(s)
    if ($fallbackPermissions) {
        // Handle single fallback or array of fallbacks
        $fallbacks = is_array($fallbackPermissions) ? $fallbackPermissions : [$fallbackPermissions];
        
        foreach ($fallbacks as $fallback) {
            if ($this->hasPermission($fallback)) {
                return true;
            }
        }
    }
    
    return false;
}
```

### 2. Usage in Views

#### Single Fallback
```blade
@if(auth()->user()->canAccess('roles.view', 'users.view'))
    <a href="{{ route('admin.roles.index') }}">Roles & Permissions</a>
@endif
```

#### Multiple Fallbacks
```blade
@if(auth()->user()->canAccess('contact.view', ['users.view', 'communication.view']))
    <a href="{{ route('admin.contact.messages.index') }}">Contact Messages</a>
@endif
```

#### Section-Level Check
```blade
@if(auth()->user()->hasPermission('products.view'))
<div class="pt-4 pb-2">
    <p>E-commerce</p>
</div>

    {{-- Products always visible if section is visible --}}
    <a href="{{ route('admin.products.index') }}">Products</a>
    
    {{-- Orders has its own specific check --}}
    @if(auth()->user()->canAccess('orders.view', 'products.view'))
    <a href="{{ route('admin.orders.index') }}">Orders</a>
    @endif
@endif
```

### 3. Usage in Controllers

```php
public function index()
{
    // Check with fallback
    abort_if(
        !auth()->user()->canAccess('roles.view', 'users.view'), 
        403, 
        'You do not have permission to view roles.'
    );
    
    // ... controller logic
}
```

## Current Menu Implementation

### User Management Section
| Menu Item | Specific Permission | Fallback Permission | Notes |
|-----------|-------------------|-------------------|-------|
| Section Visibility | `users.view` | None | Section shown if base permission exists |
| Users | Inherits section | - | Always visible with section |
| Roles & Permissions | `roles.view` | `users.view` | ✅ Hybrid check |
| Email Preferences | `email-preferences.view` | `users.view` | ✅ Hybrid check |

### E-commerce Section
| Menu Item | Specific Permission | Fallback Permission | Notes |
|-----------|-------------------|-------------------|-------|
| Section Visibility | `products.view` | None | Section shown if base permission exists |
| Products | Inherits section | - | Always visible with section |
| Orders | `orders.view` | `products.view` | ✅ Hybrid check |
| Reports & Analytics | `reports.view` | `products.view` | ✅ Hybrid check |
| Categories | Inherits section | - | Always visible with section |
| Brands | Inherits section | - | Always visible with section |
| Attributes | Inherits section | - | Always visible with section |
| Product Q&A | Inherits section | - | Always visible with section |
| Product Reviews | Inherits section | - | Always visible with section |

### Shipping & Delivery Section
| Menu Item | Specific Permission | Fallback Permission | Notes |
|-----------|-------------------|-------------------|-------|
| Section Visibility | `orders.view` | None | Linked to orders |
| Coupons | Inherits section | - | Always visible with section |
| Delivery Zones | Inherits section | - | Always visible with section |
| Delivery Methods | Inherits section | - | Always visible with section |
| Delivery Rates | Inherits section | - | Always visible with section |

### Payments Section
| Menu Item | Specific Permission | Fallback Permission | Notes |
|-----------|-------------------|-------------------|-------|
| Section Visibility | `orders.view` | None | Linked to orders |
| Payment Gateways | Inherits section | - | Always visible with section |

### Inventory Section
| Menu Item | Specific Permission | Fallback Permission | Notes |
|-----------|-------------------|-------------------|-------|
| Section Visibility | `stock.view` | None | Section shown if base permission exists |
| Stock Management | Inherits section | - | Always visible with section |
| Warehouses | Inherits section | - | Always visible with section |
| Suppliers | Inherits section | - | Always visible with section |
| Stock Reports | Inherits section | - | Always visible with section |

### Content Section
| Menu Item | Specific Permission | Fallback Permission | Notes |
|-----------|-------------------|-------------------|-------|
| Section Visibility | `users.view` | None | Currently broad permission |
| Homepage Settings | Inherits section | - | Can add `content.view` later |
| Secondary Menu | Inherits section | - | Can add `content.view` later |
| Sale Offers | Inherits section | - | Can add `content.view` later |
| Trending Products | Inherits section | - | Can add `content.view` later |
| Best Sellers | Inherits section | - | Can add `content.view` later |
| New Arrivals | Inherits section | - | Can add `content.view` later |
| Footer Management | Inherits section | - | Can add `content.view` later |

### Blog Section
| Menu Item | Specific Permission | Fallback Permission | Notes |
|-----------|-------------------|-------------------|-------|
| Section Visibility | `posts.view` | None | Section shown if base permission exists |
| Posts | Inherits section | - | Always visible with section |
| Categories | Inherits section | - | Always visible with section |
| Tags | Inherits section | - | Always visible with section |
| Comments | Inherits section | - | Always visible with section |

### Feedback Section
| Menu Item | Specific Permission | Fallback Permission | Notes |
|-----------|-------------------|-------------------|-------|
| Section Visibility | `feedback.view` | None | Section shown if base permission exists |
| Customer Feedback | Inherits section | - | Always visible with section |

### Appointments Section
| Menu Item | Specific Permission | Fallback Permission | Notes |
|-----------|-------------------|-------------------|-------|
| Section Visibility | `appointments.view` OR `chambers.manage` | None | Multiple permissions |
| Appointments | `appointments.view` | None | Specific check |
| Chambers | `chambers.manage` | None | Specific check |

### Finance Section
| Menu Item | Specific Permission | Fallback Permission | Notes |
|-----------|-------------------|-------------------|-------|
| Section Visibility | `finance.view` | None | Section shown if base permission exists |
| Transactions | Coming soon | - | Placeholder |
| Reports | Coming soon | - | Placeholder |

### Communication Section
| Menu Item | Specific Permission | Fallback Permission | Notes |
|-----------|-------------------|-------------------|-------|
| Section Visibility | `contact.view` | `users.view` | ✅ Hybrid check |
| Contact Messages | Inherits section | - | Always visible with section |

### System Section
| Menu Item | Specific Permission | Fallback Permission | Notes |
|-----------|-------------------|-------------------|-------|
| Section Visibility | `users.view` OR `system.settings.view` | None | Multiple permissions |
| Site Settings | `settings.view` | `users.view` | ✅ Hybrid check |
| Theme Settings | `settings.view` | `users.view` | ✅ Hybrid check |
| System Settings | `system.settings.view` | None | Specific permission required |
| Permission Settings | `system.settings.view` | None | Specific permission required |

## Benefits

### 1. Backward Compatibility
- Existing systems continue to work with broad permissions
- No need to immediately create all specific permissions

### 2. Progressive Enhancement
- Add specific permissions as needed
- Automatically use them when they exist
- No code changes required when adding new permissions

### 3. Granular Control
- Fine-grained access control where it matters
- Broader access where specifics aren't needed
- Flexible permission assignment

## Adding New Specific Permissions

### Step 1: Create Permission in Seeder
```php
// database/seeders/RolePermissionSeeder.php
'roles.view' => 'View Roles',
'roles.create' => 'Create Roles',
'roles.edit' => 'Edit Roles',
'roles.delete' => 'Delete Roles',
```

### Step 2: Assign to Roles
```php
$superAdminRole->permissions()->attach([
    Permission::where('slug', 'roles.view')->first()->id,
    Permission::where('slug', 'roles.create')->first()->id,
    // ... etc
]);
```

### Step 3: System Automatically Uses It
The `canAccess()` method will automatically detect the new permission exists and use it instead of the fallback. **No code changes needed!**

## Best Practices

### 1. Choose Meaningful Fallbacks
```blade
{{-- Good: Related parent permission --}}
@if(auth()->user()->canAccess('roles.view', 'users.view'))

{{-- Bad: Unrelated fallback --}}
@if(auth()->user()->canAccess('roles.view', 'products.view'))
```

### 2. Use Section-Level Permissions
```blade
{{-- Check section permission once --}}
@if(auth()->user()->hasPermission('products.view'))
    {{-- Most items inherit --}}
    <a href="{{ route('admin.products.index') }}">Products</a>
    <a href="{{ route('admin.categories.index') }}">Categories</a>
    
    {{-- Critical items get their own check --}}
    @if(auth()->user()->canAccess('orders.view', 'products.view'))
    <a href="{{ route('admin.orders.index') }}">Orders</a>
    @endif
@endif
```

### 3. Document Permission Hierarchy
Keep a clear hierarchy:
- `users.view` → `roles.view`, `email-preferences.view`
- `products.view` → `orders.view`, `reports.view`
- `users.view` → `settings.view`, `contact.view`

## Migration Path

### Current State (Before Hybrid)
```blade
@if(auth()->user()->hasPermission('users.view'))
    <a href="{{ route('admin.roles.index') }}">Roles</a>
@endif
```
**Problem**: Can't give someone role management without full user management access.

### Hybrid State (Now)
```blade
@if(auth()->user()->canAccess('roles.view', 'users.view'))
    <a href="{{ route('admin.roles.index') }}">Roles</a>
@endif
```
**Solution**: 
- Works with `users.view` permission (backward compatible)
- Automatically uses `roles.view` when it exists (forward compatible)

### Future State (When All Permissions Exist)
```blade
@if(auth()->user()->canAccess('roles.view', 'users.view'))
    <a href="{{ route('admin.roles.index') }}">Roles</a>
@endif
```
**Result**: 
- System uses `roles.view` permission
- Granular control achieved
- **Same code, no changes needed!**

## Testing

### Test Scenarios

#### Scenario 1: Specific Permission Exists
```php
// Given: roles.view permission exists
// When: User has roles.view
// Then: canAccess('roles.view', 'users.view') returns true

// When: User has only users.view
// Then: canAccess('roles.view', 'users.view') returns false
```

#### Scenario 2: Specific Permission Doesn't Exist
```php
// Given: roles.view permission doesn't exist
// When: User has users.view
// Then: canAccess('roles.view', 'users.view') returns true

// When: User has neither
// Then: canAccess('roles.view', 'users.view') returns false
```

#### Scenario 3: Multiple Fallbacks
```php
// Given: contact.view doesn't exist
// When: User has users.view
// Then: canAccess('contact.view', ['users.view', 'communication.view']) returns true

// When: User has communication.view
// Then: canAccess('contact.view', ['users.view', 'communication.view']) returns true
```

## Summary

The hybrid permission system provides:
- ✅ **Flexibility**: Works with existing broad permissions
- ✅ **Scalability**: Easy to add specific permissions
- ✅ **Maintainability**: No code changes when adding permissions
- ✅ **Security**: Proper permission checks at all levels
- ✅ **Future-Proof**: Automatically uses new permissions when available

This approach allows your permission system to evolve naturally without breaking existing functionality or requiring massive code refactoring.

# Permission Security Audit - Critical Issues Found

## 🚨 Critical Security Issue
**User Report:** Admin with disabled `orders.create` permission can still:
1. See "Create Order" button
2. Access `/admin/orders/create` route
3. Potentially create orders

## Root Cause
1. **Routes**: Using single permission (`orders.view`) for ALL CRUD operations
2. **Views**: Not checking permissions before showing action buttons

---

## 📋 Affected Modules

### ❌ CRITICAL - Order Management (Lines 75-91)
**Current:** All routes use `permission:orders.view`
**Issue:**
- `orders/create` → should require `orders.create`
- `orders/{id}/edit` → should require `orders.edit`
- `orders (POST)` → should require `orders.create`
- `orders/{id} (PUT)` → should require `orders.edit`
- `orders/{id} (DELETE)` → should require `orders.delete`
- `orders/{id}/update-status` → should require `orders.update-status`
- `orders/{id}/cancel` → should require `orders.cancel`
- `orders/{id}/invoice` → should require `orders.invoice`

**Permissions Exist:** ✅ All defined in RolePermissionSeeder.php

---

### ❌ CRITICAL - User Management (Lines 43-47)
**Current:** All routes use `permission:users.view`
**Issue:**
- Resource controller includes create, store, edit, update, destroy
- Missing granular permissions for create/edit/delete

**Required Permissions:**
- `users.view` - index, show
- `users.create` - create, store  
- `users.edit` - edit, update
- `users.delete` - destroy
- `users.toggle-status` - toggle-status

---

### ❌ CRITICAL - Role Management (Lines 50-52)
**Current:** All routes use `permission:roles.view`
**Issue:** Same as User Management

**Required Permissions:**
- `roles.view` - index, show
- `roles.create` - create, store
- `roles.edit` - edit, update
- `roles.delete` - destroy

---

### ❌ HIGH - Category Management (Lines 55-61)
**Current:** All routes use `permission:products.view`
**Issue:** Categories have their own CRUD, should have dedicated permissions

**Required Permissions:**
- `categories.view` - index, show
- `categories.create` - create, store
- `categories.edit` - edit, update, toggle-status, duplicate
- `categories.delete` - destroy

---

### ❌ HIGH - Brand Management (Lines 64-72)
**Current:** All routes use `permission:products.view`
**Issue:** Same as categories

**Required Permissions:**
- `brands.view` - index, show
- `brands.create` - create, store
- `brands.edit` - edit, update, toggle-status, toggle-featured, duplicate
- `brands.delete` - destroy

---

### ❌ HIGH - Delivery Management (Lines 215-230)
**Current:** All routes use `permission:orders.view`
**Issue:** Delivery zones/methods/rates have full CRUD with wrong permission

**Required Permissions:**
- `delivery.view` - index, show
- `delivery.create` - create, store
- `delivery.edit` - edit, update, toggle-status
- `delivery.delete` - destroy

---

### ❌ HIGH - Stock Management (Lines 260-274)
**Current:** All routes use `permission:stock.view`
**Issue:** Stock movements (add, remove, adjust, transfer) need separate permissions

**Required Permissions:**
- `stock.view` - index, movements, current-stock
- `stock.add` - add (create/store)
- `stock.remove` - remove (create/store)
- `stock.adjust` - adjust (create/store)
- `stock.transfer` - transfer (create/store)
- `stock.alerts` - alerts, resolve

---

### ❌ HIGH - Warehouse Management (Lines 277-280)
**Current:** All routes use `permission:stock.view`
**Issue:** Uses resource controller

**Required Permissions:**
- `warehouses.view` - index, show
- `warehouses.create` - create, store
- `warehouses.edit` - edit, update, set-default
- `warehouses.delete` - destroy

---

### ❌ HIGH - Supplier Management (Lines 283-285)
**Current:** All routes use `permission:stock.view`
**Issue:** Same as warehouses

**Required Permissions:**
- `suppliers.view` - index, show
- `suppliers.create` - create, store
- `suppliers.edit` - edit, update
- `suppliers.delete` - destroy

---

### ❌ MEDIUM - Product Questions (Lines 134-141)
**Current:** All routes use `permission:products.view`
**Issue:** Approval/rejection should have separate permission

**Required Permissions:**
- `product-questions.view` - index, show
- `product-questions.edit` - edit, update
- `product-questions.delete` - destroy
- `product-questions.moderate` - approve, reject, mark best answer

---

### ❌ MEDIUM - Product Reviews (Lines 144-153)
**Current:** All routes use `permission:products.view`
**Issue:** Same as questions

**Required Permissions:**
- `reviews.view` - index, show, pending
- `reviews.moderate` - approve, reject
- `reviews.delete` - destroy, bulk-delete

---

### ❌ MEDIUM - Site Settings (Lines 241-246)
**Current:** All routes use `permission:settings.view`
**Issue:** Update routes should require separate permission

**Required Permissions:**
- `settings.view` - index
- `settings.edit` - update, updateGroup, removeLogo

---

### ✅ GOOD - Feedback Management (Lines 177-196)
**Status:** CORRECTLY IMPLEMENTED
- Has granular permissions for approve, reject, feature, delete
- Each action properly protected

### ✅ GOOD - Chambers Management (Lines 204-212)
**Status:** ACCEPTABLE
- Uses single `chambers.manage` permission
- Can be improved with granular permissions if needed

---

## 🎯 Fix Strategy

### Phase 1: Add Missing Permissions to Seeder
Add all required permissions to `RolePermissionSeeder.php`

### Phase 2: Fix Routes
Update `routes/admin.php` with proper permission middleware for each action

### Phase 3: Fix Views
Add permission checks to ALL action buttons:
- Create buttons → `@can('module.create')`
- Edit buttons → `@can('module.edit')`
- Delete buttons → `@can('module.delete')`
- Special actions → `@can('module.action')`

### Phase 4: Fix web.php Routes
Check and fix product management routes in web.php

---

## 📊 Priority Matrix

| Module | Severity | Impact | Files to Fix |
|--------|----------|--------|-------------|
| Orders | CRITICAL | HIGH | admin.php (routes), index.blade.php (view) |
| Users | CRITICAL | HIGH | admin.php, index view |
| Roles | CRITICAL | HIGH | admin.php, index view |
| Delivery | HIGH | MEDIUM | admin.php, all delivery views |
| Stock | HIGH | MEDIUM | admin.php, all stock views |
| Warehouses | HIGH | MEDIUM | admin.php, warehouse views |
| Suppliers | HIGH | MEDIUM | admin.php, supplier views |
| Categories | HIGH | LOW | admin.php, category views |
| Brands | HIGH | LOW | admin.php, brand views |
| Settings | MEDIUM | MEDIUM | admin.php, settings views |
| Questions | MEDIUM | LOW | admin.php, question views |
| Reviews | MEDIUM | LOW | admin.php, review views |

---

## ✅ Implementation Checklist

- [ ] Add missing permissions to RolePermissionSeeder.php
- [ ] Fix Order routes (CRITICAL)
- [ ] Fix Order views (CRITICAL)
- [ ] Fix User routes
- [ ] Fix User views
- [ ] Fix Role routes
- [ ] Fix Role views
- [ ] Fix Delivery routes
- [ ] Fix Delivery views
- [ ] Fix Stock routes
- [ ] Fix Stock views
- [ ] Fix Warehouse routes
- [ ] Fix Warehouse views
- [ ] Fix Supplier routes
- [ ] Fix Supplier views
- [ ] Fix Category routes
- [ ] Fix Category views
- [ ] Fix Brand routes
- [ ] Fix Brand views
- [ ] Fix Settings routes
- [ ] Fix Settings views
- [ ] Test all permissions with disabled state

---

## 📝 Notes
- Feedback module is the BEST example of proper permission implementation
- Always use middleware at route level AND `@can()` in views
- Each CRUD action should have its own permission check
- Resource controllers need multiple permission checks

---

Last Updated: {{ date }}
Status: AUDIT COMPLETE - READY FOR FIX

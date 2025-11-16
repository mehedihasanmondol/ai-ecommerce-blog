<?php

namespace Database\Seeders;

use App\Modules\User\Models\Permission;
use App\Modules\User\Models\Role;
use Illuminate\Database\Seeder;

/**
 * ModuleName: User Management
 * Purpose: Seed initial roles and permissions
 * 
 * @author AI Assistant
 * @date 2025-11-04
 */
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            // User Management
            ['name' => 'View Users', 'slug' => 'users.view', 'module' => 'user'],
            ['name' => 'Create Users', 'slug' => 'users.create', 'module' => 'user'],
            ['name' => 'Edit Users', 'slug' => 'users.edit', 'module' => 'user'],
            ['name' => 'Delete Users', 'slug' => 'users.delete', 'module' => 'user'],
            
            // Role Management
            ['name' => 'View Roles', 'slug' => 'roles.view', 'module' => 'user'],
            ['name' => 'Create Roles', 'slug' => 'roles.create', 'module' => 'user'],
            ['name' => 'Edit Roles', 'slug' => 'roles.edit', 'module' => 'user'],
            ['name' => 'Delete Roles', 'slug' => 'roles.delete', 'module' => 'user'],
            
            // Product Management
            ['name' => 'View Products', 'slug' => 'products.view', 'module' => 'product'],
            ['name' => 'Create Products', 'slug' => 'products.create', 'module' => 'product'],
            ['name' => 'Edit Products', 'slug' => 'products.edit', 'module' => 'product'],
            ['name' => 'Delete Products', 'slug' => 'products.delete', 'module' => 'product'],
            
            // Order Management
            ['name' => 'View Orders', 'slug' => 'orders.view', 'module' => 'order'],
            ['name' => 'Create Orders', 'slug' => 'orders.create', 'module' => 'order'],
            ['name' => 'Edit Orders', 'slug' => 'orders.edit', 'module' => 'order'],
            ['name' => 'Delete Orders', 'slug' => 'orders.delete', 'module' => 'order'],
            
            // Stock Management
            ['name' => 'View Stock', 'slug' => 'stock.view', 'module' => 'stock'],
            ['name' => 'Manage Stock', 'slug' => 'stock.manage', 'module' => 'stock'],
            
            // Finance Management
            ['name' => 'View Finance', 'slug' => 'finance.view', 'module' => 'finance'],
            ['name' => 'Manage Finance', 'slug' => 'finance.manage', 'module' => 'finance'],
            
            // Blog Management
            ['name' => 'View Posts', 'slug' => 'posts.view', 'module' => 'blog'],
            ['name' => 'Create Posts', 'slug' => 'posts.create', 'module' => 'blog'],
            ['name' => 'Edit Posts', 'slug' => 'posts.edit', 'module' => 'blog'],
            ['name' => 'Delete Posts', 'slug' => 'posts.delete', 'module' => 'blog'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Create Roles
        $adminRole = Role::create([
            'name' => 'Super Admin',
            'slug' => 'super-admin',
            'description' => 'Full system access with all permissions',
            'is_active' => true,
        ]);

        $managerRole = Role::create([
            'name' => 'Manager',
            'slug' => 'manager',
            'description' => 'Manage products, orders, and stock',
            'is_active' => true,
        ]);

        $editorRole = Role::create([
            'name' => 'Content Editor',
            'slug' => 'editor',
            'description' => 'Manage blog posts and content',
            'is_active' => true,
        ]);

        $authorRole = Role::create([
            'name' => 'Author',
            'slug' => 'author',
            'description' => 'Write and manage own blog posts',
            'is_active' => true,
        ]);

        $customerRole = Role::create([
            'name' => 'Customer',
            'slug' => 'customer',
            'description' => 'Regular customer with basic access',
            'is_active' => true,
        ]);

        // Assign all permissions to Super Admin
        $adminRole->permissions()->attach(Permission::all());

        // Assign specific permissions to Manager
        $managerPermissions = Permission::whereIn('module', ['product', 'order', 'stock'])->get();
        $managerRole->permissions()->attach($managerPermissions);

        // Assign specific permissions to Editor
        $editorPermissions = Permission::where('module', 'blog')->get();
        $editorRole->permissions()->attach($editorPermissions);

        // Assign blog permissions to Author (create and edit own posts)
        $authorPermissions = Permission::whereIn('slug', ['posts.view', 'posts.create', 'posts.edit'])->get();
        $authorRole->permissions()->attach($authorPermissions);

        $this->command->info('Roles and permissions seeded successfully!');
    }
}

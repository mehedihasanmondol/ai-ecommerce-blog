<?php

/**
 * Quick script to assign admin role to user
 * Run with: php artisan tinker < fix-admin-user.php
 */

use App\Models\User;
use App\Modules\User\Models\Role;

// Get user ID 1 (your current user)
$user = User::find(1);

if (!$user) {
    echo "User not found!\n";
    exit;
}

echo "Found user: {$user->name} ({$user->email})\n";

// Update user role to admin
$user->update(['role' => 'admin', 'is_active' => true]);
echo "✓ Updated user role to 'admin'\n";

// Get Super Admin role
$superAdminRole = Role::where('slug', 'super-admin')->first();

if ($superAdminRole) {
    // Assign Super Admin role
    $user->roles()->syncWithoutDetaching([$superAdminRole->id]);
    echo "✓ Assigned 'Super Admin' role\n";
} else {
    echo "⚠ Super Admin role not found\n";
}

echo "\n✅ User setup complete!\n";
echo "User: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "Role: {$user->role}\n";
echo "Active: " . ($user->is_active ? 'Yes' : 'No') . "\n";
echo "Additional Roles: " . $user->roles->pluck('name')->join(', ') . "\n";

exit;

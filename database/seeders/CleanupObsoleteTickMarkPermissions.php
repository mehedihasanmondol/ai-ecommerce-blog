<?php

namespace Database\Seeders;

use App\Modules\User\Models\Permission;
use Illuminate\Database\Seeder;

/**
 * ModuleName: User Management
 * Purpose: Remove obsolete individual tick mark permissions
 * 
 * These permissions have been consolidated into a single 'posts.tick-marks' permission
 * 
 * @author AI Assistant
 * @date 2025-12-07
 */
class CleanupObsoleteTickMarkPermissions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // List of obsolete permissions to remove
        $obsoletePermissions = [
            'posts.toggle-verification',
            'posts.toggle-editor-choice',
            'posts.toggle-trending',
            'posts.toggle-premium',
        ];

        $this->command->info('🧹 Cleaning up obsolete tick mark permissions...');

        foreach ($obsoletePermissions as $slug) {
            $permission = Permission::where('slug', $slug)->first();

            if ($permission) {
                // Detach from all roles first
                $permission->roles()->detach();

                // Delete the permission
                $permission->delete();

                $this->command->info("✓ Removed: {$slug}");
            } else {
                $this->command->warn("⚠ Not found: {$slug}");
            }
        }

        $this->command->info("\n========================================");
        $this->command->info('✓ Cleanup Complete!');
        $this->command->info('========================================');
        $this->command->info('Obsolete permissions removed: ' . count($obsoletePermissions));
        $this->command->info('Current tick mark permission: posts.tick-marks');
        $this->command->info('========================================\n');
    }
}

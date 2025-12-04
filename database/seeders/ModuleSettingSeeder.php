<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

/**
 * ModuleName: System Settings
 * Purpose: Seed module enable/disable settings for permission system
 * 
 * This seeder creates system-level settings to control which modules/features
 * are enabled or disabled across the entire system. When a module is disabled,
 * its permissions won't be visible in roles configuration and its menu items
 * will be hidden from all users regardless of their role permissions.
 * 
 * @author AI Assistant
 * @date 2025-12-04
 */
class ModuleSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔧 Seeding Module Settings...');

        $moduleSettings = [
            // User Management Module
            [
                'key' => 'module_user_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'modules',
                'description' => 'Enable/Disable User Management Module (Users, Roles, Permissions)',
            ],

            // Product Management Module
            [
                'key' => 'module_product_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'modules',
                'description' => 'Enable/Disable Product Module (Products, Categories, Brands, Attributes, Q&A, Reviews)',
            ],

            // Order Management Module
            [
                'key' => 'module_order_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'modules',
                'description' => 'Enable/Disable Order Module (Orders, Customers, Coupons)',
            ],

            // Delivery Management Module
            [
                'key' => 'module_delivery_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'modules',
                'description' => 'Enable/Disable Delivery Module (Zones, Methods, Rates)',
            ],

            // Stock Management Module
            [
                'key' => 'module_stock_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'modules',
                'description' => 'Enable/Disable Stock Module (Stock, Warehouses, Suppliers)',
            ],

            // Blog Module
            [
                'key' => 'module_blog_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'modules',
                'description' => 'Enable/Disable Blog Module (Posts, Categories, Tags, Comments)',
            ],

            // Content Management Module
            [
                'key' => 'module_content_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'modules',
                'description' => 'Enable/Disable Content Module (Homepage, Banners, Menus, Footer)',
            ],

            // Reports & Analytics Module
            [
                'key' => 'module_reports_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'modules',
                'description' => 'Enable/Disable Reports Module (Sales, Inventory, Product, Customer Reports)',
            ],

            // Finance Module
            [
                'key' => 'module_finance_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'modules',
                'description' => 'Enable/Disable Finance Module (Payment Gateways, Transactions, Finance Reports)',
            ],

            // System Settings Module
            [
                'key' => 'module_system_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'modules',
                'description' => 'Enable/Disable System Module (Site Settings, Cache, Maintenance, Logs)',
            ],

            // Feedback Module
            [
                'key' => 'module_feedback_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'modules',
                'description' => 'Enable/Disable Feedback Module (Customer Feedback)',
            ],

            // Appointments Module
            [
                'key' => 'module_appointments_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'modules',
                'description' => 'Enable/Disable Appointments Module (Appointments, Chambers)',
            ],
        ];

        foreach ($moduleSettings as $setting) {
            $this->upsertSetting($setting);
        }

        $this->command->info('✅ Module Settings Seeded Successfully!');
        $this->command->info('📊 Total Module Settings: ' . count($moduleSettings));
    }

    /**
     * Smart upsert: Only update if values are different
     */
    private function upsertSetting(array $data): void
    {
        $existing = SystemSetting::where('key', $data['key'])->first();

        if (!$existing) {
            SystemSetting::create($data);
            $this->command->info("  ✓ Created: {$data['key']}");
            return;
        }

        // Compare all fields except timestamps
        $needsUpdate = false;
        $updates = [];

        if ($existing->value !== $data['value']) {
            $updates['value'] = $data['value'];
            $needsUpdate = true;
        }

        if ($existing->type !== $data['type']) {
            $updates['type'] = $data['type'];
            $needsUpdate = true;
        }

        if ($existing->group !== $data['group']) {
            $updates['group'] = $data['group'];
            $needsUpdate = true;
        }

        if ($existing->description !== $data['description']) {
            $updates['description'] = $data['description'];
            $needsUpdate = true;
        }

        if ($needsUpdate) {
            $existing->update($updates);
            $this->command->info("  ↻ Updated: {$data['key']}");
        }
    }
}

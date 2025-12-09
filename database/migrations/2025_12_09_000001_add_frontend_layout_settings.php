<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add frontend layout settings to site_settings table
        DB::table('site_settings')->insert([
            [
                'key' => 'frontend_header_type',
                'value' => 'default',
                'type' => 'select',
                'group' => 'frontend_layout',
                'label' => 'Header Type',
                'description' => 'Select which header to display on the frontend',
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'frontend_footer_type',
                'value' => 'default',
                'type' => 'select',
                'group' => 'frontend_layout',
                'label' => 'Footer Type',
                'description' => 'Select which footer to display on the frontend',
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove frontend layout settings
        DB::table('site_settings')
            ->whereIn('key', ['frontend_header_type', 'frontend_footer_type'])
            ->delete();
    }
};

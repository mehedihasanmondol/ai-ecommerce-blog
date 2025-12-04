<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Note: Module settings are added via ModuleSettingSeeder
     * No schema changes needed as SystemSetting uses key-value storage
     */
    public function up(): void
    {
        // Module settings will be seeded via ModuleSettingSeeder
        // No schema changes required
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Module settings can be deleted via seeder if needed
        // No schema rollback required
    }
};

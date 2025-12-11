<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\FooterSetting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add newspaper footer settings
        FooterSetting::create([
            'key' => 'newspaper_second_column',
            'value' => '<h3 class="text-lg font-bold mb-4">About Us</h3><p class="text-sm text-gray-400">Your trusted source for the latest news and updates.</p>',
            'type' => 'text',
            'group' => 'newspaper',
        ]);

        FooterSetting::create([
            'key' => 'newspaper_third_column',
            'value' => '<h3 class="text-lg font-bold mb-4">Contact</h3><p class="text-sm text-gray-400">Email: info@example.com<br>Phone: +123 456 7890</p>',
            'type' => 'text',
            'group' => 'newspaper',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        FooterSetting::whereIn('key', ['newspaper_second_column', 'newspaper_third_column'])->delete();
    }
};

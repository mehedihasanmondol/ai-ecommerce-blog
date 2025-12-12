<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ad_slots', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255); // e.g., "Header Banner", "Sidebar Top"
            $table->string('slug', 255)->unique(); // e.g., "header-banner", "sidebar-top"
            $table->enum('location', ['header', 'footer', 'sidebar', 'inline', 'popup', 'native'])->default('sidebar');
            $table->text('description')->nullable();
            $table->integer('default_width')->unsigned()->nullable(); // Default width in pixels
            $table->integer('default_height')->unsigned()->nullable(); // Default height in pixels
            $table->boolean('is_active')->default(true);
            $table->boolean('lazy_load')->default(true); // Enable lazy loading
            $table->timestamps();

            // Indexes
            $table->index(['slug']);
            $table->index(['location']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_slots');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('headline_banner_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('enabled')->default(true);
            $table->string('label')->default('শিরোনাম');
            $table->text('news_text');
            $table->string('bg_color')->default('#E31E24');
            $table->string('text_color')->default('#FFFFFF');
            $table->string('label_bg_color')->default('#C41E3A');
            $table->integer('scroll_speed')->default(50);
            $table->boolean('auto_scroll')->default(true);
            $table->string('link_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('headline_banner_settings');
    }
};

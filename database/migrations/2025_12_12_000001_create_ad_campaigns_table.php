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
        Schema::create('ad_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'paused', 'completed', 'scheduled'])->default('scheduled');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('daily_impression_limit')->unsigned()->nullable();
            $table->integer('total_impression_limit')->unsigned()->nullable();
            $table->integer('daily_click_limit')->unsigned()->nullable();
            $table->integer('total_click_limit')->unsigned()->nullable();
            $table->integer('priority')->unsigned()->default(5); // 1-10, higher = more frequent
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['status']);
            $table->index(['start_date', 'end_date']);
            $table->index(['priority']);
            $table->index(['created_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_campaigns');
    }
};

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
        Schema::table('blog_posts', function (Blueprint $table) {
            // Tick mark management fields
            $table->boolean('is_verified')->default(false)->after('is_featured');
            $table->boolean('is_editor_choice')->default(false)->after('is_verified');
            $table->boolean('is_trending')->default(false)->after('is_editor_choice');
            $table->boolean('is_premium')->default(false)->after('is_trending');
            $table->timestamp('verified_at')->nullable()->after('is_premium');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete()->after('verified_at');
            $table->text('verification_notes')->nullable()->after('verified_by');
            
            // Indexes for performance
            $table->index('is_verified');
            $table->index('is_editor_choice');
            $table->index('is_trending');
            $table->index('is_premium');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropIndex(['is_verified']);
            $table->dropIndex(['is_editor_choice']);
            $table->dropIndex(['is_trending']);
            $table->dropIndex(['is_premium']);
            $table->dropColumn([
                'is_verified',
                'is_editor_choice',
                'is_trending',
                'is_premium',
                'verified_at',
                'verified_by',
                'verification_notes'
            ]);
        });
    }
};

<?php

namespace Database\Seeders;

use App\Models\FeaturedCategory;
use App\Modules\Blog\Models\BlogCategory;
use Illuminate\Database\Seeder;

/**
 * ModuleName: Featured Categories
 * Purpose: Seed initial featured categories for newspaper homepage
 * 
 * @author AI Assistant
 * @date 2025-12-10
 */
class FeaturedCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get active categories with published posts
        $categories = BlogCategory::where('is_active', true)
            ->withCount([
                'posts' => function ($q) {
                    $q->where('status', 'published');
                }
            ])
            ->having('posts_count', '>', 0)
            ->orderBy('name')
            ->limit(4)
            ->get();

        // Add categories to featured list
        $displayOrder = 1;
        foreach ($categories as $category) {
            FeaturedCategory::updateOrCreate(
                ['blog_category_id' => $category->id],
                [
                    'display_order' => $displayOrder,
                    'is_active' => true,
                ]
            );
            $displayOrder++;
        }

        $this->command->info('✓ Featured Categories seeded successfully! (' . $categories->count() . ' categories)');
    }
}

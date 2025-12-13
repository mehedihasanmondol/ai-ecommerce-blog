<?php

namespace Database\Seeders;

use App\Modules\Advertisement\Models\AdSlot;
use Illuminate\Database\Seeder;

/**
 * Seeder for predefined ad slots
 * 
 * @category Database
 * @package  Database\Seeders
 * @author   AI Assistant
 * @created  2025-12-12
 */
class AdSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slots = [
            [
                'name' => 'Header Banner',
                'slug' => 'header-banner',
                'location' => 'header',
                'description' => 'Banner ad displayed at the top of the page (Leaderboard)',
                'default_width' => 728,
                'default_height' => 90,
                'is_active' => true,
                'lazy_load' => false, // Header loads immediately
            ],
            [
                'name' => 'Sidebar Top',
                'slug' => 'sidebar-top',
                'location' => 'sidebar',
                'description' => 'Ad at the top of the sidebar (Medium Rectangle)',
                'default_width' => 300,
                'default_height' => 250,
                'is_active' => true,
                'lazy_load' => true,
            ],
            [
                'name' => 'Sidebar Middle',
                'slug' => 'sidebar-middle',
                'location' => 'sidebar',
                'description' => 'Ad in the middle of the sidebar (Half Page)',
                'default_width' => 300,
                'default_height' => 600,
                'is_active' => true,
                'lazy_load' => true,
            ],
            [
                'name' => 'Sidebar Bottom',
                'slug' => 'sidebar-bottom',
                'location' => 'sidebar',
                'description' => 'Ad at the bottom of the sidebar',
                'default_width' => 300,
                'default_height' => 250,
                'is_active' => true,
                'lazy_load' => true,
            ],
            [
                'name' => 'Footer Banner',
                'slug' => 'footer-banner',
                'location' => 'footer',
                'description' => 'Banner ad displayed at the bottom of the page',
                'default_width' => 728,
                'default_height' => 90,
                'is_active' => true,
                'lazy_load' => true,
            ],
            [
                'name' => 'Content Top',
                'slug' => 'content-top',
                'location' => 'inline',
                'description' => 'Inline ad before the main content',
                'default_width' => 728,
                'default_height' => 90,
                'is_active' => true,
                'lazy_load' => false,
            ],
            [
                'name' => 'Content Middle',
                'slug' => 'content-middle',
                'location' => 'inline',
                'description' => 'Inline ad in the middle of the content',
                'default_width' => 728,
                'default_height' => 90,
                'is_active' => true,
                'lazy_load' => true,
            ],
            [
                'name' => 'Content Bottom',
                'slug' => 'content-bottom',
                'location' => 'inline',
                'description' => 'Inline ad after the main content',
                'default_width' => 728,
                'default_height' => 90,
                'is_active' => true,
                'lazy_load' => true,
            ],
            [
                'name' => 'Popup / Interstitial',
                'slug' => 'popup-interstitial',
                'location' => 'popup',
                'description' => 'Full-screen popup or interstitial ad',
                'default_width' => null,
                'default_height' => null,
                'is_active' => false, // Disabled by default (can be annoying)
                'lazy_load' => false,
            ],
            [
                'name' => 'Native Feed Ad',
                'slug' => 'native-feed',
                'location' => 'native',
                'description' => 'Native ad that appears within news feed/listing',
                'default_width' => null, // Responsive
                'default_height' => null,
                'is_active' => true,
                'lazy_load' => true,
            ],
            [
                'name' => 'Video Pre-roll',
                'slug' => 'video-pre-roll',
                'location' => 'inline',
                'description' => 'Video ad that plays before main video content',
                'default_width' => 640,
                'default_height' => 360,
                'is_active' => false, // Disabled by default
                'lazy_load' => false,
            ],

            // ========== NEWSPAPER-SPECIFIC AD SLOTS ==========

            // Homepage Slots
            [
                'name' => 'Homepage Mid Content',
                'slug' => 'homepage-mid-content',
                'location' => 'inline',
                'description' => 'Large banner between featured stories and category sections',
                'default_width' => 970,
                'default_height' => 250,
                'is_active' => true,
                'lazy_load' => true,
            ],
            [
                'name' => 'Homepage Category Divider',
                'slug' => 'homepage-category-divider',
                'location' => 'inline',
                'description' => 'Banner between featured category sections',
                'default_width' => 728,
                'default_height' => 90,
                'is_active' => true,
                'lazy_load' => true,
            ],
            [
                'name' => 'Homepage Bottom',
                'slug' => 'homepage-bottom',
                'location' => 'inline',
                'description' => 'Banner at bottom of homepage before footer',
                'default_width' => 970,
                'default_height' => 90,
                'is_active' => true,
                'lazy_load' => true,
            ],

            // Category Page Slots
            [
                'name' => 'Category Header',
                'slug' => 'category-header',
                'location' => 'header',
                'description' => 'Banner below category breadcrumbs',
                'default_width' => 728,
                'default_height' => 90,
                'is_active' => true,
                'lazy_load' => false,
            ],
            [
                'name' => 'Category Feed Inline',
                'slug' => 'category-feed-inline',
                'location' => 'native',
                'description' => 'Native ad within category post grid (every 6 posts)',
                'default_width' => null,
                'default_height' => null,
                'is_active' => true,
                'lazy_load' => true,
            ],

            // Post Detail Page Slots
            [
                'name' => 'Post Header',
                'slug' => 'post-header',
                'location' => 'inline',
                'description' => 'Banner below post title, before content',
                'default_width' => 728,
                'default_height' => 90,
                'is_active' => true,
                'lazy_load' => false,
            ],
            [
                'name' => 'Post Content Inline',
                'slug' => 'post-content-inline',
                'location' => 'inline',
                'description' => 'Ad in middle of post content',
                'default_width' => 336,
                'default_height' => 280,
                'is_active' => true,
                'lazy_load' => true,
            ],
            [
                'name' => 'Post Sidebar Sticky',
                'slug' => 'post-sidebar-sticky',
                'location' => 'sidebar',
                'description' => 'Sticky advertisement in post sidebar',
                'default_width' => 300,
                'default_height' => 600,
                'is_active' => true,
                'lazy_load' => true,
            ],
            [
                'name' => 'Post Related Section',
                'slug' => 'post-related-section',
                'location' => 'inline',
                'description' => 'Banner after post content, before related posts',
                'default_width' => 728,
                'default_height' => 90,
                'is_active' => true,
                'lazy_load' => true,
            ],

            // Mobile-Specific Slots
            [
                'name' => 'Mobile Sticky Footer',
                'slug' => 'mobile-sticky-footer',
                'location' => 'footer',
                'description' => 'Fixed bottom banner for mobile devices',
                'default_width' => 320,
                'default_height' => 50,
                'is_active' => true,
                'lazy_load' => false,
            ],
            [
                'name' => 'Mobile Feed Native',
                'slug' => 'mobile-feed-native',
                'location' => 'native',
                'description' => 'Native ad within mobile article lists',
                'default_width' => null,
                'default_height' => null,
                'is_active' => true,
                'lazy_load' => true,
            ],
        ];

        foreach ($slots as $slot) {
            AdSlot::updateOrCreate(
                ['slug' => $slot['slug']],
                $slot
            );
        }

        $this->command->info('Ad slots seeded successfully!');
    }
}

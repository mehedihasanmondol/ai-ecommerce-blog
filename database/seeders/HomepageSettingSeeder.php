<?php

namespace Database\Seeders;

use App\Models\HomepageSetting;
use App\Models\HeroSlider;
use Illuminate\Database\Seeder;

class HomepageSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // General Settings
        $generalSettings = [
            [
                'key' => 'site_title',
                'value' => 'Welcome to Our Store',
                'type' => 'text',
                'group' => 'general',
                'order' => 1,
                'description' => 'Main site title displayed on homepage',
            ],
            [
                'key' => 'site_tagline',
                'value' => 'Your one-stop shop for quality products',
                'type' => 'text',
                'group' => 'general',
                'order' => 2,
                'description' => 'Site tagline or subtitle',
            ],
            [
                'key' => 'featured_products_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'featured',
                'order' => 1,
                'description' => 'Show featured products section on homepage',
            ],
            [
                'key' => 'featured_products_title',
                'value' => 'Featured Products',
                'type' => 'text',
                'group' => 'featured',
                'order' => 2,
                'description' => 'Title for featured products section',
            ],
            [
                'key' => 'featured_products_limit',
                'value' => '8',
                'type' => 'text',
                'group' => 'featured',
                'order' => 3,
                'description' => 'Number of featured products to display',
            ],
            // Note: new_arrivals settings moved to New Arrival Products management panel
            // Managed via site_settings with keys: new_arrivals_section_enabled and new_arrivals_section_title
            [
                'key' => 'banner_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'banner',
                'order' => 1,
                'description' => 'Show promotional banner on homepage',
            ],
            [
                'key' => 'banner_title',
                'value' => 'Special Offer',
                'type' => 'text',
                'group' => 'banner',
                'order' => 2,
                'description' => 'Promotional banner title',
            ],
            [
                'key' => 'banner_text',
                'value' => 'Get up to 50% off on selected items',
                'type' => 'textarea',
                'group' => 'banner',
                'order' => 3,
                'description' => 'Promotional banner text',
            ],
            
            // Top Header Settings
            [
                'key' => 'top_header_link1_text',
                'value' => 'Special Offers & Coupons',
                'type' => 'text',
                'group' => 'top_header',
                'order' => 1,
                'description' => 'Text for first top header link',
            ],
            [
                'key' => 'top_header_link1_url',
                'value' => '/coupons',
                'type' => 'text',
                'group' => 'top_header',
                'order' => 2,
                'description' => 'URL for first top header link',
            ],
            [
                'key' => 'top_header_link1_icon',
                'value' => 'tag',
                'type' => 'text',
                'group' => 'top_header',
                'order' => 3,
                'description' => 'Icon for first top header link (tag, clock, gift, etc.)',
            ],
            [
                'key' => 'top_header_link2_text',
                'value' => 'Shop Now',
                'type' => 'text',
                'group' => 'top_header',
                'order' => 4,
                'description' => 'Text for second top header link',
            ],
            [
                'key' => 'top_header_link2_url',
                'value' => '/shop',
                'type' => 'text',
                'group' => 'top_header',
                'order' => 5,
                'description' => 'URL for second top header link',
            ],
            [
                'key' => 'top_header_link2_icon',
                'value' => 'clock',
                'type' => 'text',
                'group' => 'top_header',
                'order' => 6,
                'description' => 'Icon for second top header link (tag, clock, gift, etc.)',
            ],
            [
                'key' => 'top_header_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'top_header',
                'order' => 7,
                'description' => 'Show top header announcement bar',
            ],
            
            // Mega Menu Settings
            [
                'key' => 'mega_menu_trending_brands_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'mega_menu',
                'order' => 1,
                'description' => 'Show trending brands in mega menu based on sales',
            ],
            [
                'key' => 'mega_menu_trending_brands_dynamic',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'mega_menu',
                'order' => 2,
                'description' => 'Calculate trending brands dynamically per category based on product sales',
            ],
            [
                'key' => 'mega_menu_trending_brands_limit',
                'value' => '6',
                'type' => 'text',
                'group' => 'mega_menu',
                'order' => 3,
                'description' => 'Number of trending brands to display per category (default: 6)',
            ],
            [
                'key' => 'mega_menu_trending_brands_days',
                'value' => '30',
                'type' => 'text',
                'group' => 'mega_menu',
                'order' => 4,
                'description' => 'Calculate trending brands based on sales from last X days (default: 30)',
            ],
            [
                'key' => 'mega_menu_trending_brands_fallback',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'mega_menu',
                'order' => 5,
                'description' => 'Show featured brands when no sales data available (if disabled, brands section will be hidden)',
            ],
        ];

        foreach ($generalSettings as $setting) {
            HomepageSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        // Hero Sliders
        $sliders = [
            [
                'title' => 'Up to 70% off',
                'subtitle' => 'iHerb Brands',
                'image' => 'https://via.placeholder.com/1920x400/1e3a8a/ffffff?text=Nutricost+-+Take+control+of+your+health',
                'link' => '/shop',
                'button_text' => 'Shop Now',
                'order' => 0,
                'is_active' => true,
            ],
            [
                'title' => 'Trusted Brands',
                'subtitle' => 'Up to 20% off',
                'image' => 'https://via.placeholder.com/1920x400/059669/ffffff?text=Trusted+Brands+-+Shop+Now',
                'link' => '/shop',
                'button_text' => 'Explore',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Wellness Hub',
                'subtitle' => 'Learn More',
                'image' => 'https://via.placeholder.com/1920x400/7c3aed/ffffff?text=Wellness+Hub+-+Discover+Health',
                'link' => '/about',
                'button_text' => 'Learn More',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => '20 High-Fibre Foods',
                'subtitle' => 'Find out more',
                'image' => 'https://via.placeholder.com/1920x400/dc2626/ffffff?text=High+Fibre+Foods+-+Nutrition+Guide',
                'link' => '/shop',
                'button_text' => 'View Guide',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Nutricost',
                'subtitle' => 'Shop now',
                'image' => 'https://via.placeholder.com/1920x400/ea580c/ffffff?text=Nutricost+-+Premium+Supplements',
                'link' => '/shop',
                'button_text' => 'Shop Now',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($sliders as $slider) {
            HeroSlider::updateOrCreate(
                ['title' => $slider['title']],
                $slider
            );
        }

        $this->command->info('Homepage settings and sliders seeded successfully!');
    }
}

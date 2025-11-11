<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

/**
 * ModuleName: Site Settings Seeder
 * Purpose: Seed default site settings including logo and branding
 * 
 * @category Seeders
 * @package  Database\Seeders
 * @created  2025-11-11
 */
class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'iHerb',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Site Name',
                'description' => 'The name of your website',
                'order' => 1,
            ],
            [
                'key' => 'site_tagline',
                'value' => 'Your Health & Wellness Store',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Site Tagline',
                'description' => 'A short description or tagline for your site',
                'order' => 2,
            ],
            [
                'key' => 'site_email',
                'value' => 'support@iherb.com',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Contact Email',
                'description' => 'Primary contact email address',
                'order' => 3,
            ],
            [
                'key' => 'site_phone',
                'value' => '+1-800-123-4567',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Contact Phone',
                'description' => 'Primary contact phone number',
                'order' => 4,
            ],

            // Appearance Settings
            [
                'key' => 'site_logo',
                'value' => null,
                'type' => 'image',
                'group' => 'appearance',
                'label' => 'Site Logo',
                'description' => 'Upload your site logo (recommended size: 200x60px)',
                'order' => 1,
            ],
            [
                'key' => 'site_favicon',
                'value' => null,
                'type' => 'image',
                'group' => 'appearance',
                'label' => 'Favicon',
                'description' => 'Upload your site favicon (recommended size: 32x32px)',
                'order' => 2,
            ],
            [
                'key' => 'admin_logo',
                'value' => null,
                'type' => 'image',
                'group' => 'appearance',
                'label' => 'Admin Panel Logo',
                'description' => 'Upload logo for admin panel (recommended size: 180x50px)',
                'order' => 3,
            ],
            [
                'key' => 'primary_color',
                'value' => '#16a34a',
                'type' => 'text',
                'group' => 'appearance',
                'label' => 'Primary Color',
                'description' => 'Primary brand color (hex code)',
                'order' => 4,
            ],

            // Social Media
            [
                'key' => 'facebook_url',
                'value' => '',
                'type' => 'text',
                'group' => 'social',
                'label' => 'Facebook URL',
                'description' => 'Your Facebook page URL',
                'order' => 1,
            ],
            [
                'key' => 'twitter_url',
                'value' => '',
                'type' => 'text',
                'group' => 'social',
                'label' => 'Twitter URL',
                'description' => 'Your Twitter profile URL',
                'order' => 2,
            ],
            [
                'key' => 'instagram_url',
                'value' => '',
                'type' => 'text',
                'group' => 'social',
                'label' => 'Instagram URL',
                'description' => 'Your Instagram profile URL',
                'order' => 3,
            ],
            [
                'key' => 'youtube_url',
                'value' => '',
                'type' => 'text',
                'group' => 'social',
                'label' => 'YouTube URL',
                'description' => 'Your YouTube channel URL',
                'order' => 4,
            ],

            // SEO Settings
            [
                'key' => 'meta_description',
                'value' => 'Shop for health and wellness products online',
                'type' => 'textarea',
                'group' => 'seo',
                'label' => 'Meta Description',
                'description' => 'Default meta description for your site',
                'order' => 1,
            ],
            [
                'key' => 'meta_keywords',
                'value' => 'health, wellness, supplements, vitamins',
                'type' => 'textarea',
                'group' => 'seo',
                'label' => 'Meta Keywords',
                'description' => 'Default meta keywords for your site',
                'order' => 2,
            ],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}

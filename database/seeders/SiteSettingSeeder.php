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

            // Blog Settings
            [
                'key' => 'blog_title',
                'value' => 'Health & Wellness Blog',
                'type' => 'text',
                'group' => 'blog',
                'label' => 'Blog Title',
                'description' => 'The main title for your blog section',
                'order' => 1,
            ],
            [
                'key' => 'blog_tagline',
                'value' => 'Your source for health tips, wellness advice, and product insights',
                'type' => 'text',
                'group' => 'blog',
                'label' => 'Blog Tagline',
                'description' => 'A short tagline or subtitle for your blog',
                'order' => 2,
            ],
            [
                'key' => 'blog_description',
                'value' => 'Discover the latest health and wellness tips, product reviews, and expert advice to help you live your best life. Our blog covers everything from nutrition and fitness to natural remedies and lifestyle tips.',
                'type' => 'textarea',
                'group' => 'blog',
                'label' => 'Blog Description',
                'description' => 'A detailed description of your blog for SEO and about sections',
                'order' => 3,
            ],
            [
                'key' => 'blog_keywords',
                'value' => 'health blog, wellness tips, nutrition advice, fitness, supplements, natural health, lifestyle, product reviews',
                'type' => 'textarea',
                'group' => 'blog',
                'label' => 'Blog Keywords',
                'description' => 'SEO keywords for your blog (comma-separated)',
                'order' => 4,
            ],
            [
                'key' => 'blog_posts_per_page',
                'value' => '12',
                'type' => 'text',
                'group' => 'blog',
                'label' => 'Posts Per Page',
                'description' => 'Number of blog posts to display per page',
                'order' => 5,
            ],
            [
                'key' => 'blog_show_author',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'blog',
                'label' => 'Show Author',
                'description' => 'Display author information on blog posts',
                'order' => 6,
            ],
            [
                'key' => 'blog_show_date',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'blog',
                'label' => 'Show Date',
                'description' => 'Display publication date on blog posts',
                'order' => 7,
            ],
            [
                'key' => 'blog_show_comments',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'blog',
                'label' => 'Enable Comments',
                'description' => 'Allow comments on blog posts',
                'order' => 8,
            ],

            // Homepage Settings
            [
                'key' => 'homepage_type',
                'value' => 'default',
                'type' => 'select',
                'group' => 'homepage',
                'label' => 'Homepage Type',
                'description' => 'Select what content to display on the homepage',
                'order' => 1,
            ],
            [
                'key' => 'homepage_author_id',
                'value' => null,
                'type' => 'select',
                'group' => 'homepage',
                'label' => 'Featured Author',
                'description' => 'Select an author to display (only for Author Profile homepage type)',
                'order' => 2,
            ],

            // Stock Settings
            [
                'key' => 'manual_stock_update_enabled',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'stock',
                'label' => 'Enable Manual Stock Updates',
                'description' => 'Allow manual stock updates in product edit form. If disabled, stock can only be managed via Stock Management system.',
                'order' => 1,
            ],
            [
                'key' => 'enable_out_of_stock_restriction',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'stock',
                'label' => 'Enable Out of Stock Restriction',
                'description' => 'When ENABLED: Users cannot add/order out-of-stock products, stock quantity is visible, "Out of Stock" messages shown. When DISABLED: Users can order out-of-stock products, stock quantity is completely hidden from frontend.',
                'order' => 2,
            ],

            // Invoice Settings
            [
                'key' => 'invoice_header_banner',
                'value' => null,
                'type' => 'image',
                'group' => 'invoice',
                'label' => 'Invoice Header Banner',
                'description' => 'Upload invoice header banner/logo (recommended size: 800x150px)',
                'order' => 1,
            ],
            [
                'key' => 'invoice_company_name',
                'value' => 'iHerb',
                'type' => 'text',
                'group' => 'invoice',
                'label' => 'Company Name',
                'description' => 'Company name to display on invoices',
                'order' => 2,
            ],
            [
                'key' => 'invoice_company_address',
                'value' => '123 Business Street, Dhaka 1000, Bangladesh',
                'type' => 'textarea',
                'group' => 'invoice',
                'label' => 'Company Address',
                'description' => 'Full company address for invoices',
                'order' => 3,
            ],
            [
                'key' => 'invoice_company_phone',
                'value' => '+880 1XXX-XXXXXX',
                'type' => 'text',
                'group' => 'invoice',
                'label' => 'Company Phone',
                'description' => 'Company phone number for invoices',
                'order' => 4,
            ],
            [
                'key' => 'invoice_company_email',
                'value' => 'info@iherb.com',
                'type' => 'text',
                'group' => 'invoice',
                'label' => 'Company Email',
                'description' => 'Company email for invoices',
                'order' => 5,
            ],
            [
                'key' => 'invoice_footer_text',
                'value' => 'Thank you for your business!',
                'type' => 'textarea',
                'group' => 'invoice',
                'label' => 'Footer Text',
                'description' => 'Text to display at the bottom of invoices',
                'order' => 6,
            ],
            [
                'key' => 'invoice_footer_note',
                'value' => 'This is a computer-generated invoice and does not require a signature.',
                'type' => 'textarea',
                'group' => 'invoice',
                'label' => 'Footer Note',
                'description' => 'Additional note for invoice footer',
                'order' => 7,
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

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FooterSetting;
use App\Models\FooterLink;
use App\Models\FooterBlogPost;

class FooterSeeder extends Seeder
{
    public function run(): void
    {
        // General Settings
        FooterSetting::create(['key' => 'newsletter_title', 'value' => 'BE THE FIRST TO GET PROMO OFFERS AND REWARD PERKS STRAIGHT TO YOUR INBOX', 'type' => 'text', 'group' => 'general']);
        FooterSetting::create(['key' => 'newsletter_description', 'value' => 'Your email address will be used to send you Health Newsletters and emails about our products, services, sales, and special offers.', 'type' => 'textarea', 'group' => 'general']);
        FooterSetting::create(['key' => 'value_guarantee', 'value' => 'World\'s best value - guaranteed!', 'type' => 'text', 'group' => 'general']);
        FooterSetting::create(['key' => 'rewards_text', 'value' => 'Enjoy free products, insider access and exclusive offers', 'type' => 'text', 'group' => 'general']);
        
        // Social Media
        FooterSetting::create(['key' => 'facebook_url', 'value' => '#', 'type' => 'url', 'group' => 'social']);
        FooterSetting::create(['key' => 'twitter_url', 'value' => '#', 'type' => 'url', 'group' => 'social']);
        FooterSetting::create(['key' => 'youtube_url', 'value' => '#', 'type' => 'url', 'group' => 'social']);
        FooterSetting::create(['key' => 'pinterest_url', 'value' => '#', 'type' => 'url', 'group' => 'social']);
        FooterSetting::create(['key' => 'instagram_url', 'value' => '#', 'type' => 'url', 'group' => 'social']);
        
        // Legal
        FooterSetting::create(['key' => 'copyright_text', 'value' => 'iHerb.com  Copyright 1997-2025 iHerb, Ltd. All rights reserved.', 'type' => 'textarea', 'group' => 'legal']);

        // Footer Links - About Section
        FooterLink::create(['section' => 'about', 'title' => 'About us', 'url' => '#', 'sort_order' => 1]);
        FooterLink::create(['section' => 'about', 'title' => 'Store Reviews', 'url' => '#', 'sort_order' => 2]);
        FooterLink::create(['section' => 'about', 'title' => 'Rewards Programme', 'url' => '#', 'sort_order' => 3]);
        FooterLink::create(['section' => 'about', 'title' => 'Affiliate Programme', 'url' => '#', 'sort_order' => 4]);
        FooterLink::create(['section' => 'about', 'title' => 'iTested', 'url' => '#', 'sort_order' => 5]);
        FooterLink::create(['section' => 'about', 'title' => 'We Give Back', 'url' => '#', 'sort_order' => 6]);

        // Footer Links - Company Section
        FooterLink::create(['section' => 'company', 'title' => 'Corporate', 'url' => '#', 'sort_order' => 1]);
        FooterLink::create(['section' => 'company', 'title' => 'Press', 'url' => '#', 'sort_order' => 2]);
        FooterLink::create(['section' => 'company', 'title' => 'Partner with iHerb', 'url' => '#', 'sort_order' => 3]);

        // Footer Links - Resources Section
        FooterLink::create(['section' => 'resources', 'title' => 'Wellness Hub', 'url' => '#', 'sort_order' => 1]);
        FooterLink::create(['section' => 'resources', 'title' => 'Accessibility View', 'url' => '#', 'sort_order' => 2]);
        FooterLink::create(['section' => 'resources', 'title' => 'Sales & Offers', 'url' => '#', 'sort_order' => 3]);

        // Footer Links - Customer Support Section
        FooterLink::create(['section' => 'customer_support', 'title' => 'Contact Us', 'url' => '#', 'sort_order' => 1]);
        FooterLink::create(['section' => 'customer_support', 'title' => 'Suggest a Product', 'url' => '#', 'sort_order' => 2]);
        FooterLink::create(['section' => 'customer_support', 'title' => 'Order Status', 'url' => '/orders/track', 'sort_order' => 3]);
        FooterLink::create(['section' => 'customer_support', 'title' => 'Delivery', 'url' => '#', 'sort_order' => 4]);
        FooterLink::create(['section' => 'customer_support', 'title' => 'Communication Preferences', 'url' => '#', 'sort_order' => 5]);

        // Blog Posts
        FooterBlogPost::create(['title' => '5 Simple Oral Health Tips', 'url' => '#', 'sort_order' => 1]);
        FooterBlogPost::create(['title' => 'Why Do Babies Need DHA?', 'url' => '#', 'sort_order' => 2]);
        FooterBlogPost::create(['title' => 'Best Home Remedies For A Cough + Sore Throat', 'url' => '#', 'sort_order' => 3]);
        FooterBlogPost::create(['title' => 'Top 10 Supplements For Longevity', 'url' => '#', 'sort_order' => 4]);
        FooterBlogPost::create(['title' => 'What Is Fungal Acne? A Doctor\'s Guide To Products', 'url' => '#', 'sort_order' => 5]);
        FooterBlogPost::create(['title' => 'Krill Oil: What It Is + 12 Science-Backed Benefits', 'url' => '#', 'sort_order' => 6]);
    }
}

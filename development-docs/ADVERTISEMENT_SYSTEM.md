# Advertisement System - Complete Documentation

## 📋 Table of Contents

- [Overview](#overview)
- [Architecture](#architecture)
- [Installation](#installation)
- [Campaign Management](#campaign-management)
- [Ad Creatives](#ad-creatives)
- [Ad Slots](#ad-slots)
- [Targeting System](#targeting-system)
- [Display Integration](#display-integration)
- [Performance Tracking](#performance-tracking)
- [API Reference](#api-reference)
- [Troubleshooting](#troubleshooting)

---

## 🎯 Overview

The Advertisement System is a comprehensive solution for managing and displaying ads across your Laravel application. It supports multiple ad types, smart targeting, performance tracking, and easy integration via Blade components.

### Key Features

- **Campaign Management** - Create, schedule, and manage ad campaigns
- **Multiple Ad Types** - Image, Video, GIF, HTML, Script
- **Smart Targeting** - Category-based and slot-based targeting
- **Performance Analytics** - Track impressions, clicks, and CTR
- **Priority System** - Weighted ad rotation (1-10 priority)
- **Frequency Control** - Set impression/click limits per campaign
- **Lazy Loading** - Optimized ad loading for better performance
- **Blade Components** - Easy integration with simple component tags

---

## 🏗️ Architecture

### Module Structure

```
app/Modules/Advertisement/
├── Controllers/
│   ├── AdCampaignController.php      # Campaign CRUD operations
│   ├── AdCreativeController.php      # Creative management
│   ├── AdSlotController.php          # Ad slot management
│   └── AdAnalyticsController.php     # Analytics and reporting
├── Models/
│   ├── AdCampaign.php               # Campaign model
│   ├── AdCreative.php               # Creative model
│   ├── AdSlot.php                   # Ad slot model
│   ├── AdImpression.php             # Impression tracking
│   └── AdClick.php                  # Click tracking
└── Services/
    ├── AdDisplayService.php         # Ad selection and rendering
    └── AdTrackingService.php        # Impression/click tracking
```

### Database Schema

**Tables:**
- `ad_campaigns` - Campaign information
- `ad_creatives` - Ad creative content
- `ad_slots` - Available ad slots
- `ad_creative_slot` - Creative-to-slot relationships (pivot)
- `ad_creative_category` - Creative-to-category targeting (pivot)
- `ad_impressions` - Impression tracking
- `ad_clicks` - Click tracking

---

## 🚀 Installation

### 1. Run Migrations

Migrations are already included in your project. Run:

```bash
php artisan migrate
```

### 2. Seed Ad Slots

```bash
php artisan db:seed --class=AdSlotSeeder
```

This creates 11 predefined ad slots:

| Slot | Dimensions | Location |
|------|-----------|----------|
| Header Banner | 728×90 | Top of page |
| Sidebar Top | 300×250 | Right sidebar top |
| Sidebar Middle | 300×600 | Right sidebar middle |
| Sidebar Bottom | 300×250 | Right sidebar bottom |
| Footer Banner | 728×90 | Bottom of page |
| Content Top | 728×90 | Above content |
| Content Middle | 728×90 | Within content |
| Content Bottom | 728×90 | Below content |
| Popup/Interstitial | Variable | Popup overlay |
| Native Feed Ad | Responsive | In post listings |
| Video Pre-roll | 640×360 | Video player |

### 3. Access Admin Panel

Navigate to: `/admin/advertisements/campaigns`

---

## 📊 Campaign Management

### Creating a Campaign

1. **Navigate** to `/admin/advertisements/campaigns`
2. **Click** "Create Campaign"
3. **Fill in details:**
   - **Name**: Campaign identifier
   - **Status**: Active, Paused, or Completed
   - **Priority**: 1-10 (higher = more likely to show)
   - **Start Date**: When campaign begins
   - **End Date**: When campaign ends (optional)
   - **Max Impressions**: Limit total impressions (optional)
   - **Max Clicks**: Limit total clicks (optional)

### Campaign Status

- **Active**: Campaign is running and ads will display
- **Paused**: Campaign is temporarily stopped
- **Completed**: Campaign has ended

### Priority System

Campaigns with higher priority are more likely to be displayed:

- **Priority 10**: Highest priority (always shows if active)
- **Priority 5**: Medium rotation
- **Priority 1**: Lowest rotation

The system uses **weighted random selection** based on priority.

### Campaign Limits

Set limits to control ad spend:

- **Max Impressions**: Stop showing after X impressions
- **Max Clicks**: Stop showing after X clicks
- **Both**: Campaign stops when either limit is reached

---

## 🎨 Ad Creatives

### Creative Types

#### 1. Image Ads
- **Formats**: JPG, PNG, WebP, GIF
- **Max Size**: 2MB recommended
- **Dimensions**: Auto-detected or manual input

#### 2. Video Ads
- **Formats**: MP4, WebM
- **Max Size**: 10MB recommended
- **Dimensions**: 640×360 (standard)

#### 3. GIF Ads
- **Formats**: Animated GIF
- **Max Size**: 5MB recommended
- **Dimensions**: Variable

#### 4. HTML Ads
- **Custom HTML**: Full control over ad content
- **JavaScript**: Supported
- **Responsive**: Can adapt to container

#### 5. Script Ads
- **Third-party**: Google AdSense, etc.
- **Embed Code**: Paste script tag
- **Auto-render**: Scripts execute automatically

### Creating a Creative

1. **In campaign edit page**, click "Add Creative"
2. **Configure creative:**
   - **Name**: Creative identifier
   - **Type**: Select ad type
   - **Upload/Content**: Add your ad content
   - **Dimensions**: Width × Height (auto-filled for images)
   - **Link URL**: Where ad should link to
   - **Target Slots**: Select one or more ad slots
   - **Target Categories**: Select categories (optional)
   - **Status**: Active or Inactive

### Creative Targeting

#### Slot Targeting
- Select multiple ad slots per creative
- Creative will display in any of the selected slots
- Dimensions should match slot requirements

#### Category Targeting
- **No categories selected**: Shows on ALL pages (universal)
- **Specific categories**: Shows ONLY on those category pages
- **Homepage**: Only universal ads (no category targeting) show

---

## 📍 Ad Slots

### Predefined Slots

All slots are created via `AdSlotSeeder`:

```php
php artisan db:seed --class=AdSlotSeeder
```

### Slot Properties

Each slot has:
- **Name**: Human-readable name
- **Slug**: Unique identifier (used in components)
- **Location**: header, sidebar, content, footer, popup
- **Default Dimensions**: Width × Height
- **Lazy Load**: Enable/disable lazy loading
- **Status**: Active or Inactive

### Managing Slots

**Admin Panel**: `/admin/advertisements/slots`

- View all slots
- Edit slot properties
- Activate/deactivate slots
- View usage examples

---

## 🎯 Targeting System

### How Targeting Works

The `AdDisplayService` selects ads based on:

1. **Slot Match**: Creative targets the requested slot
2. **Category Match**: 
   - If no category context: Show universal ads only
   - If category context: Show universal OR category-targeted ads
3. **Campaign Status**: Campaign must be active
4. **Campaign Dates**: Within start/end date range
5. **Campaign Limits**: Not exceeded max impressions/clicks
6. **Creative Status**: Creative must be active
7. **Priority**: Higher priority ads selected first

### Targeting Examples

#### Universal Ad (Shows Everywhere)
```
Campaign: "Site-wide Banner"
Creative: "Summer Sale"
  - Target Slots: header-banner
  - Target Categories: (none)
  
Result: Shows on ALL pages
```

#### Category-Specific Ad
```
Campaign: "Tech Products"
Creative: "Laptop Sale"
  - Target Slots: sidebar-top
  - Target Categories: Technology, Gadgets
  
Result: Shows ONLY on Technology and Gadgets category pages
```

#### Multi-Slot Ad
```
Campaign: "Holiday Promo"
Creative: "Holiday Banner"
  - Target Slots: header-banner, sidebar-top, content-middle
  - Target Categories: (none)
  
Result: Shows in ANY of the 3 slots on ALL pages
```

---

## 🔌 Display Integration

### Blade Components

All ad components are in `resources/views/components/advertisement/`:

#### 1. Ad Banner Component

**File**: `ad-banner.blade.php`

**Usage**:
```blade
<!-- Basic usage -->
<x-advertisement.ad-banner slot-slug="header-banner" />

<!-- With category targeting -->
<x-advertisement.ad-banner slot-slug="sidebar-top" :categoryId="$category->id" />
```

**Props**:
- `slot-slug` (required): Ad slot identifier
- `categoryId` (optional): Category ID for targeting

#### 2. Ad Inline Component

**File**: `ad-inline.blade.php`

**Usage**:
```blade
<x-advertisement.ad-inline slot-slug="content-middle" :categoryId="$post->blog_category_id" />
```

**Props**:
- `slot-slug` (required): Ad slot identifier
- `categoryId` (optional): Category ID for targeting

#### 3. Ad Native Component

**File**: `ad-native.blade.php`

**Usage**:
```blade
<x-advertisement.ad-native slot-slug="native-feed" :categoryId="$category->id" />
```

**Props**:
- `slot-slug` (required): Ad slot identifier
- `categoryId` (optional): Category ID for targeting

#### 4. Ad Popup Component

**File**: `ad-popup.blade.php`

**Usage**:
```blade
<x-advertisement.ad-popup />
```

**Props**: None (uses `popup-interstitial` slot automatically)

### Integration Examples

#### Newspaper Homepage

```blade
<!-- Header Banner -->
<div class="container mx-auto px-4">
    <x-advertisement.ad-banner slot-slug="header-banner" />
</div>

<!-- Sidebar Ad -->
<aside class="lg:col-span-3">
    <x-advertisement.ad-banner slot-slug="sidebar-top" />
</aside>

<!-- Category Section Sidebar -->
<aside class="lg:col-span-3">
    <x-advertisement.ad-banner 
        slot-slug="sidebar-middle" 
        :categoryId="$section['category']->id" 
    />
</aside>
```

#### News Detail Page

```blade
<!-- Sidebar Ad with Category Targeting -->
<div class="lg:col-span-3">
    <x-advertisement.ad-banner 
        slot-slug="sidebar-top" 
        :categoryId="$post->categories->first()?->id" 
    />
</div>
```

#### Blog Listing Page

```blade
<!-- Inline Ad Between Posts -->
@foreach($posts as $index => $post)
    <article>{{ $post->title }}</article>
    
    @if($index == 3)
        <x-advertisement.ad-inline 
            slot-slug="content-middle" 
            :categoryId="$currentCategory?->id" 
        />
    @endif
@endforeach
```

---

## 📈 Performance Tracking

### Automatic Tracking

The system automatically tracks:
- **Impressions**: When ad is displayed
- **Clicks**: When user clicks on ad
- **CTR**: Click-through rate (clicks/impressions × 100)

### Viewing Analytics

**Admin Panel**: `/admin/advertisements/analytics`

**Campaign-Level Metrics**:
- Total impressions
- Total clicks
- Overall CTR
- Performance by creative

**Creative-Level Metrics**:
- Impressions per creative
- Clicks per creative
- CTR per creative
- Best performing creatives

### Database Tables

#### ad_impressions
```sql
- id
- ad_campaign_id
- ad_creative_id
- ad_slot_id
- ip_address
- user_agent
- created_at
```

#### ad_clicks
```sql
- id
- ad_campaign_id
- ad_creative_id
- ad_slot_id
- ip_address
- user_agent
- created_at
```

---

## 🔧 API Reference

### AdDisplayService

**Location**: `app/Modules/Advertisement/Services/AdDisplayService.php`

#### getAdForSlot()

Retrieves an ad for a specific slot.

```php
public function getAdForSlot(string $slotSlug, ?int $categoryId = null): ?array
```

**Parameters**:
- `$slotSlug`: Ad slot identifier
- `$categoryId`: Optional category ID for targeting

**Returns**: Array with `campaign`, `creative`, `slot` or `null`

**Example**:
```php
$adService = app(\App\Modules\Advertisement\Services\AdDisplayService::class);
$ad = $adService->getAdForSlot('header-banner', $categoryId);

if ($ad) {
    $campaign = $ad['campaign'];
    $creative = $ad['creative'];
    $slot = $ad['slot'];
}
```

#### renderAd()

Renders ad HTML.

```php
public function renderAd(AdCreative $creative, AdSlot $slot): string
```

**Parameters**:
- `$creative`: AdCreative model
- `$slot`: AdSlot model

**Returns**: HTML string

### AdTrackingService

**Location**: `app/Modules/Advertisement/Services/AdTrackingService.php`

#### trackImpression()

Records an ad impression.

```php
public function trackImpression(AdCampaign $campaign, AdCreative $creative, AdSlot $slot): void
```

#### trackClick()

Records an ad click.

```php
public function trackClick(AdCampaign $campaign, AdCreative $creative, AdSlot $slot): void
```

---

## 🐛 Troubleshooting

### Ads Not Showing

**Check Campaign Status**:
- Campaign status must be "Active"
- Check start/end dates
- Verify campaign hasn't reached limits

**Check Creative Status**:
- Creative must be marked as "Active"
- Verify creative targets the correct slot
- Check category targeting settings

**Check Slot Configuration**:
- Ad slot must exist and be active
- Run: `php artisan db:seed --class=AdSlotSeeder`

**Category Targeting**:
- Universal ads: Leave categories empty
- Category-specific: Only shows on those pages
- Homepage: Only universal ads show

### Wrong Ads Showing

**Review Priority**:
- Higher priority campaigns show more often
- Adjust campaign priority (1-10)

**Check Targeting**:
- Verify category targeting settings
- Check slot targeting configuration

**Clear Cache**:
```bash
php artisan cache:clear
php artisan config:clear
```

### Performance Issues

**Enable Lazy Loading**:
- Edit ad slot settings
- Enable "Lazy Load" option

**Optimize Images**:
- Use WebP format
- Compress images before upload
- Recommended max size: 2MB

**Limit Tracking**:
- Consider sampling for high-traffic sites
- Archive old impression/click data

### Browser Console Errors

**Check JavaScript**:
- Open browser DevTools (F12)
- Check Console tab for errors
- Verify ad creative HTML/script is valid

**CORS Issues**:
- If using external ad scripts
- Check server CORS configuration

---

## 📚 Best Practices

### Campaign Setup

1. **Start with Low Priority**: Test new campaigns with priority 1-3
2. **Set Limits**: Use impression/click limits to control spend
3. **Monitor Performance**: Check analytics regularly
4. **A/B Testing**: Create multiple creatives per campaign

### Creative Design

1. **Match Dimensions**: Use recommended sizes for each slot
2. **Optimize Images**: WebP format, compressed
3. **Clear CTA**: Include clear call-to-action
4. **Mobile-Friendly**: Ensure ads work on all devices

### Targeting Strategy

1. **Universal First**: Start with universal ads
2. **Category Targeting**: Use sparingly for specific campaigns
3. **Multi-Slot**: Target multiple slots for better coverage
4. **Test and Iterate**: Monitor performance and adjust

### Performance Optimization

1. **Lazy Loading**: Enable for below-fold ads
2. **Image Optimization**: Compress and use modern formats
3. **Cache Management**: Clear cache after major changes
4. **Database Cleanup**: Archive old tracking data periodically

---

## 🔄 Workflow Example

### Complete Campaign Setup

1. **Create Campaign**:
   ```
   Name: "Summer Sale 2025"
   Status: Active
   Priority: 7
   Start: 2025-06-01
   End: 2025-08-31
   Max Impressions: 100,000
   ```

2. **Add Header Banner Creative**:
   ```
   Name: "Summer Sale Header"
   Type: Image
   Upload: summer-sale-728x90.webp
   Link: https://example.com/summer-sale
   Target Slots: header-banner
   Target Categories: (none - universal)
   Status: Active
   ```

3. **Add Sidebar Creative**:
   ```
   Name: "Summer Sale Sidebar"
   Type: Image
   Upload: summer-sale-300x250.webp
   Link: https://example.com/summer-sale
   Target Slots: sidebar-top, sidebar-middle
   Target Categories: (none - universal)
   Status: Active
   ```

4. **Monitor Performance**:
   - Check `/admin/advertisements/analytics`
   - Review impressions, clicks, CTR
   - Adjust priority or creatives as needed

5. **Optimize**:
   - Pause underperforming creatives
   - Increase priority for high-performing campaigns
   - Test new creative variations

---

## 📞 Support

For issues or questions:
- Check this documentation
- Review `/development-docs/` for additional guides
- Check application logs: `storage/logs/laravel.log`

---

**Last Updated**: December 13, 2025  
**Version**: 1.0.0  
**Module**: Advertisement System

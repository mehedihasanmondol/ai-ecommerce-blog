<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * ModuleName: Headline Banner Settings
 * Purpose: Manage breaking news banner settings for newspaper homepage
 * 
 * @category Models
 * @package  App\Models
 * @created  2025-12-09
 */
class HeadlineBannerSettings extends Model
{
    protected $fillable = [
        'enabled',
        'label',
        'news_text',
        'bg_color',
        'text_color',
        'label_bg_color',
        'scroll_speed',
        'auto_scroll',
        'link_url',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'auto_scroll' => 'boolean',
        'scroll_speed' => 'integer',
    ];

    /**
     * Get cached settings or create default
     */
    public static function getSettings()
    {
        $settings = self::first();

        if (!$settings) {
            $settings = self::create([
                'enabled' => true,
                'label' => 'শিরোনাম',
                'news_text' => 'সর্বশেষ সংবাদ এখানে প্রদর্শিত হবে',
                'bg_color' => '#f1f2f4',
                'text_color' => '#000000',
                'label_bg_color' => '#C41E3A',
                'scroll_speed' => 5,
                'auto_scroll' => true,
                'link_url' => null,
            ]);
        }

        return $settings;
    }


}

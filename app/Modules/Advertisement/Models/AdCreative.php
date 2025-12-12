<?php

namespace App\Modules\Advertisement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use App\Modules\Blog\Models\BlogCategory;

/**
 * ModuleName: Advertisement
 * Purpose: Represents an ad creative (content) for a campaign
 * 
 * @category Advertisement
 * @package  App\Modules\Advertisement\Models
 * @author   AI Assistant
 * @created  2025-12-12
 */
class AdCreative extends Model
{
    use HasFactory;

    protected $table = 'ad_creatives';

    protected $fillable = [
        'ad_campaign_id',
        'name',
        'type',
        'content',
        'image_path',
        'video_url',
        'video_type',
        'link_url',
        'link_target',
        'width',
        'height',
        'alt_text',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'width' => 'integer',
        'height' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the campaign this creative belongs to
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(AdCampaign::class, 'ad_campaign_id');
    }

    /**
     * Get impressions for this creative
     */
    public function impressions(): HasMany
    {
        return $this->hasMany(AdImpression::class, 'ad_creative_id');
    }

    /**
     * Get clicks for this creative
     */
    public function clicks(): HasMany
    {
        return $this->hasMany(AdClick::class, 'ad_creative_id');
    }

    /**
     * Get ad slots this creative targets
     */
    public function slots(): BelongsToMany
    {
        return $this->belongsToMany(AdSlot::class, 'ad_creative_slot', 'ad_creative_id', 'ad_slot_id')
            ->withTimestamps();
    }

    /**
     * Get categories this creative targets
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(BlogCategory::class, 'ad_creative_category', 'ad_creative_id', 'blog_category_id')
            ->withTimestamps();
    }

    /**
     * Check if creative is image type
     */
    public function isImage(): bool
    {
        return in_array($this->type, ['image', 'gif']);
    }

    /**
     * Check if creative is video type
     */
    public function isVideo(): bool
    {
        return $this->type === 'video';
    }

    /**
     * Check if creative is HTML type
     */
    public function isHtml(): bool
    {
        return $this->type === 'html';
    }

    /**
     * Check if creative is script type
     */
    public function isScript(): bool
    {
        return $this->type === 'script';
    }

    /**
     * Get full image URL
     */
    public function getImageUrl(): ?string
    {
        if (!$this->image_path) {
            return null;
        }

        // If it's already a full URL, return as-is
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }

        // Otherwise, get from storage
        return Storage::url($this->image_path);
    }

    /**
     * Get YouTube video ID from URL
     */
    public function getYoutubeVideoId(): ?string
    {
        if (!$this->video_url) {
            return null;
        }

        // Extract video ID from various YouTube URL formats
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';

        if (preg_match($pattern, $this->video_url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Get video embed code
     */
    public function getVideoEmbedCode(): ?string
    {
        if (!$this->video_url) {
            return null;
        }

        // YouTube embed
        $youtubeId = $this->getYoutubeVideoId();
        if ($youtubeId) {
            $width = $this->width ?? 560;
            $height = $this->height ?? 315;
            return '<iframe width="' . $width . '" height="' . $height . '" src="https://www.youtube.com/embed/' . $youtubeId . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        }

        // Vimeo embed
        if (preg_match('/vimeo\.com\/(\d+)/', $this->video_url, $matches)) {
            $vimeoId = $matches[1];
            $width = $this->width ?? 560;
            $height = $this->height ?? 315;
            return '<iframe src="https://player.vimeo.com/video/' . $vimeoId . '" width="' . $width . '" height="' . $height . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
        }

        // Direct video file
        if (Storage::exists($this->video_url)) {
            $videoUrl = Storage::url($this->video_url);
            $width = $this->width ?? 560;
            $height = $this->height ?? 315;
            return '<video width="' . $width . '" height="' . $height . '" controls><source src="' . $videoUrl . '" type="video/mp4">Your browser does not support the video tag.</video>';
        }

        return null;
    }

    /**
     * Render the creative HTML
     */
    public function render(AdSlot $slot = null): string
    {
        $html = '';

        switch ($this->type) {
            case 'image':
            case 'gif':
                $imageUrl = $this->getImageUrl();
                $width = $this->width ?? ($slot ? $slot->default_width : null);
                $height = $this->height ?? ($slot ? $slot->default_height : null);

                $html = '<div class="ad-creative ad-image">';
                if ($this->link_url) {
                    $html .= '<a href="' . e($this->link_url) . '" target="' . e($this->link_target) . '" rel="noopener">';
                }
                $html .= '<img src="' . e($imageUrl) . '" alt="' . e($this->alt_text ?? $this->name) . '"';
                if ($width) $html .= ' width="' . $width . '"';
                if ($height) $html .= ' height="' . $height . '"';
                $html .= ' loading="lazy">';
                if ($this->link_url) {
                    $html .= '</a>';
                }
                $html .= '</div>';
                break;

            case 'video':
                $html = '<div class="ad-creative ad-video">';
                $html .= $this->getVideoEmbedCode() ?? '';
                $html .= '</div>';
                break;

            case 'html':
                $html = '<div class="ad-creative ad-html">';
                $html .= $this->content; // Already HTML, don't escape
                $html .= '</div>';
                break;

            case 'script':
                $html = '<div class="ad-creative ad-script">';
                $html .= $this->content; // Third-party script, don't escape
                $html .= '</div>';
                break;
        }

        return $html;
    }

    /**
     * Get total impressions count
     */
    public function getTotalImpressions(): int
    {
        return $this->impressions()->count();
    }

    /**
     * Get total clicks count
     */
    public function getTotalClicks(): int
    {
        return $this->clicks()->count();
    }

    /**
     * Calculate click-through rate (CTR)
     */
    public function getCTR(): float
    {
        $impressions = $this->getTotalImpressions();

        if ($impressions === 0) {
            return 0.0;
        }

        $clicks = $this->getTotalClicks();

        return round(($clicks / $impressions) * 100, 2);
    }

    /**
     * Scope to get only active creatives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

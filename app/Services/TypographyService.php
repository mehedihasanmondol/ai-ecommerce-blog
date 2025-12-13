<?php

namespace App\Services;

use App\Models\SiteSetting;

/**
 * ModuleName: Typography Service
 * Purpose: Handle typography-related logic and CSS variable generation
 * 
 * @category Services
 * @package  App\Services
 * @created  2025-12-13
 */
class TypographyService
{
    /**
     * Generate CSS custom properties from typography settings
     * 
     * @return string CSS variables as a string
     */
    public function generateCssVariables(): string
    {
        $baseMultiplier = (float) SiteSetting::get('typography_base_multiplier', 100) / 100;
        $headingMultiplier = (float) SiteSetting::get('typography_heading_multiplier', 100) / 100;
        $smallMultiplier = (float) SiteSetting::get('typography_small_multiplier', 100) / 100;
        $lineHeightAdjustment = (int) SiteSetting::get('typography_line_height_adjustment', 0);

        // Define base sizes (from Tailwind defaults)
        $textSizes = [
            // Small text sizes (affected by small multiplier)
            'xs' => ['size' => '0.75rem', 'lineHeight' => '1rem', 'multiplier' => $smallMultiplier],

            // Base text sizes (affected by base multiplier)
            'sm' => ['size' => '0.875rem', 'lineHeight' => '1.25rem', 'multiplier' => $baseMultiplier],
            'base' => ['size' => '1rem', 'lineHeight' => '1.5rem', 'multiplier' => $baseMultiplier],

            // Heading sizes (affected by heading multiplier)
            'lg' => ['size' => '1.125rem', 'lineHeight' => '1.75rem', 'multiplier' => $headingMultiplier],
            'xl' => ['size' => '1.25rem', 'lineHeight' => '1.75rem', 'multiplier' => $headingMultiplier],
            '2xl' => ['size' => '1.5rem', 'lineHeight' => '2rem', 'multiplier' => $headingMultiplier],
            '3xl' => ['size' => '1.875rem', 'lineHeight' => '2.25rem', 'multiplier' => $headingMultiplier],
            '4xl' => ['size' => '2.25rem', 'lineHeight' => '2.5rem', 'multiplier' => $headingMultiplier],
            '5xl' => ['size' => '3rem', 'lineHeight' => '1', 'multiplier' => $headingMultiplier],
            '6xl' => ['size' => '3.75rem', 'lineHeight' => '1', 'multiplier' => $headingMultiplier],
            '7xl' => ['size' => '4.5rem', 'lineHeight' => '1', 'multiplier' => $headingMultiplier],
            '8xl' => ['size' => '6rem', 'lineHeight' => '1', 'multiplier' => $headingMultiplier],
            '9xl' => ['size' => '8rem', 'lineHeight' => '1', 'multiplier' => $headingMultiplier],
        ];

        $css = ":root {\n";

        foreach ($textSizes as $key => $config) {
            $multiplier = $config['multiplier'];
            $size = $config['size'];
            $lineHeight = $config['lineHeight'];

            // Calculate adjusted sizes
            $adjustedSize = "calc({$size} * {$multiplier})";

            // Adjust line height if needed
            if ($lineHeightAdjustment !== 0) {
                $adjustmentPx = $lineHeightAdjustment > 0 ? "+{$lineHeightAdjustment}px" : "{$lineHeightAdjustment}px";
                $adjustedLineHeight = "calc({$lineHeight} {$adjustmentPx})";
            } else {
                $adjustedLineHeight = $lineHeight;
            }

            $css .= "    --text-{$key}: {$adjustedSize};\n";
            $css .= "    --text-{$key}--line-height: {$adjustedLineHeight};\n";
        }

        $css .= "}";

        return $css;
    }

    /**
     * Validate multiplier value
     * 
     * @param mixed $value The value to validate
     * @return bool
     */
    public function validateMultiplier($value): bool
    {
        if (!is_numeric($value)) {
            return false;
        }

        $numValue = (float) $value;
        return $numValue >= 50 && $numValue <= 200;
    }

    /**
     * Validate line height adjustment
     * 
     * @param mixed $value The value to validate
     * @return bool
     */
    public function validateLineHeightAdjustment($value): bool
    {
        if (!is_numeric($value)) {
            return false;
        }

        $numValue = (int) $value;
        return $numValue >= -5 && $numValue <= 10;
    }

    /**
     * Get preview HTML for admin panel
     * 
     * @return string HTML preview
     */
    public function getPreviewHtml(): string
    {
        return '
        <div class="space-y-4 p-6 bg-gray-50 rounded-lg">
            <div>
                <p class="text-xs text-gray-600">Extra Small (text-xs)</p>
                <p class="text-xs">The quick brown fox jumps over the lazy dog</p>
            </div>
            <div>
                <p class="text-xs text-gray-600">Small (text-sm)</p>
                <p class="text-sm">The quick brown fox jumps over the lazy dog</p>
            </div>
            <div>
                <p class="text-xs text-gray-600">Base (text-base)</p>
                <p class="text-base">The quick brown fox jumps over the lazy dog</p>
            </div>
            <div>
                <p class="text-xs text-gray-600">Large (text-lg)</p>
                <p class="text-lg">The quick brown fox jumps over the lazy dog</p>
            </div>
            <div>
                <p class="text-xs text-gray-600">Extra Large (text-xl)</p>
                <p class="text-xl">The quick brown fox jumps over the lazy dog</p>
            </div>
            <div>
                <p class="text-xs text-gray-600">2X Large (text-2xl)</p>
                <p class="text-2xl">The quick brown fox jumps over the lazy dog</p>
            </div>
            <div>
                <p class="text-xs text-gray-600">3X Large (text-3xl)</p>
                <p class="text-3xl">The quick brown fox jumps over the lazy dog</p>
            </div>
        </div>
        ';
    }
}

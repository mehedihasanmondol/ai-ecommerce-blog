<?php

namespace App\Services;

use DOMDocument;
use DOMXPath;

class ContentAdInjector
{
    /**
     * Inject ads into HTML content at strategic positions
     *
     * @param string $content The HTML content
     * @param int|null $categoryId Category ID for targeting
     * @param array $adSlots Ad slots to inject (in order)
     * @return string Modified HTML with ads
     */
    public function inject(string $content, ?int $categoryId = null, array $adSlots = ['content-top', 'content-middle', 'post-content-inline']): string
    {
        // Check if user has advertisement permission (if authenticated)
        if (auth()->check() && !auth()->user()->can('view-advertisements')) {
            return $content;
        }

        if (empty($content) || empty($adSlots)) {
            return $content;
        }

        // Use DOMDocument to parse HTML
        $dom = new DOMDocument('1.0', 'UTF-8');
        $internalErrors = libxml_use_internal_errors(true);

        // Load HTML with UTF-8 encoding
        $dom->loadHTML('<?xml encoding="UTF-8">' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($internalErrors);

        $xpath = new DOMXPath($dom);

        // Find all paragraphs and list items
        $elements = $xpath->query('//p | //ul | //ol | //h2 | //h3');

        if ($elements->length < 3) {
            // Not enough content elements, append ads at the end
            return $content . $this->renderAds($adSlots, $categoryId);
        }

        $totalElements = $elements->length;
        $adCount = count($adSlots);

        // Calculate positions for ad insertion
        $positions = $this->calculateAdPositions($totalElements, $adCount);

        // Insert ads at calculated positions
        $insertedCount = 0;
        foreach ($positions as $index => $position) {
            if ($position >= $totalElements) {
                continue;
            }

            $element = $elements->item($position);
            if (!$element) {
                continue;
            }

            // Create ad container
            $adHtml = $this->renderAdSlot($adSlots[$insertedCount] ?? 'post-content-inline', $categoryId);
            $adFragment = $dom->createDocumentFragment();
            $adFragment->appendXML($adHtml);

            // Insert after the element
            if ($element->nextSibling) {
                $element->parentNode->insertBefore($adFragment, $element->nextSibling);
            } else {
                $element->parentNode->appendChild($adFragment);
            }

            $insertedCount++;
            if ($insertedCount >= $adCount) {
                break;
            }
        }

        // Get the modified HTML
        $result = $dom->saveHTML();

        // Remove XML declaration that was added
        $result = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace('<?xml encoding="UTF-8">', '', $result));

        return $result;
    }

    /**
     * Calculate strategic positions for ad placement
     *
     * @param int $totalElements Total number of content elements
     * @param int $adCount Number of ads to insert
     * @return array Positions where ads should be inserted
     */
    protected function calculateAdPositions(int $totalElements, int $adCount): array
    {
        if ($adCount <= 0 || $totalElements < 3) {
            return [];
        }

        $positions = [];

        if ($adCount === 1) {
            // Single ad: place in the middle
            $positions[] = (int) floor($totalElements / 2);
        } elseif ($adCount === 2) {
            // Two ads: 1/3 and 2/3
            $positions[] = (int) floor($totalElements / 3);
            $positions[] = (int) floor(($totalElements / 3) * 2);
        } else {
            // Multiple ads: distribute evenly
            $interval = (int) floor($totalElements / ($adCount + 1));
            for ($i = 1; $i <= $adCount; $i++) {
                $pos = $interval * $i;
                if ($pos < $totalElements) {
                    $positions[] = $pos;
                }
            }
        }

        return $positions;
    }

    /**
     * Render a single ad slot
     *
     * @param string $slotSlug
     * @param int|null $categoryId
     * @return string
     */
    protected function renderAdSlot(string $slotSlug, ?int $categoryId = null): string
    {
        $categoryParam = $categoryId ? ":category-id=\"{$categoryId}\"" : '';

        return "<div class=\"in-content-ad my-6\">
            <x-advertisement.ad-banner slot-slug=\"{$slotSlug}\" {$categoryParam} />
        </div>";
    }

    /**
     * Render all ads concatenated (fallback)
     *
     * @param array $adSlots
     * @param int|null $categoryId
     * @return string
     */
    protected function renderAds(array $adSlots, ?int $categoryId = null): string
    {
        $html = '';
        foreach ($adSlots as $slotSlug) {
            $html .= $this->renderAdSlot($slotSlug, $categoryId);
        }
        return $html;
    }
}

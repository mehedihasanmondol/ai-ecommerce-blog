@props(['slotSlug' => 'header-banner', 'categoryId' => null])

@php
$adDisplayService = app(\App\Modules\Advertisement\Services\AdDisplayService::class);
$adTrackingService = app(\App\Modules\Advertisement\Services\AdTrackingService::class);

$ad = $adDisplayService->getAdForSlot($slotSlug, $categoryId);

@endphp

@if($ad)
@php
$campaign = $ad['campaign'];
$creative = $ad['creative'];
$adSlot = $ad['slot'];

// Track impression
$adTrackingService->trackImpression($campaign, $creative, $adSlot);
@endphp




<div class="ad-container ad-banner ad-slot-{{ $adSlot->slug }}"
    data-ad-campaign="{{ $campaign->id }}"
    data-ad-creative="{{ $creative->id }}"
    data-ad-slot="{{ $adSlot->id }}"
    @if($adSlot->lazy_load) data-lazy-load="true" @endif>

    <div class="ad-label">
        <span>Advertisement</span>
    </div>

    <div class="ad-content">
        {!! $adDisplayService->renderAd($creative, $adSlot) !!}
    </div>
</div>
@endif

@push('styles')
<style>
    .ad-container {
        padding: 10px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        text-align: center;
    }

    .ad-label {
        font-size: 10px;
        color: #6c757d;
        text-transform: uppercase;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }

    .ad-content {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .ad-content img {
        max-width: 100%;
        height: auto;
        display: block;
    }

    .ad-creative {
        width: 100%;
    }

    /* Lazy loading placeholder */
    .ad-container[data-lazy-load="true"]:not(.loaded) .ad-content {
        min-height: 90px;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }

    @keyframes loading {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }
</style>
@endpush
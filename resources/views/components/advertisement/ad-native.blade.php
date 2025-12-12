@props(['slotSlug' => 'native-feed', 'categoryId' => null])

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

<div class="ad-container ad-native ad-slot-{{ $adSlot->slug }}"
    data-ad-campaign="{{ $campaign->id }}"
    data-ad-creative="{{ $creative->id }}"
    data-ad-slot="{{ $adSlot->id }}"
    @if($adSlot->lazy_load) data-lazy-load="true" @endif>

    <div class="ad-sponsored-label">
        <span>Sponsored</span>
    </div>

    <div class="ad-content">
        {!! $adDisplayService->renderAd($creative, $adSlot) !!}
    </div>
</div>
@endif

@push('styles')
<style>
    .ad-native {
        margin: 20px 0;
        padding: 15px;
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        transition: box-shadow 0.3s;
    }

    .ad-native:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .ad-sponsored-label {
        font-size: 11px;
        color: #999;
        text-transform: uppercase;
        margin-bottom: 10px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .ad-sponsored-label span {
        background: #f0f0f0;
        padding: 3px 8px;
        border-radius: 3px;
    }

    .ad-native .ad-content {
        width: 100%;
    }

    .ad-native .ad-content img {
        width: 100%;
        height: auto;
        border-radius: 4px;
    }
</style>
@endpush
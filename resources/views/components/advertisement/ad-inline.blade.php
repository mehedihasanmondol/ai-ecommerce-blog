@props(['slotSlug' => 'content-middle', 'categoryId' => null])

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

<div class="ad-container ad-inline ad-slot-{{ $adSlot->slug }}"
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
    .ad-inline {
        margin: 30px auto;
        max-width: 728px;
        padding: 15px;
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .ad-inline .ad-label {
        font-size: 10px;
        color: #999;
        text-transform: uppercase;
        margin-bottom: 10px;
        text-align: center;
    }

    .ad-inline .ad-content {
        display: flex;
        justify-content: center;
    }
</style>
@endpush
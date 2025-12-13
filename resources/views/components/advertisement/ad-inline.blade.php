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

    <div class="ad-content">
        {!! $adDisplayService->renderAd($creative, $adSlot) !!}
    </div>
</div>
@endif

@push('styles')
<style>
    .ad-inline {
        max-width: 728px;
    }

    .ad-inline .ad-content {
        display: flex;
        justify-content: center;
    }
</style>
@endpush
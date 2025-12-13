<div class="ad-container ad-banner ad-slot-{{ $adSlot->slug }}"
    data-ad-campaign="{{ $campaign->id }}"
    data-ad-creative="{{ $creative->id }}"
    data-ad-slot="{{ $adSlot->id }}"
    style="padding: 10px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; text-align: center;">

    <div class="ad-label" style="font-size: 10px; color: #6c757d; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">
        <span>Advertisement</span>
    </div>

    <div class="ad-content" style="display: flex; justify-content: center; align-items: center; max-width: 100%; overflow: hidden;">
        {!! $adContent !!}
    </div>
</div>
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
        max-width: 100%;
        overflow: hidden;
    }

    .ad-content img,
    .ad-content video,
    .ad-content iframe,
    .ad-content>* {
        max-width: 100%;
        display: block;
        margin: auto;
    }

    .ad-creative {
        width: 100%;
        max-width: 100%;
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lazy load ads using Intersection Observer
        const lazyAds = document.querySelectorAll('.ad-container[data-lazy-load="true"]');

        if ('IntersectionObserver' in window) {
            const adObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const adContainer = entry.target;

                        // Mark as loaded to remove skeleton
                        adContainer.classList.add('loaded');

                        // Find all media elements and wait for them to load
                        const images = adContainer.querySelectorAll('img');
                        const videos = adContainer.querySelectorAll('video');
                        const iframes = adContainer.querySelectorAll('iframe');

                        // Handle images
                        images.forEach(img => {
                            if (img.complete) {
                                adContainer.classList.add('loaded');
                            } else {
                                img.addEventListener('load', () => {
                                    adContainer.classList.add('loaded');
                                });
                            }
                        });

                        // Handle iframes (videos)
                        if (iframes.length > 0) {
                            // Add loaded class after a short delay for iframes
                            setTimeout(() => {
                                adContainer.classList.add('loaded');
                            }, 500);
                        }

                        // If no media elements, mark as loaded immediately
                        if (images.length === 0 && videos.length === 0 && iframes.length === 0) {
                            adContainer.classList.add('loaded');
                        }

                        // Stop observing this ad
                        observer.unobserve(adContainer);
                    }
                });
            }, {
                rootMargin: '50px' // Start loading 50px before entering viewport
            });

            lazyAds.forEach(ad => adObserver.observe(ad));
        } else {
            // Fallback for browsers without Intersection Observer
            lazyAds.forEach(ad => ad.classList.add('loaded'));
        }
    });
</script>
@endpush
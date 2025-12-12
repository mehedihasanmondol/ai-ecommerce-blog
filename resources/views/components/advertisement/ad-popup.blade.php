@props(['categoryId' => null])

@php
$adDisplayService = app(\App\Modules\Advertisement\Services\AdDisplayService::class);
$adTrackingService = app(\App\Modules\Advertisement\Services\AdTrackingService::class);

$ad = $adDisplayService->getPopupAd($categoryId);
@endphp

@if($ad)
@php
$campaign = $ad['campaign'];
$creative = $ad['creative'];
$adSlot = $ad['slot'];
@endphp

<div id="ad-popup-overlay" class="ad-popup-overlay" style="display: none;">
    <div class="ad-popup-container">
        <button class="ad-popup-close" onclick="closeAdPopup()">&times;</button>

        <div class="ad-popup-content">
            {!! $adDisplayService->renderAd($creative, $adSlot) !!}
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Check if popup was shown recently (cookie-based frequency control)
    function shouldShowPopup() {
        const lastShown = localStorage.getItem('ad_popup_last_shown');
        if (!lastShown) return true;

        const hoursSinceLastShown = (Date.now() - parseInt(lastShown)) / (1000 * 60 * 60);
        return hoursSinceLastShown >= 24; // Show once per day
    }

    function showAdPopup() {
        const overlay = document.getElementById('ad-popup-overlay');
        if (overlay && shouldShowPopup()) {
            overlay.style.display = 'flex';
            localStorage.setItem('ad_popup_last_shown', Date.now().toString());

            // Track impression
            fetch('/ad/impression', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    campaign_id: {
                        {
                            $campaign - > id
                        }
                    },
                    creative_id: {
                        {
                            $creative - > id
                        }
                    },
                    slot_id: {
                        {
                            $adSlot - > id
                        }
                    }
                })
            });
        }
    }

    function closeAdPopup() {
        document.getElementById('ad-popup-overlay').style.display = 'none';
    }

    // Show popup after 5 seconds
    setTimeout(showAdPopup, 5000);

    // Close on overlay click
    document.addEventListener('DOMContentLoaded', function() {
        const overlay = document.getElementById('ad-popup-overlay');
        if (overlay) {
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    closeAdPopup();
                }
            });
        }
    });
</script>
@endpush

@push('styles')
<style>
    .ad-popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .ad-popup-container {
        position: relative;
        max-width: 90%;
        max-height: 90%;
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .ad-popup-close {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #333;
        color: #fff;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        font-size: 20px;
        line-height: 1;
        cursor: pointer;
        transition: background 0.2s;
        z-index: 10;
    }

    .ad-popup-close:hover {
        background: #000;
    }

    .ad-popup-content {
        max-width: 100%;
        overflow: auto;
    }
</style>
@endpush
@endif
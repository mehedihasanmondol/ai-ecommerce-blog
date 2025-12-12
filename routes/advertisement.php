<?php

use App\Modules\Advertisement\Controllers\AdTrackingController;
use Illuminate\Support\Facades\Route;

/**
 * Advertisement tracking routes (public)
 */

// Track ad impression via AJAX
Route::post('ad/impression', [AdTrackingController::class, 'trackImpression'])->name('ad.impression');

// Track ad click and redirect
Route::get('ad/click/{campaign}/{creative}/{slot}', [AdTrackingController::class, 'trackClick'])->name('ad.click');

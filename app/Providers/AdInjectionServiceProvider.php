<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AdInjectionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register Blade directive for in-content ad injection
        Blade::directive('injectContentAds', function ($expression) {
            return "<?php echo app('App\Services\ContentAdInjector')->inject({$expression}); ?>";
        });
    }
}

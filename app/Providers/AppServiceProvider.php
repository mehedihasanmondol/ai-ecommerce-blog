<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use App\Http\View\Composers\CategoryComposer;
use App\Models\User;
use App\Observers\UserObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register ContentAdInjector as singleton
        $this->app->singleton(\App\Services\ContentAdInjector::class, function ($app) {
            return new \App\Services\ContentAdInjector();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register observers
        User::observe(UserObserver::class);

        // Share categories with frontend header
        // Register view composers for header components
        View::composer('components.frontend.header-default', CategoryComposer::class);

        // Register custom Blade directives for currency
        Blade::directive('currency', function ($expression) {
            return "<?php echo currency_format($expression); ?>";
        });

        Blade::directive('currencySymbol', function () {
            return "<?php echo currency_symbol(); ?>";
        });
    }
}

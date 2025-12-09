<?php

namespace Webkul\Sweepstakes\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webkul\Core\Http\Middleware\PreventRequestsDuringMaintenance;
use Webkul\Sweepstakes\Http\Middleware\AuthenticateCustomer;
use Webkul\Sweepstakes\Http\Middleware\CacheResponse;
use Webkul\Sweepstakes\Http\Middleware\Currency;
use Webkul\Sweepstakes\Http\Middleware\Locale;
use Webkul\Sweepstakes\Http\Middleware\Theme;

class ShopServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();
    }

    /**
     * Bootstrap services.
     */
    public function boot(Router $router): void
    {
        $router->middlewareGroup('shop', [
            Theme::class,
            Locale::class,
            Currency::class,
        ]);

        $router->aliasMiddleware('theme', Theme::class);
        $router->aliasMiddleware('locale', Locale::class);
        $router->aliasMiddleware('currency', Currency::class);
        $router->aliasMiddleware('cache.response', CacheResponse::class);
        $router->aliasMiddleware('customer', AuthenticateCustomer::class);

        Route::middleware(['web', 'shop', PreventRequestsDuringMaintenance::class])->group(__DIR__.'/../Routes/web.php');
        Route::middleware(['web', 'shop', PreventRequestsDuringMaintenance::class])->group(__DIR__.'/../Routes/api.php');

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'Sweepstakes');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'Sweepstakes');

        Paginator::defaultView('shop::partials.pagination');
        Paginator::defaultSimpleView('shop::partials.pagination');

        Blade::anonymousComponentPath(__DIR__.'/../Resources/views/components', 'Sweepstakes');

        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register package config.
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/menu.php',
            'menu.customer'
        );
    }
}

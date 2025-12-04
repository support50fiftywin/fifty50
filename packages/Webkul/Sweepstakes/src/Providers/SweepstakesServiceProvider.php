<?php

namespace Webkul\Sweepstakes\Providers;

use Illuminate\Support\ServiceProvider;

class SweepstakesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'sweepstakes');

        // Load Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
		
		$this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );

        // 2. Merge ACL Configuration (NEW)
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php', 'acl'
        );
    }

    public function register()
    {
        //
    }
}

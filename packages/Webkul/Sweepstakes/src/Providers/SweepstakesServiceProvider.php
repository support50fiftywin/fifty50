<?php

namespace Webkul\Sweepstakes\Providers;

use Illuminate\Support\ServiceProvider;


class SweepstakesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');
		$this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');
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
		$this->loadTranslationsFrom(
            dirname(__DIR__) . '/Resources/lang',
            'sweepstakes' // This is your package's namespace
        );
		$this->app->bind(
			\Webkul\Sweepstakes\Repositories\SweepstakeRepository::class,
			function () {
            return new \Webkul\Sweepstakes\Repositories\SweepstakeRepository(
                new \Webkul\Sweepstakes\Models\Sweepstake()
            );
			}
		);
		$this->publishes([
			// Source: Your package's views directory
			__DIR__ . '/../Resources/views' => resource_path('themes/sweepstakes/views'),
		], 'public'); 
		
		 \Webkul\Customer\Models\Customer::resolveRelationUsing('walletAccount', function ($customer) {
        return $customer->hasOne(\Webkul\Sweepstakes\Models\CustomerWallet::class, 'customer_id');
		});
	
    }

    public function register()
    {
       $this->app->register(\Webkul\Sweepstakes\Providers\EventServiceProvider::class);
	  //
    }
}

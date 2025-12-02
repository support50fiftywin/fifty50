<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('role:Admin', fn($user) => $user->hasRole('Admin'));
        Gate::define('role:Merchant', fn($user) => $user->hasRole('Merchant'));
        Gate::define('role:User', fn($user) => $user->hasRole('User'));
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class DynamicAdminLTEProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Change logo only when user is logged in
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->hasRole('Admin')) {
                config([
                    'adminlte.logo' => '<b>Admin</b> Dashboard',
                     'adminlte.logo_img' => 'vendor/adminlte/dist/img/admin.png',
                ]);
            }
            elseif ($user->hasRole('Merchant')) {
                config([
                    'adminlte.logo' => '<b>Merchant</b> Panel',
                    'adminlte.logo_img' => 'vendor/adminlte/dist/img/merchant.png',
                ]);
            }
            elseif ($user->hasRole('User')) {
                config([
                    'adminlte.logo' => '<b>User</b> Dashboard',
                    'adminlte.logo_img' => 'vendor/adminlte/dist/img/user.png',
                ]);
            }
        }
    }
}

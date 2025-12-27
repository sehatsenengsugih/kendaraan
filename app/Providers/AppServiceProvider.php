<?php

namespace App\Providers;

use App\View\Composers\NotificationComposer;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register notification composer for header
        View::composer('partials.header', NotificationComposer::class);

        // Define authorization gates
        Gate::define('manage-users', function ($user) {
            return $user->role === 'super_admin';
        });

        Gate::define('view-audit-logs', function ($user) {
            return $user->role === 'super_admin';
        });

        // Gate untuk akses servis (admin_servis hanya bisa akses servis)
        Gate::define('manage-servis', function ($user) {
            return in_array($user->role, ['super_admin', 'admin', 'admin_servis']);
        });

        // Gate untuk akses menu utama (kendaraan, pajak, dll) - full access
        Gate::define('access-main-menu', function ($user) {
            return in_array($user->role, ['super_admin', 'admin', 'user']);
        });

        // Gate untuk view kendaraan (termasuk admin_servis untuk pilih kendaraan)
        Gate::define('view-kendaraan', function ($user) {
            return in_array($user->role, ['super_admin', 'admin', 'user', 'admin_servis']);
        });

        // Gate untuk akses master data
        Gate::define('access-master-data', function ($user) {
            return in_array($user->role, ['super_admin', 'admin', 'user']);
        });
    }
}

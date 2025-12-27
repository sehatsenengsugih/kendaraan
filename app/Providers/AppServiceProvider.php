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
    }
}

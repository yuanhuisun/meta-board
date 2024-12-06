<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        // Implicitly grant "sys-admin" role all permission checks using can()
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('sys-admin')) {
                return true;
            }
        });

        Vite::prefetch(concurrency: 3);
    }
}

<?php

namespace App\Providers;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFive();

        if (request()->route() !== null) {
            app()->setLocale(LaravelLocalization::getCurrentLocale());
        }
    }
}

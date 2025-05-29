<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Yajra\Address\AddressServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        AddressServiceProvider::class;
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

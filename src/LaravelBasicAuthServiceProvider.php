<?php

namespace nguyenanhung\Laravel\BasicAuth;

use Illuminate\Support\ServiceProvider;

class LaravelBasicAuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/config.php',
            'laravel-basic-auth'
        );
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

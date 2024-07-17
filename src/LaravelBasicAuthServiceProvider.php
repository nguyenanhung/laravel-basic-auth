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
            __DIR__ . '/../config/config-laravel-basic-auth.php',
            'laravel-basic-auth'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../config/config-laravel-basic-cors.php',
            'laravel-basic-cors'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../config/config-laravel-basic-whitelist-ip.php',
            'laravel-basic-whitelist-ip'
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

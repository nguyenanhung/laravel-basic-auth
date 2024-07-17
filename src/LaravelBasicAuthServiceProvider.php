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
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('laravel-basic-auth.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/../config/config.php', 'laravel-basic-auth'
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

<?php

namespace PartiMate\ThrottleMate;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/throttlemate.php' => config_path('throttlemate.php'),
        ], 'throttlemate');
        $this->mergeConfigFrom(__DIR__ . '/../config/throttlemate.php', 'throttlemate');
        $this->app['router']->aliasMiddleware('throttle.mate', ThrottleMiddleware::class);
    }

    public function register()
    {
        $this->app->singleton(AdaptiveThrottler::class, function ($app) {
            return new AdaptiveThrottler(config('throttlemate'));
        });
    }
}

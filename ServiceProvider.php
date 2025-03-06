<?php

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/throttlemate.php' => config_path('throttlemate.php'),
        ], 'throttlemate');

        $this->mergeConfigFrom(__DIR__.'/../config/throttlemate.php', 'throttlemate');
    }

    public function register()
    {
        $this->app->singleton(AdaptiveThrottler::class);
    }
}
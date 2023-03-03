<?php

namespace App\Providers;

use App\Services\ModifyLogService;
use App\Services\WeatherService;
use Illuminate\Support\ServiceProvider;

class ModifyLogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('App\Services\ModifyLogService', function ($app) {
            return new ModifyLogService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

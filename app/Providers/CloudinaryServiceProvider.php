<?php

namespace App\Providers;

use App\Services\CloudinaryService;
use Illuminate\Support\ServiceProvider;

class CloudinaryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CloudinaryService::class, function ($app) {
            return new CloudinaryService();
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
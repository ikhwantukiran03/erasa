<?php

namespace App\Providers;

use App\Services\SupabaseStorageService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Supabase Storage Service
        $this->app->singleton(SupabaseStorageService::class, function ($app) {
            return new SupabaseStorageService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
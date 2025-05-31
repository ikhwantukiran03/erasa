<?php

namespace App\Providers;

use App\Services\CloudinaryService;
use App\Services\SupabaseStorageService;
use App\Models\Promotion;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        
        // Register Cloudinary Service
        $this->app->singleton(CloudinaryService::class, function ($app) {
            return new CloudinaryService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share active promotions with all views for the promotional banner
        View::composer('*', function ($view) {
            $activePromotions = Promotion::where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
            
            $view->with('globalActivePromotions', $activePromotions);
        });
    }
}
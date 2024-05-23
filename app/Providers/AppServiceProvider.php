<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Singletons\ModelManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ModelManager::class, function ($app) {
            return ModelManager::getInstance();
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

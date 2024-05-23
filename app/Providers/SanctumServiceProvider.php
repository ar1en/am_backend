<?php
#Провайдер для использования кастомной модели Sanctum#

namespace App\Providers;

use Laravel\Sanctum\Sanctum;
use App\Models\PersonalAccessToken;
use Illuminate\Support\ServiceProvider;

class SanctumServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }

    public function register()
    {
        //
    }
}

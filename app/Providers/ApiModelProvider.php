<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ApiModelProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //Создаем массив соотношений имен моделей и названий api
        $models = [];
        $modelDir = app_path('Models/DBModels/Data');
        foreach (scandir($modelDir) as $file) {
            if (!in_array($file, ['.', '..'])) {
                $modelName = pathinfo($file, PATHINFO_FILENAME);
                $apiName = Str::kebab(substr($modelName, 1));
                $models[$modelName] = $apiName;
            }
        }

        $this->app->instance('models', $models);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

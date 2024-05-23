<?php

namespace App\Http\Controllers\api\v1\Universal;

use App\Http\Controllers\Controller;
use App\Singletons\ModelManager;
use Illuminate\Http\Request;
use ReflectionException;

class ShowController extends Controller
{
    /**
     * @throws ReflectionException
     */
    public function __invoke(String $apiName, String $id, String $with = null) {
        $mm = ModelManager::getInstance();
        $modelName = $mm->getModelNameByApi($apiName, false);
        $resourceName = $mm->getModelResourceName($modelName, true, "Default");

        if (!$modelName or !$resourceName) {
            return response()->json(['error' => sprintf('Model or Resource not found for <%s>', $apiName)], 404);
        }

        $model = $mm->getModelNameByApi($apiName)::with($this->withArray($with))
            ->find($id);

        return new $resourceName($model);
    }

    private function withArray($with): array{
        return is_null($with) ? [] : explode("-", $with);
    }
}

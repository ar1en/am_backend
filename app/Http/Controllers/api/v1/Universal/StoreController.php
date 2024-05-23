<?php

namespace App\Http\Controllers\api\v1\Universal;

use App\Singletons\ModelManager;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ReflectionException;

class StoreController extends Controller
{
    /**
     * @throws ReflectionException
     */
    public function __invoke(String $apiName, Request $request): JsonResponse
    {
        $mm = ModelManager::getInstance();
        $modelName = $mm->getModelNameByApi($apiName, false);

        if ($modelName) {
            $response = DB::transaction(function () use ($request, $modelName) {

                $model = new (config('app.models_path') . $modelName)();

                $validatedData = $request->validate((config('app.requests_path').$modelName."DefaultRequest")::rules());
                $this->transformKeysNameToCamelCase($validatedData);

                //разделяем массив полученных из json данных на поля сущности и связи
                $fields = Arr::only($validatedData, $model->getFillable());

                $relations = Arr::except($validatedData, array_keys($fields));

                $model->fill(array_merge($fields, [
                    $model::F_AUTHOR => Auth::guard('sanctum')->user()['user'],
                    ($model->validFromUntil) ? $model::F_VALIDFROM : null => ($model->validFromUntil) ? date("Y-m-d H:i:s", time()) : null,
                ]));

                $model->save();

                foreach ($relations as $key => $value) {
                    if (method_exists($model, $key)) {
                        $this->setRelationship($model, $key, $value);
                    }
                }

                return $model;
                //return new DUsersDefaultResource($model);
            });
        } else $response = sprintf('%s model does not exist', $apiName);

        return response()->json($response, 200);
    }

    private function setRelationship(&$model,$key, $value): void{
        $relationship = $model->{$key}();
        switch (true) {
            case $relationship instanceof BelongsTo:
                $model->{$key}()->associate($value);
                break;
            case $relationship instanceof BelongsToMany:
                if (is_array($value)){
                    foreach($value as $relatedValue){
                        $this->attachRelatedEntity($model, $key, $relatedValue);
                    }
                } else $this->attachRelatedEntity($model, $key, $value);
                break;
            default:
                break;
        }
    }

    private function attachRelatedEntity(&$model, $key, $value): void{
        $model->{$key}()->attach($value,
            [
                'id' => Str::uuid(),
                'author' => Auth::guard('sanctum')->user()['user'],
                //'validFrom' => $model->validFromUntil ? date("Y-m-d H:i:s", time()) : null,
                'validFrom' => date("Y-m-d H:i:s", time()),
                'created_at' => $model->timestamps ? date("Y-m-d H:i:s", time()) : null,
                'updated_at' => $model->timestamps ? date("Y-m-d H:i:s", time()): null
            ]);
    }

    private function transformKeysNameToCamelCase(&$data): void {
        foreach ($data as $key => $value) {
            $newKey = Str::camel($key);
            if ($newKey !== $key) {
                $data[$newKey] = $value;
                unset($data[$key]);
            }
        }
    }
}

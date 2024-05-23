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

class UpdateController extends Controller
{
    /**
     * @throws ReflectionException
     */
    public function __invoke(String $apiName, String $id, Request $request): JsonResponse {
        $mm = ModelManager::getInstance();
        $modelName = $mm->getModelNameByApi($apiName, false);

        if ($modelName) {
            $response = DB::transaction(function () use ($id, $request, $modelName) {

                //$model = new (config('app.models_path') . $modelName)();
                $model = (config('app.models_path') . $modelName)::find($id);

                $validatedData = $request->validate((config('app.requests_path').$modelName."UpdateRequest")::rules());
                $this->transformKeysNameToCamelCase($validatedData);

                //разделяем массив полученных из json данных на поля сущности и связи
                $fields = Arr::only($validatedData, $model->getFillable());

                $relations = Arr::except($validatedData, array_keys($fields));

                $model->update($fields);
                $model->save();

                foreach ($relations as $key => $value) {
                    foreach ($value as $action => $subValue) {
                        switch ($action) {
                            case 'add':
                                foreach ($subValue as $data){
                                    if (method_exists($model, $key)) {
                                        $this->setRelationship($model, $key, $data);
                                    }
                                }
                                break;
                            case 'del':
                                foreach ($subValue as $data){
                                    $model->{$key}()->withTimestamps()->updateExistingPivot($data, ['deleted_at' => date("Y-m-d H:i:s", time())]);
                                }
                            break;
                        };
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
        dump($model->timestamps);
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

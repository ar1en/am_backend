<?php
namespace App\Singletons;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\NoReturn;
use ReflectionException;

class ModelManager
{
    private static ?ModelManager $instance = null;
    private array $modelsMetaData = [];
    //private static string $modelsPath = 'Models/DBModels/Data';
    //private static string $resourcesPath = 'Http/Resources/api/v1';
    //private static string $requestsPath = 'Http/Requests';

    private function __construct() {}

    /**
     * @throws ReflectionException
     */
    public static function getInstance():ModelManager {
        if (!self::$instance) {
            self::$instance = new ModelManager();
            self::$instance->init();
        }
        return self::$instance;
    }

    /**
     * @throws ReflectionException
     */
    #[NoReturn]
    private function init():void {
        // Сканируем директорию и получаем список файлов
        $path = str_replace(['App\\', '\\'], ['', '/'], config('app.models_path'));
        $files = scandir(app_path($path));

        // Проходим по списку файлов и определяем, какие из них являются моделями
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            //Получаем информации о классе и формируем путь по которому он доступен для создания
            $modelName = pathinfo($file, PATHINFO_FILENAME);
            $modelPath = config('app.models_path');
            $model = new \ReflectionClass($modelPath . $modelName);

            // Если класс является моделью, то добавляем его в список моделей
            if ($model->isSubclassOf('Illuminate\Database\Eloquent\Model')) {

                $this->modelsMetaData[$modelName] = [
                    'api' => Str::kebab(substr($modelName, 1)),
                    'resources' => $this->getModelData($modelName, 'Resources'),
                    'requests' => $this->getModelData($modelName, 'Requests'),
                    //'relations' => $this->getRelationshipMethods($model),
                    ];
            }
        }
        //dump($this->modelsMetaData);
    }

    /**
     * @throws ReflectionException
     */

    private function getModelData($modelName, $dataType):array {
        $result= [];

        switch ($dataType) {
            case 'Resources':
                $path = config('app.resources_path');
                $class = 'Illuminate\Http\Resources\Json\JsonResource';
                $classPostfix = 'Resource';
                break;
            case 'Requests':
                $path = config('app.requests_path');
                $class = 'Illuminate\Foundation\Http\FormRequest';
                $classPostfix = 'Request';
                break;
            default:
                return $result;
        }

        $files = scandir(str_replace(['App\\', '\\'], ['', '/'], app_path($path)));

        foreach ($files as $file) {
            if ($file == '.' || $file == '..') continue;

            $dataName = pathinfo($file, PATHINFO_FILENAME);

            if (Str::startsWith($dataName, $modelName)) {
                $resource = new \ReflectionClass($path . $dataName);
                if ($resource->isSubclassOf($class)) {
                    $result += [str_replace([$modelName, $classPostfix], '', $dataName) => $dataName];
                }
            }
        }
        return $result;
    }

    private function getRelationshipMethods(\ReflectionClass &$model): array {
        // Получаем все методы модели
        $methods = $model->getMethods();
        //dump($methods);
        // Определяем методы отношений
        $relationshipMethods = [];

        $counter = 5;
        //dump($counter);
        foreach ($methods as $method) {
            // Определяем, относится ли метод к отношениям
            $returnType = $method->getReturnType();

            //dump($counter);
            if ($counter > 0){
                dump($model->getName());
                dump($method->getName());
                dump($returnType);
                dump(is_subclass_of($method, Relation::class));
                $counter --;
            }


            //if ($returnType instanceof \ReflectionNamedType && is_subclass_of($returnType->getName(), Relation::class)) {
            //dump()
            //if($returnType) dump($method->getName());
            /*if (is_subclass_of($returnType->getName(), Relation::class)) {

                // Если да, добавляем его в список методов отношений
                $relationshipMethods[] = $method->getName();
            }*/
        }

        return $relationshipMethods;
    }

    public function getModelNameByApi(string $api, bool $withPath = true): string {
        $result = '';
        foreach ($this->modelsMetaData as $modelName => $modelData) {
            if ($modelData['api'] === $api) {
                $result = $modelName;
            }
        }
        if ($withPath && $result) $result = config('app.models_path') . $result;
        return $result;
    }

    public function modelExistsByApi(string $api): bool {
        return $this->getModelNameByApi($api) !== null;
    }

    /*public function getModelInstancePath(string $modelName, bool $withModelName = true):?string {
        //dump($this->modelsMetaData);
        //return $modelName ? $this->modelsMetaData[$modelName]['instancePath'] : $this->modelsMetaData[$modelName]['instancePath'] . $modelName;
        return $this->modelsMetaData[$modelName]['instancePath'];
    }*/

    public function getModelResourceName(string $modelName, bool $withPath = true, string $resourceName = 'Default'):?string {
        $result = Arr::get($this->modelsMetaData, "{$modelName}.resources.{$resourceName}");
        if (!$result) return null;
        if ($withPath) $result = config('app.resources_path') . $result;
        return $result;
    }
}

<?php


namespace App\Utils\Helpers\JsonData;


use App\Utils\Helpers\Exception\AppException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;
use JsonSerializable;

/**
 *
 * Базовый класс для объектов,
 * которые будут конвертироваться в json и обратно
 * Для всех параемтров API классов входы и выходы
 * должны быть наследованы от JsonData или JsonModel
 *
 */
abstract class JsonData implements Arrayable, JsonSerializable {


    /**
     * Преобразует множество array[][]
     * в множесто объектов static[]
     *
     * @param Arrayable[] $list - миссив array[][]
     * @return static[]
     */
    public static function collection($list) {
        return
            array_map(
                function ($l) {
                    $obj = new static();
                    $obj->fromArray($l instanceof Arrayable ? $l->toArray() : (array)$l);
                    return $obj;
                },
                ($list instanceof Collection || $list instanceof \Illuminate\Support\Collection)
                    ? $list->all()
                    : $list
            );
    }

    /**
     * @return array
     * @throws AppException
     */
    public function jsonSerialize() {
        return $this->toArray();
    }


    /**
     * Преобразует объект в массив.
     * Данный метод должен быть перезаписан в каждом классе, который наследован от JsonData.
     * Генерауия этих методов производится через консольную команду
     *
     * @return array
     * @throws JsonDataException
     */
    public function toArray() {
        if (1) {
            throw new JsonDataException('Update JsonData classes: php artisan apiClasses:make');
        }
        return [];
    }


    /**
     * Преобразцет массив в объект static.
     * Данный метод должен быть перезаписан в каждом классе, который наследован от JsonData.
     * Генерауия этих методов производится через консольную команду
     *
     * @param array $data
     * @return static
     * @throws JsonDataException
     */
    public function fromArray(array $data) {
        if ($data) {
            throw new JsonDataException('Update JsonData classes: php artisan apiClasses:make');
        }
        return new static();
    }


}

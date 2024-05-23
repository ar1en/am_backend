<?php


namespace App\Utils\Helpers\JsonData;


use App\Utils\Helpers\Formats\HDateTime;
use DateTime;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use Throwable;


/**
 * Class JsonModelTransformer
 *
 * Содержит набор типовых функций для работы с классаом JsonModel
 * В частности это функции конвертации значений свйоств
 * для преобразования в json и обратно
 *
 *
 * @package App\Utils\Helpers\JsonData
 */
abstract class JsonModelTransformer implements Arrayable, JsonSerializable {


    /*
     * Define convert methods for some data types
     */
    public const JF_DATETIME = 'jsonDateTime';     // convert DateTime to jsonable format
    public const JF_DATE = 'jsonDate';
    public const JF_JSON_MODEL_OBJECT = 'jsonObject';
    public const JF_JSON_MODEL_COLLECTION = 'jsonCollection';
    public const JF_JSON_DATA_OBJECT = 'jsonDataObject';


    /**
     * Возвращает имя класса объекта Model, указанного в JsonMode в поле $fields
     *
     * @param array $fieldConfig
     * @return string
     * @throws JsonDataException
     */
    private static function getTypeClass($fieldConfig) {

        if (!isset($fieldConfig[2])) {
            throw new JsonDataException('Class JsonMode not defined for ' . static::class . '->' . $fieldConfig[0]);
        }
        $class = $fieldConfig[2];

        if (!class_exists($class)) {
            throw new JsonDataException("Class not exists [{$class}] for " . static::class . '->' . $fieldConfig[0]);
        }
        return $class;
    }

    /**
     * Преобразует значение даны в строку json значения
     *
     * @param $dateValue
     * @return DateTime|string
     * @noinspection PhpUnused
     */
    public function jsonDate($dateValue) {
        if (!($dateValue instanceof DateTime)) {
            $dateValue = HDateTime::strToDate($dateValue);
        }
        return HDateTime::toUIDate($dateValue);
    }

    /**
     * Преобразуется значения даты из строки в Дату
     * @param $strDate
     * @return DateTime|string|null
     * @noinspection PhpUnused
     */
    public function _jsonDate($strDate) {
        return HDateTime::strToDate($strDate);
    }


    /**
     * Преобразуется значения даты и времени в троку json
     *
     * @param $dateTimeValue
     * @return DateTime|string
     * @noinspection PhpUnused
     */
    public function jsonDateTime($dateTimeValue) {
        if (!($dateTimeValue instanceof DateTime)) {
            $dateTimeValue = HDateTime::strToDate($dateTimeValue);
        }
        return HDateTime::toUIDateTime($dateTimeValue);
    }

    /**
     * Преобразуется значения строки json в дату и время
     *
     * @param $dateTimeString
     * @return DateTime|string|null
     * @noinspection PhpUnused
     */
    public function _jsonDateTime($dateTimeString) {
        return HDateTime::strToDate($dateTimeString);
    }


    /**
     * Return JsonModel object for given field value
     *
     * @param null|string|array $value
     * @param null|string|array $fieldConfig - may be name of JsonModel class or array [JsonModel::class,<id_field_name>]
     * @return mixed
     * @throws JsonDataException
     * @noinspection PhpUnused
     */
    public function jsonObject($value, $fieldConfig) {
        $class = self::getTypeClass($fieldConfig);
        try {
            return $value ? new $class($value) : null;
        } catch (Throwable $ex) {
            $the = get_class($this);
            throw new JsonDataException("Can not create [{$class}] at [{$the}] params:" . json_encode($fieldConfig), 500, $ex);
        }
    }

    /**
     * Return converted from string or array value as JsonModel object
     *
     * @param null|string|array           $value
     * @param null|string|array|JsonModel $fieldConfig
     * @return mixed
     * @throws JsonDataException
     * @noinspection PhpUnused
     */
    public function _jsonObject($value, $fieldConfig) {

        /** @var JsonModel $class */
        $class      = self::getTypeClass($fieldConfig);
        $jsonObject = $class::fromArrayStatic($value);

        // set id for related field
        if (isset($fieldConfig[3])) {
            /** @var JsonModel $me */
            $me = $this;
            $me->setModelAttribute(
                $fieldConfig[3],
                $jsonObject
                    ? $jsonObject->getModel()->getKey()
                    : null
            );
        }

        return $jsonObject ? $jsonObject->getModel() : null;
    }


    /**
     * Преобразуется массив объектов JsonModel в массив значений
     *
     * @param       $jsonModelCollection
     * @param array $fieldConfig
     * @return mixed
     * @throws JsonDataException
     * @noinspection PhpUnused
     */
    public function jsonCollection($jsonModelCollection, $fieldConfig) {
        /** @var JsonModel $class */
        $class = self::getTypeClass($fieldConfig);
        return $class::collection($jsonModelCollection);
    }

    /**
     * Преобразует массив значений к коллекцию JsonModel объектов
     * @param array $value
     * @param array $fieldConfig
     * @return mixed
     * @throws JsonDataException
     * @noinspection PhpUnused
     */
    public function _jsonCollection($value, $fieldConfig) {
        /** @var JsonModel $class */
        $class = self::getTypeClass($fieldConfig);
        $arr   = $class::fromArrayArrayStatic($value);
        if ($arr === null) {
            return null;
        }
        return array_map(function ($l) {
            return $l->getModel();
        }, $arr);
    }


    /**
     * Преобразует JsonData объект в массив значений
     *
     * @param       $value
     * @param array $fieldConfig
     * @return mixed
     * @throws JsonDataException
     */
    public function jsonDataObject($value, $fieldConfig) {
        $class = self::getTypeClass($fieldConfig);
        return new $class($value);
    }

    /**
     * Преобразует массив значений в объект JsonData
     * @param       $value
     * @param array $fieldConfig
     * @return JsonData
     * @throws JsonDataException
     */
    public function _jsonDataObject($value, $fieldConfig) {
        $class = self::getTypeClass($fieldConfig);
        /** @var JsonData $obj */
        $obj = new $class($value);
        $obj->fromArray($value);
        if (isset($fieldConfig[3])) {  // 3-й параметр конфиурации - имя поля с id объекта
            /** @var JsonModel $me */
            $me = $this;
            $me->setModelAttribute($fieldConfig[3], isset($value['id']) ? $value['id'] : null);
        }
        return $obj;
    }


}

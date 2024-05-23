<?php


namespace App\Utils\Helpers\JsonData;


use App\Utils\Helpers\DB\Model\MBase;
use App\Utils\Helpers\Exception\AppException;
use App\Utils\Helpers\UUID\UUID;
use DateTime;
use Illuminate\Database\Eloquent\Collection;

/**
 * Базовый класс для объектов, используемых как входы и выходы API функций
 * Обеспечивает преобразование в json и обратно
 * В отличии от JsonData релизует интерфейс для работы с объетами Model
 *
 * @package App\BPM\Http\Api\Controller
 */
abstract class JsonModel extends JsonData {


    /**
     * Обязательно надо указать класс объекта Model, например DUser.
     * Именно объект этог отипа будет сериализован и воссоздан
     *
     * @var string
     */
    protected static string $model = '';

    /**
     * Определяем перечень полей, который будут трансформированы в json и обратно
     *
     * Структура каждого такого свйоства имеет вид:
     *  'Json name of property' => [ 'php name of property', 'transform function name', 'transform function parameter' ]
     *
     * Если список полей не указан, то в json будет конвертирован
     * список всех полей объекта self::{$model}::s_getJsonable()
     *
     * Пример заполнения параметра
     *  self::$fieldsList = [
     *      'login'         =>DUser::F_LOGIN,           // простое строковое значение
     *      'isBlocked'     =>DUser::F_IS_BLOCKED,      //
     *      'dtCreated'     =>[DUser::F_DT_CREATED,'formatDate'], // при конвертации в json и обратно используется форматирование даты
     *      'relProcessId'  =>[DUser::F_PROCESS,self::JF_JSON_OBJECT,'App\Model\JsonConvertClass'],  // свойство ковертируется как JsonModel
     *      'relProcessIds'  =>[DUser::F_PROCESSES,self::JF_JSON_COLLECTION,'App\Model\JsonConvertClass'], // свойство конверсируется как массив объектов JsonMode
     *  ];
     *
     * @var array|string[]
     */
    public static array $fieldsList = [];


    /**
     * Дополнительный список полей, которые будут конвертироваться
     * в json и обратно в дополнение к базовому списку полей self::$model
     *
     * @var array[] | string[]
     */
    public static array $addFieldsList = [];


    /**
     * Список свойств, которые будут конвертироваться в json
     * (кэшируем, чтобы 10 раз не переформировывать его)
     *
     * @var null|array
     */
    private static array $_getFieldsCache = [];

    /**
     * Current object with data
     * @var MBase
     */
    private MBase $_modelObject;

    /**
     * Функция для фильтрации перечня свойств, конвертируемых в json
     * $this->_filterProperties($fieldName)=>boolean, false - field is invisible
     *
     * @var null|callable
     */
    private $_filterProperties;

    /**
     *
     * @param array|MBase   $modelOrArray             - или список свойств и значений или сам объект класса Model
     * @param null|callable $callbackFilterProperties - фильтр для свойств при конвертации в json
     */
    public function __construct($modelOrArray = null, $callbackFilterProperties = null) {

        $this->_filterProperties = $callbackFilterProperties;

        // created with null - make new model
        if ($modelOrArray === null) {
            $cls          = static::$model;
            $modelOrArray = new $cls();
        }

        // created with array
        if (is_array($modelOrArray)) {
            $this->fromArray($modelOrArray, $callbackFilterProperties);
        } else {
            $this->_modelObject = $modelOrArray;
        }

    }

    /**
     * Return jsonable array.
     *
     * @param array         $list
     * @param callable|null $callbackFilter
     * @param callable|null $callbackFilterProperties
     * @return static[]
     */
    public static function collection($list, ?callable $callbackFilter = null, ?callable $callbackFilterProperties = null) {

        if ($list === null) {
            return [];
        }

        if ($list instanceof Collection) {
            $list = $list->all();
        }

        if ($callbackFilter) {
            $list = array_values(array_filter($list, $callbackFilter));
        }

        return array_map(function ($el) use ($callbackFilterProperties) {
            return new static($el, $callbackFilterProperties);
        }, $list);

    }


    /**
     * Формирует, кэширует и возвращает список свйоств,
     * которые будут конвертироваться в json  и обратно
     *
     * @param callable $filterFields
     * @return string[][]
     */
    public static function getFields(?callable $filterFields = null) {

        // проверяем наличие кэшированного списка свойств
        if (array_key_exists(static::class, self::$_getFieldsCache)) {
            return static::$_getFieldsCache[static::class];
        }

        // при необходимости берем список свойств для конвертации из объекта Model
        /** @var MBase $cls */
        $cls    = static::$model;
        $fields = empty(static::$fieldsList)
            ? $cls::s_getJsonable()
            : static::$fieldsList;

        // объединяем со спиком дополнительных полей
        $result = array_merge($fields, static::$addFieldsList);

        // если конфигурация поля представлена не массивом, а строкой, преобразуем в массив
        $result = array_map(function ($el) { return is_array($el) ? $el : [$el]; }, $result);

        // кэшируем список конвертируемых свойств
        static::$_getFieldsCache[static::class] = $filterFields
            ? array_filter($result, $filterFields)  // и при необходимости ограничиваем перечень свойств
            : $result;

        return static::$_getFieldsCache[static::class];

    }

    /**
     * Странная вспомогательная функция, не понятно где используется...
     *
     * @param array|\Illuminate\Support\Collection|Collection $list
     * @param callable|null                                   $callback
     * @return array|Collection|\Illuminate\Support\Collection
     */
    public static function each($list, ?callable $callback) {
        if ($list) {
            foreach ($list as $key => $item) {
                $callback($item, $key);
            }
        }
        return $list;
    }


    /**
     * Конвертаиця объекта в массив
     *
     * @return array
     */
    public function toArray() {

        // for each property
        return array_map(function ($item) {
            // get attribute name
            $attrName = is_array($item) ? $item[0] : $item;
            // check - is simple attribute or
            if (is_array($item) && isset($item[1]) && $item[1]) {
                // defined function or it is relation with class convert
                $funOrClass = $item[1];
                if (method_exists($this, $funOrClass)) {
                    return $this->$funOrClass($this->_modelObject[$attrName], $item);
                } else {
                    throw new AppException("Property function [{$funOrClass}] of [" . static::class . "] JsonModel dos not exists!");
                }

            } else {
                // return attribute value
                return $this->_modelObject->getAttribute($attrName);
            }
        }, static::getFields($this->_filterProperties));

    }

    /**
     * Заолняет свойства объекта из массива значений
     *
     * @param array         $data                     - массив значений
     * @param null|callable $callbackFilterProperties - фильтр перечня полей, которые будут конвертироваться
     */
    public function fromArray(array $data, $callbackFilterProperties = null) {

        // создаем базовый объект
        /** @var MBase $m */
        $m = $this->_modelObject = new static::$model();

        // если нужно - устанавливаем ключ id
        if (!array_key_exists($m->getKeyName(), $data)) {
            $m->setAttribute($m->getKeyName(), UUID::newV4());
        }

        // берем список полей и устанавливаем для них значения
        $fieldsIndex = static::getFields($callbackFilterProperties);
        foreach ($fieldsIndex as $fieldConfig) {
            $this->setModelProperty($data, $fieldConfig);
        }

    }

    /**
     * Устанавливаем значение свойства объекта Model из массива значений.
     *
     * @param array $data
     * @param array $fieldConfig
     */
    private function setModelProperty(array $data, array $fieldConfig) {

        // имя свойства
        $modelProperty = $fieldConfig[0];

        if (!array_key_exists($modelProperty, $data) && method_exists($this->_modelObject, $modelProperty)) {

            // нет знаения для свойства - ставим null
            $this->_modelObject->setAttribute($modelProperty, null);

        } else {

            $value = $data[$modelProperty];

            // функция конвертации значения свойства не определена?
            if (!isset($fieldConfig[1])) {

                // ставим значения напрямую в свойство Model
                $this->setModelAttribute($modelProperty, $value);

            } else {

                // has function to convert - set value throw function
                $fun = '_' . $fieldConfig[1]; //  back conversion
                if (method_exists($this, $fun)) {

                    // конвертируем значение специальной функцией
                    $v = $this->$fun($value, $fieldConfig);
                    // set value
                    $this->setModelAttribute($modelProperty, $v);

                }

            }

        }

    }


    /**
     * Конвертируем список значений свойств в список объектов JsonModel
     *
     * @param array|null $data - список значений свойств объекта
     * @return JsonModel|null
     */
    public static function fromArrayStatic(?array $data) {
        if ($data === null) {
            return null;
        }
        $obj = new static();
        $obj->fromArray($data);
        return $obj;
    }

    /**
     * Создает коллекция JsonModel из списка спиской свойств
     *
     * @param array|null $array
     * @return JsonModel[]|array|null[]|null
     */
    public static function fromArrayArrayStatic(?array $array) {
        if ($array === null) {
            return null;
        }
        return array_map(function ($el) {
            return static::fromArrayStatic($el);
        }, $array);
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize() {
        return $this->toArray();
    }


    /**
     * @return MBase
     */
    public function getModel() {
        return $this->_modelObject;
    }

    /**
     * @param MBase $obj
     */
    public function setModel(MBase $obj) {
        $this->_modelObject = $obj;
    }

    /**
     * @param string $name
     * @return DateTime|mixed|null
     */
    public function getModelAttribute(string $name) {
        return $this->_modelObject->getAttribute($name);
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function setModelAttribute(string $name, $value) {
        $this->_modelObject->setAttribute($name, $value);
    }


    /**
     * Возвращает массив значений свойств объекта Model
     * который изменены по сравнению с текущими свойствами Model.
     *
     * @param array $changesList - список значений
     * @return array - список значений, которые изменились
     * @throws AppException
     */
    public function getChangedModelFields(array $changesList) {

        $changes = [];
        $fields  = static::getFields($this->_filterProperties);

        // перебираем все новые значения
        foreach ($changesList as $fieldName => $v) {

            // check - пропускаем пустые значения , почему-то ?
            if (!$v) {
                continue;
            }

            // проверка наличия свойств в объекте - оно должно быть
            if (!array_key_exists($fieldName, $fields)) {
                throw new JsonDataException("Property [{$fieldName}] of Update request not exists in updatable objects [" . get_class($this) . "]");
            }

            // get field name in Model class
            $modelName = $fields[$fieldName][0];

            // set value with Model name
            $changes[$modelName] = $this->getModelAttribute($modelName);

            // если свойство является объектом Model и имеет ссыдку на поле с id объекта,
            // то дополнительно надо указать свойства ..._id для этого объета
            if (isset(static::$addFieldsList[$fieldName][3])) {
                $relatedModelFieldName           = $fields[$fieldName][3];
                $changes[$relatedModelFieldName] = is_object($changes[$modelName])
                    ? $changes[$modelName]->id  // set id of related object to related field
                    : $changes[$modelName];
            }

        }

        return $changes;

    }

}

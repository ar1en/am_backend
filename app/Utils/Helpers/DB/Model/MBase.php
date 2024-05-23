<?php /** @noinspection PhpMissingFieldTypeInspection */


namespace App\Utils\Helpers\DB\Model;

use App\Utils\Helpers\DB\Fix\ParamFixLimit;
use App\Utils\Helpers\DB\HDB;
use App\Utils\Helpers\Formats\HDateTime;
use App\Utils\Helpers\Formats\HString;
use DateTime;
use Illuminate\Database\Eloquent\Model;

/**
 * Базовый класс для все Model приложения
 *
 * @property string id - все Model приложения должны иметь свйоства ключа объекта
 *
 * @package App\BPM\Model\Base
 */
//abstract class MBase extends MBaseBulk {
abstract class MBase extends Model {

    // добавляем устранитель ошибки для MSSQL в котором есть лимит на количество параметров
    // для запроса 2100 штук
    use ParamFixLimit;

    // подключаем методы для работы с БД
    use MBaseDBHelpers;

    /** @var string - имя поля таблицы являющегося ключем */
    public const F_ID = 'id';

    // fields names prefixes, define default data types of fields by name
    public const FT_UNIXTIME = 'unixtime';
    public const FT_BOOL_PREFIX = 'is_';
    public const FT_DATE_PREFIX = 'date_';
    public const FT_DATETIME_PREFIX = 'datetime_';
    public const FT_UNIXTIME_PREFIX = 'ut_';

    // field data types
    public const FT_BOOL = 'bool';
    public const FT_INT = 'int';
    public const FT_FLOAT = 'float';
    public const FT_DATE = 'date';
    public const FT_DATETIME = 'datetime';


    /** @var string[] - перечень полей дополнительно, для которых будет преобразование типа значения */
    protected static array $fieldTypes = [
        // example: 'need_psp' => 'bool', 'str'=>'int', 'cost'=>'float', 'dt'=>'date'
    ];

    /** @var array|string[] - список префиксов и типов данных */
    private static array $fieldTypesPrefix = [
                self::FT_BOOL_PREFIX=>self::FT_BOOL,
                self::FT_DATE_PREFIX=>self::FT_DATE,
                self::FT_DATETIME_PREFIX=>self::FT_DATETIME,
                self::FT_UNIXTIME_PREFIX=>self::FT_UNIXTIME
    ];


    /** @var bool - как то не прижились даты в моделях... из-за mssql */
    /** @noinspection PhpUnused */
    public $timestamps = false;

    /** @var string - во всех объектах ключ имеет тип guid */
    protected $keyType = 'uuid';

    /** @var bool - во всех объектам не применяется автоинкрементирование */
    public $incrementing = false;

    /** @var string - во всех объектах используем по-умолчанию ключ id */
    /** @noinspection PhpUnused */
    protected $primaryKey = self::F_ID;

    /** @var array */
    protected $fillable = [];

    /** @var array */
    protected $visible = [];

    /** @var array - список полей для преобразования в json */
    protected static array $jsonable = [];

    /**
     * User for extended attributes of model object.
     * @var array
     */
    private array $additionalAttributes = [];

    /**
     * Устанавливает дополнительный атрибут в модель
     *
     * @param string $name
     * @param mixed  $value
     */
    public function setAdditionalAttribute(string $name, $value) {
        $this->additionalAttributes[$name] = $value;
    }

    /**
     * Возращает значение дополнительного атрибута модели
     *
     * @param string $name
     * @return mixed|null
     */
    public function getAdditionalAttribute(string $name) {
        if (isset($this->additionalAttributes[$name])) {
            return $this->additionalAttributes[$name];
        }
        return $this->additionalAttributes[$name] = null;
    }

    /**
     * Return array of visible table fields.
     *
     * @param null $addColumnNames
     * @return array
     */
    public static function s_getVisible($addColumnNames = null) {
        $self = new static();
        $cols = $self->getVisible();
        if (is_array($addColumnNames)) {
            foreach ($addColumnNames as $col) {
                $cols[] = $col;
            }
        }
        if (is_string($addColumnNames)) {
            $cols[] = $addColumnNames;
        }
        return $cols;
    }

    /**
     * Возвращает список полей, которые можно конвертировать в json
     *
     * @return array
     */
    public static function s_getJsonable() {
        return static::$jsonable;
    }


    /**
     * Return array of visible table fields.
     *
     * @param array|string|null $addColumnNames
     * @return array
     * @noinspection PhpUnused
     */
    public static function s_getFillable($addColumnNames = null) {
        $self = new static();
        $cols = $self->getFillable();
        if (is_array($addColumnNames)) {
            foreach ($addColumnNames as $col) {
                $cols[] = $col;
            }
        }
        if (is_string($addColumnNames)) {
            $cols[] = $addColumnNames;
        }
        return $cols;
    }


    /**
     * Возвращает список колонок с добавлением имени таблицы.
     * @param null|array $addColumnNames
     * @return array
     */
    public static function s_getVisibleWithTable($addColumnNames = null) {
        $self = new static();
        $cols = $self->getVisible();
        $tab  = $self->getTable();
        foreach ($cols as &$colRef) {
            $colRef = $tab . '.' . $colRef;
        }
        if (is_array($addColumnNames)) {
            foreach ($addColumnNames as $col) {
                $cols[] = $col;
            }
        }
        if (is_string($addColumnNames)) {
            $cols[] = $addColumnNames;
        }
        return $cols;
    }

    /**
     * Возвращает имя таблицы для модели.
     * @return string
     */
    public static function table() {
        $self = new static();
        return $self->table;
    }

    /**
     * Добавляет имя таблицы и точку перед именем колонки
     * @param string $colName
     * @param bool   $addPrefix
     * @return string
     */
    public static function field($colName, $addPrefix = false) {
        $self = new static();
        return ($addPrefix ? self::pref() : '') . $self->table . '.' . $colName;
    }

    /**
     * Возвращает имя поля - ключа таблицы
     * @return string
     * @noinspection PhpUnused
     */
    public static function s_getPrimaryKey() {
        $self = new static();
        return $self->primaryKey;
    }

    /**
     * Возвращает список всех использованных полей
     *
     * @return array
     * @noinspection PhpUnused
     */
    public function getAllFieldsList() {
        $all = [];
        $obj = new static();
        foreach ($obj->fillable as $field) {
            $fieldsParts     = explode('.', $field);
            $fieldName       = array_pop($fieldsParts);
            $all[$fieldName] = $fieldName;
        }
        foreach ($obj->visible as $field) {
            $fieldsParts     = explode('.', $field);
            $fieldName       = array_pop($fieldsParts);
            $all[$fieldName] = $fieldName;
        }
        foreach ($obj->hidden as $field) {
            $fieldsParts     = explode('.', $field);
            $fieldName       = array_pop($fieldsParts);
            $all[$fieldName] = $fieldName;
        }
        if ($obj->primaryKey) {
            $all[$obj->primaryKey] = $obj->primaryKey;
        }
        return $all;
    }


    /**
     * Переписываем метод - возращает типизированное значение поля модели
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key) {

        $val = parent::getAttribute($key);
        if ($val === null) {
            return null;
        }

        // при установке значений предварительно их переработаем
        $type = key_exists($key, static::$fieldTypes) ? static::$fieldTypes[$key] : null;
        $type = (str_starts_with($key, self::FT_BOOL_PREFIX)) ? self::FT_BOOL : $type;
        $type = (str_starts_with($key, self::FT_DATE_PREFIX)) ? self::FT_DATE : $type;
        $type = (str_starts_with($key, self::FT_DATETIME_PREFIX)) ? self::FT_DATETIME : $type;
        $type = (str_starts_with($key, self::FT_UNIXTIME_PREFIX)) ? self::FT_UNIXTIME : $type;

        switch ($type) {
            case self::FT_BOOL:
                $val = HString::inputToBool($val, null);
                break;
            case self::FT_INT:
                $val = intval($val);
                break;
            case self::FT_FLOAT:
                $val = floatval($val);
                break;
            case self::FT_DATE:
            case self::FT_DATETIME:
                $val = HDateTime::strToDate($val, null);
                break;
            case self::FT_UNIXTIME:
                if (is_object($val)) {
                    break;
                }
                $val = intval($val);
                if ($val === 0) {
                    $val = null;
                    break;
                }
                $dateTime = new DateTime();
                $dateTime->setTimestamp($val);
                $val = $dateTime;
                break;
            default:
        }
        return $val;
    }

    /**
     * Set a given attribute on the model.
     * @param string $key
     * @param mixed  $value
     * @return void
     */
    public function setAttribute($key, $value) {

        // null значение или '' преобразуем в null
        if ($value === null || (is_string($value) && trim($value) === '')) {
            parent::setAttribute($key, null);
            return;
        }

        // определяем тип поля, если указано в настроках модели
        switch ($this->getFieldType($key)){
            case self::FT_BOOL:
                $value = HString::inputToBool($value, null);
                if (HDB::isBooleanAsBit()) {
                    $value = $value ? 1 : 0;
                }
                parent::setAttribute($key, $value);
                break;
            case self::FT_DATE:
                $value = HString::inputToDate($value, null);
                parent::setAttribute($key, HDateTime::toSQLDate($value) );
                break;
            case self::FT_DATETIME:
                $value = HString::inputToDate($value, null);
                parent::setAttribute($key, HDateTime::toSQLDateTime($value) );
                break;
            case self::FT_UNIXTIME:
                $value = HString::inputToDate($value, null);
                parent::setAttribute($key, $value->getTimestamp() );
                break;
            case self::FT_INT:
                $value = ( trim($value) !== '') ? intval($value) : null;
                parent::setAttribute($key, $value);
                break;
            case self::FT_FLOAT:
                $value = ( trim($value) !== '') ? floatval($value) : null;
                parent::setAttribute($key, $value);
                break;
            default:
                parent::setAttribute($key, $value);
        }

    }

    /**
     * Возвращает тип поля по названию.
     *
     * @param string $key
     * @return mixed|string|null
     */
    private function getFieldType(string $key){

        // определяем тип поля, если указано в настроках модели
        if ( key_exists($key, static::$fieldTypes) ){
            return static::$fieldTypes[$key];
        }

        // опредляем тип по префиксу
        foreach (self::$fieldTypesPrefix as $prefix=>$type){
            if ( str_starts_with($key, $prefix) ) {
                return $type;
            }
        }

        return null;

    }


    /**
     * Возвращает именованный массив значений полей, которые видимые.
     *
     * @param MBase $obj
     * @return array
     * @noinspection PhpUnused
     */
    public static function toArrayVisible(MBase $obj) {
        $arr = [];
        foreach (static::s_getVisible() as $field) {
            $arr[$field] = $obj->{$field};
        }
        return $arr;
    }


    /**
     * Remove attributes.
     *
     * @param array|string $unsetColumns
     * @noinspection PhpUnused
     */
    public function unsetAttribute(array $unsetColumns) {
        if (is_array($unsetColumns)) {
            foreach ($unsetColumns as $unsetColumn) {
                unset($this->attributes[$unsetColumn]);
            }
        } else {
            unset($this->attributes[$unsetColumns]);
        }
    }


    /*
     * ---------------------------------------------------------------------------------------------
     * Save only fillable attributes
     * ---------------------------------------------------------------------------------------------
     */

    protected bool $saveOnlyFillableAttributes = true;

    private bool $_onlyFillable = false;

    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = []) {
        $this->_onlyFillable = $this->saveOnlyFillableAttributes;
        $result              = parent::save($options);
        $this->_onlyFillable = false;
        return $result;
    }

    /**
     * @param bool $onlyFillable
     * @return array
     */
    public function getAttributes($onlyFillable = false) {
        if ($this->_onlyFillable || $onlyFillable) {
            $attrs  = parent::getAttributes();
            $result = $this->fillableFromArray($attrs);
            if (array_key_exists($this->getKeyName(), $attrs)) {
                $result[$this->getKeyName()] = $attrs[$this->getKeyName()];
            }
            return $result;
        } else {
            return parent::getAttributes();
        }
    }
}

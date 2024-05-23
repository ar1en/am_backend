<?php


namespace App\Utils\Helpers\Reflection;


use App\Utils\Helpers\JsonData\JsonData;
use App\Utils\Helpers\JsonData\JsonModel;
use phpDocumentor\Reflection\Types\Boolean;
use ReflectionException;
use ReflectionType;

/**
 * Class HReflectionType
 *
 *
 *
 * @package App\BPM\Http\Api\Controller
 */
class HReflectionType {

    /** @var array|string[] - built in types. */
    private static array $builtInTypes = [
        "bool"              => "boolean",
        "boolean"           => "boolean",
        "int"               => "number",
        "integer"           => "number",
        "double"            => "number",
        "float"             => "number",
        "string"            => "string",
        "array"             => "mixed",
        "object"            => "object",
        "resource"          => "resource",
        "resource (closed)" => "resource", // as of PHP 7.2.0
        "NULL"              => "null",
        "null"              => "null",
        "mixed"             => "mixed",
        "unknown type"      => "mixed",
    ];


    public HReflectionClass $refClass;

    public string   $name;
    public string   $typeClassName  = '';
    public bool     $isArray        = false;
    public bool     $isNullable     = false;
    public bool     $isClass        = false;
    public bool     $isBuiltIn      = false;
    public string   $arrayKeyType   = '';
    public string   $arrayValueType = '';

    /**
     * HReflectionType constructor.
     * @param string|ReflectionType $typeName
     * @param HReflectionClass      $classContext
     */
    public function __construct($typeName, HReflectionClass $classContext) {

        $this->refClass = $classContext;

        if ($typeName instanceof ReflectionType) {
            $this->isNullable = $typeName->allowsNull();
            $this->isClass = !$typeName->isBuiltin();
        }

        $name = (string)$typeName;

        // check is nullable
        if (str_starts_with($name, '?')) {
            $this->isNullable = true;
            $name = substr($name, 1);
        }

        // check if simple array
        if (str_ends_with($name, '[]')) {
            $this->isArray = true;
            $name = substr($name, 0, strlen($name) - 2);
        }

        // check is Record
        static $rec = "/(\\w+)\\<(\\w+)\\,(\\w+)\\>/";
        if ( preg_match($rec,$name,$recMatch) ) {
            $this->isArray = true;
            $name = $recMatch[1];
            $this->arrayKeyType = $recMatch[2];
            $this->arrayValueType = $recMatch[3];
        }

        $this->name = $name;
        $this->isBuiltIn = array_key_exists($name, self::$builtInTypes);

        if (!$this->isBuiltIn) {
            $this->typeClassName = $this->refClass->getImportedClass($name);
            $this->isClass = class_exists($this->typeClassName);
        }

    }


    /**
     * @return HReflectionClass|null
     * @throws ReflectionException
     */
    public function getClassReflection() {
        if ($this->isClass) {
            return new HReflectionClass($this->typeClassName);
        }
        return null;
    }


    /**
     * Return type name declaration:  ?typename[]
     *
     * @return string
     */
    public function getShortDefinition() {

        $text = '';

        // check nullable
        if ($this->isArray) {
            $text .= '?';
        }

        // check type
        if ($this->isBuiltIn) {
            $text = $this->name;
        } else {
            if ($this->isClass) {
                $text .= $this->typeClassName;
            } else {
                $text .= '(!)' . $this->name;
            }
        }

        // check array
        if ($this->isArray) {
            $text .= '[]';
        }

        return ($this->isNullable ? "?" : "") . $text;
    }


    private static array $_classStack = [];

    /**
     * Get json like description of type.
     *
     * @param string $pref
     * @return false|string
     * @throws ReflectionException
     */
    public function getJsonDefinition($pref = '') {

        $text = '';

        if ($this->isBuiltIn) {
            $text .= $this->name;
        } else {
            if ($this->isClass) {
                $text .= $this->getClassJsonableDefinition($pref . "\t");
            } else {
                $text .= "(!){$this->name}";
            }
        }

        if ($this->isArray) {
            $text = " [ " . $text . " , ... ]";
        }

        if ($this->isNullable) {
            $text = " null | " . $text;
        }

        return $text;
    }

    /**
     * Get json like description of class.
     * @param string $pref
     * @return false|string
     * @throws ReflectionException
     */
    public function getClassJsonableDefinition($pref = '') {

        if (array_key_exists($this->typeClassName, self::$_classStack)) {
            return '*Recursion*(' . $this->typeClassName . ')';
        }

        self::$_classStack[$this->typeClassName] = 1;

        $ref = new HReflectionClass($this->typeClassName);

        // check is ApiDataModel class
        if ($ref->hasParent(JsonModel::class)) {
            $result = $this->getClassJsonableDefinitionApiDataModel($pref);
        } else
            // check ApiData class
            if ($ref->hasParent(JsonData::class)) {
                $result = $this->getClassJsonableDefinitionApiData($pref);
            } else {
                // return any other types
                $result = $this->getClassJsonableDefinitionOther($pref, $ref);
            }

        unset(self::$_classStack[$this->typeClassName]);

        return $result;

    }

    /**
     * @param string           $pref
     * @param HReflectionClass $ref
     * @return string
     * @throws ReflectionException
     */
    public function getClassJsonableDefinitionOther($pref = '', $ref = null) {

        $obj = new $this->typeClassName();

        // encode-decode json
        $jsonEncoded = json_encode($obj, JSON_PRETTY_PRINT);
        $jsonDecoded = json_decode($jsonEncoded);

        // out each json property
        $zap = '';
        $text = "{   \t// " . $this->typeClassName;
        foreach ($jsonDecoded as $key => $item) {
            if ($ref->hasProperty($key)) {
                $prop = $ref->getProperty($key);
                $propType = $prop ? $prop->getType() : null;
                if ($propType) {
                    $text .= $propType->getJsonDefinition();
                } else {
                    $text .= $zap . "\r\n{$pref}\t{$key}:" . json_encode($item);
                }
                $zap = ',';
            }
        }
        $text .= "\r\n{$pref}}";

        return $text;
    }

    /**
     * @param string $pref
     * @return string
     * @throws ReflectionException
     */
    public function getClassJsonableDefinitionApiData($pref = '') {
        $clsName = $this->typeClassName;
        $ref = new HReflectionClass($clsName);
        $props = $ref->getProperties();
        $zap = '';
        $text = $pref . "{ \t// " . $clsName;
        foreach ($props as $prop) {
            $type = $prop->getType();
            $text .= $zap . "\r\n{$pref}\t" . $prop->getName() . ": ";
            $text .= $type->getJsonDefinition($pref . "\t");
            $zap = ',';
        }
        return $text . "\r\n{$pref}}";
    }


    /**
     * Return json representation of class ApiData
     *
     * @param string $pref
     * @return string
     */
    public function getClassJsonableDefinitionApiDataModel($pref = '') {

        $clsName = $this->typeClassName;
        $list = $clsName::$fieldsList;

        $zap = '';
        $text = $pref . "{ \t// " . $clsName;
        foreach ($list as $name => $prop) {
            $text .= $zap
                . "\r\n{$pref}\t{$name} <- " . (is_array($prop) ? $prop[0] : $prop)
                . ": " . (is_array($prop) ? $prop[1] : '');
            $zap = ',';
        }
        return $text . "\r\n{$pref}}";
    }

    /**
     * Find all classes to be exported (ApiData or ApiDataModel).
     * @param array $classes
     * @throws ReflectionException
     */
    public function getClassRelatedList(array &$classes) {

        // add only class types
        if ($this->isClass) {
            // add me to list
            /** @var HReflectionClass $classRef */
            $classRef = $this->getClassReflection();
            // add to related classes
            $clsName = $classRef->getTypeScriptName();
            $classRef->getClassRelatedList($classes);
        }

    }

    /**
     * @param bool $asArray
     * @return false|string
     * @throws ReflectionException
     */
    public function getTypeScriptName(bool $asArray=false) {
        return $this->isClass
            ? $this->getClassReflection()->getTypeScriptName($asArray)
            : $this->getTypeScriptTypeBuiltInName($this->name,$asArray);
    }

    /**
     * Get built in type name
     * @param string $phpTypeName
     * @param bool   $asArray
     * @return mixed|string
     */
    public function getTypeScriptTypeBuiltInName(string $phpTypeName,bool $asArray=false) {

        // is record type
        if ( $this->isArray && $this->arrayKeyType && $this->arrayValueType ){
            $k = self::convertToTypeScriptName($this->arrayKeyType);
            $v = self::convertToTypeScriptName($this->arrayValueType);
            return "Record<{$k},{$v}>";
        }

        return self::convertToTypeScriptName($phpTypeName).($asArray?"[]":"");
    }

    /**
     * @param $phpTypeName
     * @return mixed|string
     */
    private static function convertToTypeScriptName($phpTypeName){
        // check default types
        if (array_key_exists($phpTypeName, self::$builtInTypes)) {
            return self::$builtInTypes[$phpTypeName];
        }
        return $phpTypeName;
    }


}

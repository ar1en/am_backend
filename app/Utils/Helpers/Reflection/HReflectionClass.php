<?php


namespace App\Utils\Helpers\Reflection;

use App\Utils\Helpers\JsonData\JsonData;
use App\Utils\Helpers\JsonData\JsonModel;
use ReflectionClass;
use ReflectionException;

/**
 * Class HReflection
 *
 * Helper for ReflectionClass
 *
 * @package App\BPM\Http\Api\Controller
 */
class HReflectionClass {

    // base reference
    private ReflectionClass $_ref;

    // list of imports strings
    private ?array          $_imports = null;


    /**
     * HReflection constructor.
     * @param string $class
     * @throws ReflectionException
     */
    public function __construct($class) {
        $class      = is_object($class) ? get_class($class) : $class;
        $this->_ref = new ReflectionClass(
            (str_starts_with($class, '\\') ? "" : "\\").$class
        );
    }

    /**
     * Return full class name.
     * @param bool $addRootPrefix
     * @return string
     */
    public function getName(bool $addRootPrefix = false) {
        return ($addRootPrefix ? "\\" : "") . $this->_ref->getName();
    }

    /**
     * Return base name of class.
     * @return string
     */
    public function getShortName() {
        return $this->_ref->getShortName();
    }

    /**
     * Return file name of class.
     * @return false|string
     */
    public function getFileName() {
        return $this->_ref->getFileName();
    }

    /**
     * Return class comments.
     * @return false|string
     */
    public function getDocComment() {
        return $this->_ref->getDocComment();
    }

    /**
     * Check - is class is parent
     * @param $class
     * @return bool
     */
    public function hasParent($class) {
        $class = trim($class, "\\");
        $p     = $this->_ref->getParentClass();
        while ($p) {
            if ($p->getName() === $class) {
                return true;
            }
            $p = $p->getParentClass();
        }
        return false;
    }

    /**
     * Return used full class name by imports alias name.
     * @param $name
     * @return mixed
     */
    public function getImportedClass($name) {

        if (class_exists($name) || str_starts_with($name, '\\')) {
            return $name;
        }

        $imports = $this->getImports();
        if (array_key_exists($name, $imports)) {
            return $imports[$name];
        } else {
            return $this->_ref->getNamespaceName() . '\\' . $name;
        }

    }

    /**
     * Return list of imports.
     * @return array
     */
    public function getImports() {

        if ($this->_imports) {
            return $this->_imports;
        }

        $content = file_get_contents($this->_ref->getFileName());
        $regx    = "/\\s*use\\s([a-zA-Z_\\\\]+)\\s*([a-zA-Z]*);/";
        $match   = [];
        if (!preg_match_all($regx, $content, $match)) {
            return [];
        }
        $result = [];
        foreach ($match[1] as $key => $item) {
            if ($key >= 0) {
                if ($match[2][$key]) {
                    $result[$match[2][$key]] = $item;
                } else {
                    $names               = explode('\\', $item);
                    $result[end($names)] = $item;
                }
            }
        }

        return $this->_imports = $result;
    }

    /**
     * Return methods list.
     * @param string $prefix
     * @return HReflectionMethod[]
     */
    public function getMethods($prefix = '') {

        $methods = $this->_ref->getMethods();
        $methods = $prefix
            ? array_filter($methods, function ($item) use ($prefix) { return str_starts_with($item->getName(), $prefix); })
            : $methods;

        return array_map(function ($i) { return new HReflectionMethod($i, $this); }, $methods);

    }

    /**
     * Return method.
     * @param string $methodName
     * @return HReflectionMethod|null
     * @throws ReflectionException
     */
    public function getMethod(string $methodName) {
        if ($this->_ref->hasMethod($methodName)) {
            $method = $this->_ref->getMethod($methodName);
            return new HReflectionMethod($method, $this);
        }
        return null;
    }

    /**
     * Return all properties
     * @return HReflectionProperty[]
     */
    public function getProperties() {
        $props = $this->_ref->getProperties();
        return array_map(function ($i) {
            return new HReflectionProperty($i, $this);
        }, $props);
    }

    /**
     * @param $key
     * @return null|HReflectionProperty
     * @throws ReflectionException
     */
    public function getProperty($key) {
        $prop = $this->_ref->getProperty($key);
        if ($prop) {
            return new HReflectionProperty($prop, $this);
        }
        return null;
    }


    /**
     * @param $key
     * @return bool
     */
    public function hasProperty($key) {
        return $this->_ref->hasProperty($key);
    }


    /**
     * @param bool|string        $asName
     * @param HReflectionClass[] $imports
     * @return string
     * @throws ReflectionException
     */
    public function getTypeScriptDefinition($asName = false, &$imports = []) {

        $typeName = $this->getTypeScriptName();

        $result = '';
        $result .= "\r\n\r\n";
        $result .= "\r\n/**";
        $result .= "\r\n* API data - based on type:  {$typeName}";
        $result .= "\r\n* Source php class name:  {$this->getName()}";
        $result .= "\r\n*/";
        $result .= "\r\nexport type "
            . ($asName ? $asName : $typeName)
            . " = {";

        if ($this->hasParent(JsonData::class)) {
            $result .= $this->getTypeScriptDefinitionApiData($imports);
        }

        if ($this->hasParent(JsonModel::class)) {
            $result .= $this->getTypeScriptDefinitionApiDataModel($imports);
        }

        $result .= "\r\n}\r\n";
        return $result;
    }

    /**
     * @param HReflectionClass[] $imports
     * @return string
     * @throws ReflectionException
     */
    private function getTypeScriptDefinitionApiData(&$imports = []) {
        $result = '';
        $props  = $this->getProperties();
        foreach ($props as $prop) {

            $propType = $prop->getType();
            $isNull   = $propType->isNullable ? "?" : "";

            $result .= ($result ? "," : "");
            $result .= "\r\n\t" . $prop->getName() . "{$isNull}:";
            $result .= $propType->getTypeScriptName($propType->isArray);

            // add import class
            if ($propType->isClass) {
                $imports[] = $propType->getClassReflection();
            }

        }
        return $result;
    }

    /**
     * @param HReflectionClass[] $imports
     * @return string
     * @throws ReflectionException
     */
    private function getTypeScriptDefinitionApiDataModel(&$imports = []) {
        $result = '';
        /** @var JsonModel $cls */
        $cls = $this->getName(true);
        foreach ($cls::getFields() as $key => $fld) {

            // get field type
            $propType = (is_array($fld) && isset($fld[2]))
                ? $fld[2]
                : 'string';

            // get convert function
            $fun = $fld[1];

            // check is array
            $isArray  = ($fun === JsonModel::JF_JSON_MODEL_COLLECTION || str_ends_with($propType, '[]'));
            $propType = str_replace('[]', '', $propType);

            // change type to Api class as TypeScript
            $cls = class_exists($propType) ? new HReflectionClass($propType) : null;
            if (is_array($fld) && isset($fld[2]) && $cls) {
                $propType = $cls->getTypeScriptName();
            }
            if ($cls) {
                $imports[] = $cls;
            }

            $result .= ($result ? "," : "") . "\r\n\t{$key}:{$propType}" . ($isArray ? "[]" : "");

        }
        return $result;
    }


    /**
     * @param bool $asArray
     * @return string
     */
    public function getTypeScriptName(bool $asArray = false) {
        return "IApi" . $this->getShortName() . ($asArray ? "[]" : "");
    }

    /**
     * @param array $classes
     * @throws ReflectionException
     */
    public function getClassRelatedList(array &$classes) {

        // check is exists
        if (array_key_exists($this->getTypeScriptName(), $classes)) {
            return;
        }

        // add to list
        $classes[$this->getTypeScriptName()] = $this;


        // add properties classes of ApiData
        if ($this->hasParent(JsonData::class)) {
            foreach ($this->getProperties() as $prop) {
                $prop->getType()->getClassRelatedList($classes);
            }
            return;
        }

        // add properties types fo ApiDataModel
        if ($this->hasParent(JsonModel::class)) {
            /** @var JsonModel $cls */
            $cls    = $this->getName(true);
            $fields = $cls::getFields();
            foreach ($fields as $item) {
                if (isset($item[2]) && class_exists($item[2])) {
                    $eClass = new HReflectionClass($item[2]);
                    $eClass->getClassRelatedList($classes);
                }
            }

        }


    }


}

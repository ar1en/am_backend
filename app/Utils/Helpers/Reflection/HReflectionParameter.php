<?php


namespace App\Utils\Helpers\Reflection;

use ReflectionException;
use ReflectionParameter;

/**
 * Class HReflectionParameter
 * @package App\BPM\Http\Api\Controller
 */
class HReflectionParameter {

    private HReflectionClass    $_ref;
    private HReflectionMethod   $_method;
    private ReflectionParameter $_param;

    /**
     * HReflectionParameter constructor.
     * @param ReflectionParameter $param
     * @param HReflectionMethod   $method
     */
    public function __construct(ReflectionParameter $param, HReflectionMethod $method) {
        $this->_ref = $method->getClassReflection();
        $this->_method = $method;
        $this->_param = $param;
    }


    /**
     * @return string
     */
    public function getName() {
        return $this->_param->getName();
    }


    /**
     * @return bool
     */
    public function isDefaultValueAvailable() {
        return $this->_param->isDefaultValueAvailable();
    }

    /**
     * @return mixed
     * @throws ReflectionException
     */
    public function getDefaultValue() {
        return $this->_param->getDefaultValue();
    }

    /**
     * @return HReflectionType
     */
    public function getType() {

        $type = $this->_param->getType();

        $note = $this->_method->getDocComment();

        if ($note) {
            $regx = "/\\s@param\s([a-zA-Z_0-9]+)\\s\\$" . $this->_param->getName() . "/";
            $match = [];
            if (preg_match($regx, $note, $match)) {
                $type = $match[1];
            }
        }

        return new HReflectionType($type, $this->_ref);

    }


}

<?php


namespace App\Utils\Helpers\Reflection;

use ReflectionProperty;

/**
 * Class HReflectionProperty
 * @package App\BPM\Http\Api\Controller
 */
class HReflectionProperty {

    private HReflectionClass         $_ref;
    private ReflectionProperty       $_prop;

    /**
     * HReflectionProperty constructor.
     * @param ReflectionProperty $prop
     * @param HReflectionClass   $ref
     */
    public function __construct(ReflectionProperty $prop, HReflectionClass $ref) {
        $this->_prop = $prop;
        $this->_ref = $ref;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->_prop->getName();
    }

    /**
     * @return ReflectionProperty
     */
    public function getProperty() {
        return $this->_prop;
    }


    /**
     * @return HReflectionType
     */
    public function getType() {

        $type = $this->_prop->getType();

        $note = $this->_prop->getDocComment();
        if ($note) {
            $regx = "/\\s@var\s([a-zA-Z_0-9\\[\\]\\<\\>\\,]+)\\s*/";
            $match = [];
            if (preg_match($regx, $note, $match)) {
                $type = $match[1];
            }
        }

        return new HReflectionType($type, $this->_ref);

    }


}

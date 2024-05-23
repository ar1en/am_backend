<?php


namespace App\Utils\Helpers\Reflection;


use ReflectionMethod;

/**
 * Class HReflectionMethod
 * @package App\BPM\Http\Api\Controller
 */
class HReflectionMethod {

    private HReflectionClass $_ref;

    private ReflectionMethod $_method;

    /**
     * HReflectionMethod constructor.
     * @param ReflectionMethod $method
     * @param HReflectionClass $ref
     */
    public function __construct(ReflectionMethod $method, HReflectionClass $ref) {
        $this->_ref = $ref;
        $this->_method = $method;
    }


    /**
     * @return HReflectionClass
     */
    public function getClassReflection() {
        return $this->_ref;
    }


    /**
     * @return string
     */
    public function getName() {
        return $this->_method->getName();
    }

    /**
     * @return false|string
     */
    public function getDocComment() {
        return $this->_method->getDocComment();
    }

    /**
     * Return documentation as string.
     * @return string
     */
    public function getDocString(){
        $txt = $this->getDocComment();
        $txtLines = explode("\n",$txt);
        $linesTrimmed = array_map(function($l){ return trim($l,"\r\n \t/*");},$txtLines);
        $lines = array_filter($linesTrimmed,function($l){return !str_starts_with($l,'@') && trim($l);});
        return implode(" ",$lines);
    }



    /**
     * @return mixed|null
     */
    public function getReturnType() {

        // check comments type
        $comments = $this->_method->getDocComment();
        $regex = "/\\@return\\s([a-zA-Z\\[\\]]+)\\s/";
        $match = false;
        if ($comments && preg_match($regex, $comments, $match)) {
            return new HReflectionType($match[1], $this->_ref);
        }

        $type = $this->_method->getReturnType();
        return $type?new HReflectionType($type, $this->_ref):null;

    }


    /**
     * @return HReflectionParameter[]
     */
    public function getParameters() {
        $params = $this->_method->getParameters();
        return array_map(function ($i) { return new HReflectionParameter($i, $this); }, $params);
    }


}

<?php


namespace App\Utils\Helpers\Http;


use App\Utils\Helpers\Formats\HString;
use App\Utils\Helpers\UUID\UUID;
use Illuminate\Http\Request;

/**
 * Class HRequestInputs
 *
 * Реализует методы доступа к паратмерам запроса из uri строки (после ?)
 *  val('paramName')
 *  str('paramName')
 *  bool('paramName')
 *  guid('paramName')
 *
 * А также метоы доступа к параметрам url, определяемых в route
 * Если в route = '/app/:fileGuid/load'
 * То $controller->in()->paramGuid('fileGuid') вернет соответствующее значение
 *
 * @package App\Utils\Helpers\Http
 */
class HRequestInputs {

    private Request $_request;
    private array   $params;


    /**
     * HRequestInputs constructor.
     * @param Request $request
     * @param array   $params
     */
    public function __construct(Request $request, array $params) {
        $this->_request = $request;
        $this->params = $params;
    }



    /*
     * -----------------------------------------------------------
     * Query parameters - values of uri parameters
     * -----------------------------------------------------------
     */

    /**
     * Возвращает знаение параметра запроса.
     * @param string $name
     * @param null   $default
     * @return array|string
     */
    public function val($name, $default = null) {
        return $this->_request->input($name, $default);
    }

    /**
     * Возвращает знаение параметра запроса.
     * @param string $name
     * @param null   $default
     * @return array|string
     */
    public function str($name, $default = null) {
        return $this->_request->input($name, $default);
    }

    /**
     * @param string $name
     * @param null   $default
     * @return bool|null
     */
    public function bool($name, $default = null) {
        $val = $this->_request->input($name, $default);
        return HString::inputToBool($val, $default);
    }

    /**
     * @param string $name
     * @param null   $default
     * @return int|null
     */
    public function int($name, $default = null) {
        $val = $this->_request->input($name, $default);
        return ($val === null || $val === '') ? $default : intval($val);
    }

    /**
     * @param string $name
     * @param null   $default
     * @return string|null
     */
    public function guid($name, $default = null) {
        $val = $this->_request->input($name, $default);
        return UUID::isValid($val) ? $val : $default;
    }




    /*
     * -----------------------------------------------------------
     * URL parameters - values of url parameters
     * -----------------------------------------------------------
     */

    /**
     * Return url parameter value.
     * @param string     $name
     * @param mixed|null $def
     * @return mixed|null
     */
    public function param($name, $def = null) {
        if (array_key_exists($name, $this->params)) {
            return $this->params[$name];
        }
        return $def;
    }


    /**
     * @param string      $name
     * @param string|null $def
     * @return string|null
     */
    public function paramStr(string $name, ?string $def=null) {
        if (array_key_exists($name, $this->params)) {
            return (string)$this->params[$name];
        }
        return $def;
    }


    /**
     * @param string      $name
     * @param string|null $def
     * @return string|null
     */
    public function paramGuid(string $name, ?string $def=null) {
        if (array_key_exists($name, $this->params)) {
            $val = (string)$this->params[$name];
            return UUID::isValid($val)?$val:$def;
        }
        return $def;
    }

    /**
     * @param string      $name
     * @param int|null $def
     * @return int|null
     */
    public function paramInt(string $name, ?int $def=null) {
        if (array_key_exists($name, $this->params)) {
            return (int)$this->params[$name];
        }
        return $def;
    }



}

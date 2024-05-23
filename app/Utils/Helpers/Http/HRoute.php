<?php


namespace App\Utils\Helpers\Http;


use App\Utils\Helpers\Exception\AppException;
use App\PVU\Http\Controllers\ControllerWebPageAuthorized;
use App\Utils\Helpers\Reflection\HClass;
use Illuminate\Support\Facades\Route;
use ReflectionException;

/**
 * Class HRoute
 *
 * Класс содержит вспомогательные методы для работы с маршрутами.
 *
 *
 * @package App\About\Utils\Helpers
 */
class HRoute {


    /*
     * Наименование методов в контроллерах,
     * которые будут обрабатываться запросы
     */
    public const C_METHOD_POST = 'httpPost';
    public const C_METHOD_GET = 'httpGet';
    public const C_METHOD_ANY = 'httpAny';
    public const C_METHOD_API = 'httpApi';

    /*
     * В каждом контроллере, если есть методы,
     * которые обрабатываю api запросы,
     * то будет также регистриоваться путь для api
     */
    public const C_API_URL_SUFFIX = '/__api__';


    /**
     * Перечень method для обработки httpAny()
     * @var array|string[]
     */
    private static array $httpAnyMethods = ['GET', 'POST', 'OPTIONS', 'HEAD', 'INPUT', 'DELETE', 'INSERT'];

    /**
     * @var array - for checking duplicates routes
     */
    private static array $_routesNames = [];


    /**
     * Register GET route
     *
     * @param $url
     * @param $options
     * @return \Illuminate\Routing\Route
     */
    public static function get($url, $options) {
        return Route::get($url, $options);
    }

    /**
     * Register POST route
     * @param $url
     * @param $options
     * @return \Illuminate\Routing\Route
     */
    public static function post($url, $options) {
        return Route::post($url, $options);
    }

    /**
     * Register several methods route
     *
     * @param $methods
     * @param $url
     * @param $options
     * @return \Illuminate\Routing\Route
     */
    public static function match($methods, $url, $options) {
        return Route::match($methods, $url, $options);
    }


    /**
     * Register routes for all child classes with $route property.
     * @param string $baseControllerClass
     * @throws ReflectionException
     */
    public static function registerAllRoutes(string $baseControllerClass) {

        self::$_routesNames = [];
        $children = HClass::getChildrenClasses($baseControllerClass);
        foreach ($children as $cls) {
            // skip abstract classes and base class
            try {
                $isAbstract = HClass::isAbstract($cls);
            } catch (ReflectionException $e) {
                die($e->getMessage());
            }
            if ($cls === $baseControllerClass || $isAbstract) {
                continue;
            }
            // register base route actions
            try {
                self::registerRoute($cls);
            } catch (AppException $e) {
                die($e->getMessage());
            }
        }

    }


    /**
     * Register controller with route.
     *
     * @param string $controllerRoutedClassName
     * @return bool
     * @throws AppException
     */
    public static function registerRoute(string $controllerRoutedClassName) {

        // make full class name
        $controllerClassName = "\\" . trim($controllerRoutedClassName, "\\");

        // check class
        if (!class_exists($controllerRoutedClassName)) {
            throw new AppException('Controller class not exists: [' . $controllerRoutedClassName . ']');
        }

        // check method exists
        if (!method_exists($controllerRoutedClassName, 'getRoute')) {
            throw new AppException('Controller class has not getRoute() method: [' . $controllerRoutedClassName . ']');
        }

        /** @var ControllerWebPageAuthorized $controllerRoutedClassName */
        $urlName = $controllerRoutedClassName::getRoute();
        if (!$urlName) {
            return false;
        }

        // check ulr duplicate
        $result = false;
        $theUrl = HRoute::url($urlName);  // make full url
        if ($dupClass = self::isDuplicate($theUrl, $controllerRoutedClassName)) {
            throw new AppException("\r\n!!! Controller class has duplicate url [{$theUrl}] at [{$dupClass}] & [{$controllerRoutedClassName}]");
        }

        // register GET method
        if (method_exists($controllerRoutedClassName, self::C_METHOD_GET)) {
            $options = ['as' => $urlName, 'uses' => $controllerClassName . '@' . self::C_METHOD_GET];
            $result  = self::get($theUrl, $options);
        }

        // register POST method
        if (method_exists($controllerRoutedClassName, self::C_METHOD_POST)) {
            $options = [
                'as'   => $result ? $urlName . '___post' : $urlName,
                'uses' => $controllerClassName . '@' . self::C_METHOD_POST
            ];
            $result  = self::post(HRoute::url($urlName), $options);
            // alternative code is... HRoute::post(HRoute::url($uri), $cls . self::C_METHOD_POST);
        }

        // register API query url
        if (method_exists($controllerRoutedClassName, self::C_METHOD_API)) {
            $result = HRoute::get(
                HRoute::url($urlName) . self::C_API_URL_SUFFIX,
                $controllerClassName . '@' . self::C_METHOD_API);
        }

        // register ANY method
        if (method_exists($controllerRoutedClassName, self::C_METHOD_ANY)) {
            $options = [
                'as'   => $result ? $urlName . '___any' : $urlName,
                'uses' => $controllerClassName . '@' . self::C_METHOD_ANY
            ];
            $result  = HRoute::match(self::$httpAnyMethods, HRoute::url($urlName), $options);
        }

        return $result;

    }







    /**
     * Return url with site prefix.
     *
     * Для всех регистрируемых марщрутов можно централизовано добавить
     * префикс через настройки в env файле.
     *
     * @param string $url
     * @return string
     */
    public static function url($url = '') {
        return config('app.routes_url_prefix') . $url;
    }


    /**
     * Check if current route is parent for given route name with parameters.
     * @param       $routeName
     * @param null  $params
     * @param mixed $true
     * @param mixed $false
     * @return mixed
     */
    public static function isParentRoute($routeName, $params = null, $true = true, $false = false) {
        if ($params === null) {
            $params = [];
        }
        $uri      = route($routeName, $params, false);
        $uriLines = explode('?', $uri);
        $uriMask  = urldecode(substr($uriLines[0] . '*', 1));
        return request()->is($uriMask) ? $true : $false;
    }





    /**
     * @param $url
     * @param $class
     * @return bool
     */
    private static function isDuplicate($url, $class) {
        return (isset(self::$_routesNames[$url]))
            ? self::$_routesNames[$url]
            : !(self::$_routesNames[$url] = $class);
    }


}

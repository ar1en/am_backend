<?php


namespace App\Utils\Helpers\Http;


use Illuminate\Routing\Controller;

/**
 * Trait HControllerRoutedTrait
 *
 * Класс автоматически регистрируемого контроллера.
 *
 * @package App\Utils\Helpers\Http
 */
abstract class HControllerRouted extends Controller {


    // route - url path, when not empty will be registered as route
    protected static string $route = '';

    /**
     * @return string
     */
    public static function getRoute() {

        return static::$route;
    }


}

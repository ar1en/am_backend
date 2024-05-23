<?php

/* ------------------------------------------------------------------------------------
 * Дополнительные хелперы для работы приложения
 * Его надо подключить в файле app.php в начале скрипта
 * include_once __DIR__."/../app/Utils/Helpers/Helpers.php";
 * ------------------------------------------------------------------------------------
 */


if (!function_exists('env_require')) {
    /**
     * Для извлечения параметров env файлоа c проверкой наличия параметра в env файле.
     * В случае отсутствия параметра прекращает работу и выводит ошибку в кпоток.
     *
     * @param string $key
     * @return mixed
     */
    function env_require($key) {
        $value = env($key, null);
        if ($value === null) {
            // ошибка критическая, поэтому просто прекращаем выполнение скрипта
            die("Config ENV value required [$key]!");
            // убираем пока - throw new HelpersException("Config ENV value required [$key]!");
        }
        return $value;
    }
}

/**
 * Возвращает ссылка на asset сайта с добавление в конце
 * пути параметр с номером версии,
 * прописанной в env файле
 *      asset_version=...
 */
if (!function_exists('asset_ver')) {

    /**
     * @param string $path
     * @param null   $secure
     * @return string
     */
    function asset_ver($path, $secure = null) {
        $ver = config('app.asset_version');
        return app('url')->asset($path, $secure) . "?v=" . $ver;
    }

}


/**
 * Подменяет ссылку в соответствии с конфигурацией в файле /app/mix-manifest.php
 * Добавляет при необходимости префик виртуального хоста
 */
if (!function_exists('mix_fix')) {

    /**
     * @param string $path
     * @return string
     * @throws Exception
     */
    function mix_fix($path) {
        /** @noinspection PhpIncludeInspection */
        $list = include(app_path('mix-manifest.php'));
        if (!is_array($list)) {
            die('Error: mix-manifest.php is incorrect!');
        }
        $path = array_key_exists($path, $list) ? $list[$path] : $path;
        return request()->getBaseUrl() . $path;

    }

}


<?php


namespace App\Utils\Helpers\DB\Model;


use App\Utils\Helpers\IO\HFiles;

/**
 * Использовалось ранее для загрузки всех классов типа Model
 *
 * @package App\Utils\Helpers\DB\Model
 */
class MBaseDeprecated {

    /**
     * Register all Model classes.
     * @param null $path
     */
    public static function __includeAllModelClasses($path = null) {

        if (!$path) {
            $path = __DIR__ . "/../";
        }

        $files = HFiles::getDirList($path, null, null);

        foreach ($files as $file) {

            if (is_dir($path . $file)) {
                self::__includeAllModelClasses($path . $file . '/');
                continue;
            }

            if (str_ends_with($file, '.php')) {
                /** @noinspection PhpIncludeInspection */
                require_once $path . $file;
            }

        }

    }

}

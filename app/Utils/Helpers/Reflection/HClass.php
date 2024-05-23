<?php


namespace App\Utils\Helpers\Reflection;


use App\Utils\Helpers\IO\HFiles;
use ReflectionClass;
use ReflectionException;

/**
 * Class HClass
 *
 * Class helper.
 *
 * @package App\BPM\Utils\Helpers
 */
class HClass {

    /**
     * Find all classes - children of $ofClass
     * @param string $ofClass
     * @return array
     * @throws ReflectionException
     */
    public static function getChildrenClasses(string $ofClass) {

        // include all classes files
        $appPath = app_path('/');
        $files   = HFiles::list($appPath, ['php'], false, true);
        foreach ($files as $file) {
            if (!str_starts_with(basename($file['name']), "_")) {
                /** @noinspection PhpIncludeInspection */
                include_once $file['path'];
            }
        }

        // find all classes of self and register
        $result  = [];
        $classes = get_declared_classes();
        foreach ($classes as $cls) {
            if (!is_subclass_of($cls, $ofClass) || self::isAbstract($cls)) {
                continue;
            }
            $result[] = $cls;
        }

        return $result;

    }


    /**
     * Check is class is abstract.
     * @param string $className
     * @return bool
     * @throws ReflectionException
     */
    public static function isAbstract($className) {

        $class = new ReflectionClass($className);
        return $class->isAbstract();

    }


}

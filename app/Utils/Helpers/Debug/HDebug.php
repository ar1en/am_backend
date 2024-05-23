<?php


namespace App\Utils\Helpers\Debug;

/**
 * Class HDebug
 *
 * Вспомогательный класс для отладки.
 * В отладку можно добавлять точки с данными HDebug::add('Marker name',$data)
 * Потом можно посмотреть записи в HDebug::info() - массив с отметками времени
 *
 * @package App\Utils\Helpers\Debug
 */
class HDebug {

    /** @var string config DEBUG param name */
    public const APP_DEBUG = 'app.debug';

    /** @var string - config var name with admin email */
    public const APP_SUPPORT_EMAIL = 'app.support_email';

    /** @var string - output date-time format */
    public const DATE_TIME_FORMAT_FULL = 'Y-m-d H:i:s.v';

    // накапливаемая информация об отладке
    private static array $info = [];

    // текущий статус флага отладки
    private static ?bool $_isDebug = null;

    /**
     * Check debug state.
     * @return bool
     */
    public static function isDebug() {
        if ( self::$_isDebug===null ) {
            self::$_isDebug=config(self::APP_DEBUG) === true;
        }
        return self::$_isDebug;
    }

    /**
     * Добавялет в стэк сообщения для отладки.
     * @param string $message
     * @param null   $data
     */
    public static function add(string $message, $data = null) {
        self::$info[] = [
            'micro time' => microtime(true),
            'time'       => date(self::DATE_TIME_FORMAT_FULL),
            'message'    => $message,
            'data'       => $data,
        ];
    }

    /**
     * Возвращает массив с накопленными данными по отладке.
     * @return array
     */
    public static function info() {
        return self::$info;
    }

    /**
     * Возвращает почтовый адрес поддержки
     * @return string
     */
    public static function supportEmail() {
        return (string)config(self::APP_SUPPORT_EMAIL);
    }

}

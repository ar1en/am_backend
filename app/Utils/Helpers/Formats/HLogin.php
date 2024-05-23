<?php


namespace App\Utils\Helpers\Formats;

/**
 * Class HLogin
 *
 * Для реализации функций по работе с логинами
 *
 * @package App\Utils\Helpers\Formats
 */
class HLogin {

    /**
     * Check login name rules.
     *
     * @param string $login
     * @return false
     */
    public static function isGood(string $login){
        return preg_match("/^[a-z0-9_-]{2,20}$/i", $login)>0;
    }

}

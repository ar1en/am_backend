<?php


namespace App\Utils\Helpers\Formats;


use App\Utils\Helpers\Exception\AppExceptionViewable;

/**
 * Class HPassword
 * @package App\BPM\Utils\Helpers
 */
class HPassword {


    /**
     * Verify passwords rules.
     * @param $password
     * @return false
     */
    public static function isGood($password){
        return preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/', $password)>0;
    }

    /**
     * Check password pair by rules
     * @param $password
     * @param $confirm
     * @throws AppExceptionViewable
     * @noinspection PhpUnused
     */
    public static function checkRules($password,$confirm){
        if ( $password !== $confirm ) {
            throw new AppExceptionViewable('Пароли не совпадают!');
        }
        if(!HPassword::isGood($password)) {
            throw new AppExceptionViewable('Пароль не соответствует требованиям!');
        }
    }


}

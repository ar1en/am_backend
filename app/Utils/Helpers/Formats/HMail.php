<?php


namespace App\Utils\Helpers\Formats;

/**
 * Class HMail
 * @package App\Utils\Helpers\Formats
 */
class HMail {


    /**
     * @param $email
     * @return bool
     */
    public static function isEmail($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }



}

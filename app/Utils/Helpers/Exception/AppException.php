<?php


namespace App\Utils\Helpers\Exception;


use Exception;

/**
 * Базовый класс для всех ошибок приложения.
 * Добавляет статус http для возврата клиенту.
 *
 * @package App\Utils\Helpers\Exception
 */
class AppException extends Exception {

    /**
     * Типовой статус для ошибок приложения 521 - системная ошибка, но не понятно какая.
     * @var int
     */
    protected int $httpStatus = 521;

    /**
     * Return http status of error.
     * @return int
     */
    public function getHTTPStatus(){
        return $this->httpStatus;
    }


    /** @var string Текст ошибки на все случаи жизни */
    public const MSG_ERROR_DESCRIPTION_COMMON =
        "Произошла системная ошибка на сервере. Если ошибка повторяется - сообщите пожалуйста администратору системы.";

    /** @var string Текст приставка для почты поддержки */
    public const MSG_ERROR_SUPPORT_TEXT = "Служба подержки ";

    /**
     * Возвращает html сообщение об ошибке.
     * @return string
     */
    public function getHTTPDescription(){
        $email = config('app.support_email');
        return "<p>".self::MSG_ERROR_SUPPORT_TEXT."<a href='{$email}'>{$email}</a></p>";
    }


    /**
     * Возвращает текст ошибки с указанием адреса администртаора
     * @return string
     */
    public function getTextDescription(){
        $email = config('app.support_email');
        return self::MSG_ERROR_SUPPORT_TEXT." [{$email}].";
    }

}

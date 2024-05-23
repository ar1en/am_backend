<?php


namespace App\Utils\Helpers\Exception;



/**
 * Базовый класс для всех ошибок приложения,
 * Текст ошибки может быть доступен рядовому пользователю
 *
 * @package App\BPM\Exceptions
 */
class AppExceptionViewable extends AppException {

    /**
     * Типовой статус ошибки 522
     * @var int
     */
    protected int $httpStatus = 522;

}

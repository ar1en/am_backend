<?php


namespace App\Utils\Helpers\UUID;


use Exception;
use Throwable;

/**
 * Class HUUIDException
 * @package App\BPM\Utils\Helpers\HUUID
 */
class HUUIDException extends Exception {

    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}

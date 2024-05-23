<?php


namespace App\Utils\Helpers\ADHelper;


use Exception;
use Throwable;

/**
 * Class ADHelperException
 * @package App\BPM\Utils\Helpers\ADHelper
 */
class ADHelperException extends Exception {

    /**
     * ADHelperException constructor.
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}

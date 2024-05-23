<?php


namespace App\Utils\Helpers\Exception;

use Illuminate\Http\Request;
use PDOException;
use Throwable;

/**
 * Генерирует подробное описание ошибки лдя записи в файл лога
 *
 * @package App\Exceptions
 */
class ExceptionRender {

    /**
     *
     * @param Throwable $exception
     * @return string
     */
    public static function render($exception) {

        // get trace stack
        $exceptionsList = [];
        $prev           = $exception;
        while ($prev) {
            $exceptionsList[] = $prev;
            $prev             = $prev->getPrevious();
        }

        // out all
        $txt   = '';
        $count = count($exceptionsList);
        /** @var Throwable $nextException */
        foreach ($exceptionsList as $exceptionNumber => $nextException) {
            $txt .= self::renderExceptionObject($nextException, $exceptionNumber + 1, $count);
        }

        return $txt;
    }

    /**
     * @param Throwable $exception
     * @param int       $exceptionNumber
     * @param int       $countAll
     * @return string
     */
    private static function renderExceptionObject($exception, $exceptionNumber, $countAll) {

        $txt = '';

        $trace = $exception->getTrace();

        $txt .= "\r\nERROR {$exceptionNumber}/{$countAll}\t" . $exception->getMessage();
        $txt .= "\r\n\t" . get_class($exception) . " in " . $exception->getFile() . ":" . $exception->getLine();

        foreach ($trace as $num => $row) {
            $txt .= "\r\n\t#" . $num . "\t";
            if (isset($row['file'])) {
                $txt .= $row['file'] . ":" . (isset($row['line']) ? $row['line'] : "0");
            }
            if (isset($row['class'])) {
                $txt .= " " . $row['class'] . $row["type"] . $row['function'];
            }
        }

        // если ошибка касается БД - вывести информацию из лога БД - последний запрос к БД
        if ($exception instanceof PDOException) {
            /** @var PDOException $pdo */
            // $txt .= "\r\nPDO Error info: " . SQLLogger::getLastLogContent();
        }

        return $txt;
    }


    /**
     * Make Request text representation
     * @param Request $request
     * @return string
     */
    public static function renderRequest(Request $request) {
        $txt = '';

        $values = [
            // "Url"         => $request->url(),
            "Link   "   => "<a target='_blank' href='" . $request->url() . "'>Link</a>",
            "Referer"   => filter_input(INPUT_SERVER, 'HTTP_REFERER'),
            "Method "   => $request->method(),
            "Host   "   => $request->host(),
            "IP     "   => $request->ip(),
            "Agent  "   => $request->userAgent(),
            "Client IP" => $request->getClientIp(),
            "Rem.user"  => filter_input(INPUT_SERVER, 'REMOTE_USER'),
            "Session "  => $request->session()->getId(),
        ];

        foreach ($values as $key => $val) {
            $txt .= "\r\n\t{$key} \t: " . $val;
        }

        $txt  .= "\r\n";
        $vars = $request->query->all();
        return "\r\nRequest: " . $request->url() . $txt . self::renderVars($vars, 'Request values');
    }

    /**
     * Готовим текст - значения переменных
     * @param        $vars
     * @param string $title
     * @return string|true
     */
    public static function renderVars($vars, $title = '') {
        return ($title
                ? $title . str_repeat('-', 25) . "\r\n"
                : '')
            . str_replace("\n", "\n\t", print_r($vars, true));
    }


    /**
     * Готовим детальнуую информацию об ошибке для бедуг режима.
     *
     * @param Throwable $exception
     * @return array
     */
    public static function renderJson(Throwable $exception) {
        $prev = $exception->getPrevious();
        return [
            'Code'      => $exception->getCode(),
            'Message'   => $exception->getMessage(),
            'File'      => $exception->getFile(),
            'Line'      => $exception->getLine(),
            'Trace'     => self::renderJsonTrace($exception),
            'Prev'      => $prev ? self::renderJson($prev) : null,
        ];
    }

    /**
     * Make trace for json
     * @param Throwable $exception
     * @return array
     */
    private static function renderJsonTrace(Throwable $exception) {
        $traced = [];
        foreach ($exception->getTrace() as $row) {
            $traced[] = $row;
        }
        return $traced;
    }


}

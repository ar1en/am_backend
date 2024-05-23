<?php


namespace App\Utils\Helpers\Exception;

use App\Models\User;
use Exception;
use Throwable;


/**
 * Управления файлами логов ошибок приложения.
 * @package App\Exceptions
 */
class ExceptionFileLogger {

    /**
     * Место хранения фалов логов ошибок
     */
    public const LOG_STORAGE_PATH = '/logs/';


    /**
     * Создает файл с описанием ошибки и возвращает код ошибки.
     * @param Throwable $exception
     * @param array     $vars
     * @return string
     */
    public static function logException($exception, $vars = []) {
        try {

            $errorLogId       = self::getLogFileName($exception);
            $errorLogFileName = $errorLogId[1];  // get file name
            $errorId          = $errorLogId[0];           // get error guid

            $request = request();
            $content = ($request ? ExceptionRender::renderRequest($request) : '')
                . ExceptionRender::render($exception)
                . ($vars ? ExceptionRender::renderVars($vars) : '');

            self::writeLogFile($errorLogFileName, $content);
            return $errorId;

        } catch (Exception $exception2) {
            return 'Error log writing!';
        }
    }


    /**
     * Возвращает имя текущего пользвоателя.
     */
    public static function getUserName() {
        /** @var User $user */
        $user       = auth()->user();
        $remoteUser = isset($_SERVER['REMOTE_USER'])
            ? $_SERVER['REMOTE_USER']
            : "NotAuthorized";
        return $user
            ? $user->name ?? '-'
            : $remoteUser;
    }

    /**
     * Заменяет в строка все символы "не буквы/фицры" указанным символом (поумолчанию _)
     * @param string $str
     * @param string $replace_with_char
     * @return string|string[]|null
     */
    public static function clearNonAlphaNumeric($str, $replace_with_char = '_') {
        return preg_replace('/[^a-zA-Z0-9_.-]/', $replace_with_char, $str);
    }

    /**
     * Возвращает новый уникальный код ошибки и имя файла.
     * Имя файла в формате "Y_m_d__H_i_s_{GUID}_userName~<Error class>"
     * @param null $exception
     * @return array - [guid,fileName]
     */
    public static function getLogFileName($exception = null) {

        $userName         = self::clearNonAlphaNumeric(self::getUserName());
        $errorTime        = date("Y_m_d__H_i_s_u_");
        $errorId          = microtime();
        $exceptionName    = ($exception instanceof Exception)
            ? basename(get_class($exception))
            : "UnknownException";
        $errorFileLogName = $errorTime . $errorId . "_" . $userName . '~' . $exceptionName;
        return [$errorId, $errorFileLogName];
    }

    /**
     * Возвращает системный путь к файлу лога по его имени.
     *
     * @param string $errorFileLogName
     * @return string
     */
    public static function getLogFilePath(string $errorFileLogName) {
        return storage_path(self::LOG_STORAGE_PATH . $errorFileLogName . '.log');
    }

    /**
     * Записывает содержимое в файл лога.
     * @param $errorLogFileName
     * @param $content
     * @return bool|false|int
     */
    public static function writeLogFile($errorLogFileName, $content) {
        try {
            $file = ExceptionFileLogger::getLogFilePath($errorLogFileName);
            return file_put_contents($file, $content, FILE_APPEND);
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * Извлекаем из именифайла лога дату и время, id и имя ошибки.
     * @param string $fileName
     * @return array - [dt,guid,user,name]
     */
    public static function getLogFileNameAttributes(string $fileName) {

        $reg = "/(\\d*)_(\\d*)_(\\d*)__(\\d*)_(\\d*)_(\\d*)_([a-zA-Z0-9\\-]*)_([a-zA-Z\\d\\-]*)(?:~(.*))?/";
        if (preg_match($reg, $fileName, $res) > 0) {
            return [
                'dt'   => "{$res[3]}.{$res[2]}.{$res[1]} {$res[4]}:{$res[5]}:{$res[6]}",
                'guid' => $res[7],
                'user' => $res[8],
                'name' => isset($res[9]) ? $res[9] : '',
            ];
        }

        return [
            'dt'   => date('d.m.Y H:i:s'),
            'guid' => $fileName,
            'user' => '-',
            'name' => '-',
        ];

    }



}

<?php


namespace App\Utils\Helpers\DB\QueryLogger;


use App\Utils\Helpers\DB\HDB;
use App\Utils\Helpers\UUID\UUID;
use Illuminate\Support\Facades\Auth;

/**
 * Подсистема логирования SQL  запросов.
 *
 * @package App\Utils\Helpers\DB\QueryLogger
 */
class HDBLogger {

    /** @var string - имя файля для хранения настроек логирования */
    public const FILE_SQL_LOGGER_CFG = '_sql_logger.cfg';

    /** @var string - имя файла для хранения данных логов */
    public const FILE_SQL_LOGGER_LOG = '_sql_logger.log';

    /** @var string определяет часть имени пользователя, для которого логируем запросы */
    private static string     $filter = '';

    /** @var float|int - время начала логирования */
    private static float      $start  = 0;

    /** @var float|int - время завершения логирования */
    private static float      $finish = 0;

    /**
     * Возвращает имя файла, в котором хранится настройка логировнаия - имя пользователя
     *
     * @return string
     */
    public static function getConfigFileName() {
        return storage_path(self::FILE_SQL_LOGGER_CFG);
    }

    /**
     * Возвращает имя файла, в который пишем лог
     *
     * @return string
     */
    public static function getLogFileName() {
        return storage_path(self::FILE_SQL_LOGGER_LOG);
    }

    /**
     * Возвращает значения фильтра из файла конфигурации
     *
     * @return false|string
     */
    public static function getFilter() {
        $fn = self::getConfigFileName();
        if (file_exists($fn)) {
            return file_get_contents($fn);
        }
        return false;
    }

    /**
     * Устанавливает значение фильтра для логировнаия запросов или отключает логирование
     *
     * @param string|null $value
     */
    public static function setFilter(?string $value = null) {

        $fn = self::getConfigFileName();

        // устанавливаем значение
        if (trim($value)) {
            file_put_contents($fn, trim($value));
            return;
        }

        // отключаем логирование
        if (file_exists($fn)) {
            unlink($fn);
        }

    }

    /**
     * Очищает файл лога
     */
    public static function clearLogs() {
        $fn = self::getLogFileName();
        if (file_exists($fn)) {
            unlink($fn);
        }
    }

    /**
     * Запускаем логирование запросов к БД
     */
    public static function start() {

        // читаем из файла конфигурации значени фильтра
        self::$filter = self::getFilter();
        if (!self::$filter) {
            // если фильтр логирования отключен
            return;
        }

        // проверяем время жизни настроек логирования - не более 1 часа
        $ft = filectime(self::getConfigFileName());
        if (time() - $ft > 60 * 60) {
            self::setFilter();
            return;
        }

        // start logging
        /** @noinspection PhpUndefinedMethodInspection */
        HDB::getDefaultConnection()->enableQueryLog();

        // fix start time
        self::$start = microtime(true);

        // hook exit event
        // регистрируем функцию завершения скрипта,
        // при выходе собранный лог запишем в файл
        register_shutdown_function(function () {
            self::finish();
        });

    }

    /**
     * Finish logger
     */
    public static function finish() {

        // check user login exists
        $u = Auth::user();
        $login = $u ? $u['login'] : null;
        if (!$login && self::$filter !== '%') {
            return;
        }

        // write log only for user by filter
        if (false === strpos($login, self::$filter) && self::$filter !== '%') {
            return;
        }

        // fix end time
        self::$finish = microtime(true);

        // get query list
        /** @noinspection PhpUndefinedMethodInspection */
        $list = HDB::getDefaultConnection()->getQueryLog();


        $obj = new JSQLLogSession();
        $obj->id = UUID::newV4();
        $obj->login = $login ?? '-';
        $obj->uri = request()->getUri();
        $obj->method = request()->method();
        $obj->action = request()->input('action') . "@action" . request()->input('method');
        $obj->date = date('Y-m-d H:m:i');
        $obj->queryTime = round((self::$finish - self::$start) * 1000, 0);
        $obj->sqlTime = 0.0;
        foreach ($list as $item) {
            $obj->sqlTime += $item['time'];
            $query = new JSQLLogQuery();
            $query->time = $item['time'];
            $query->sql = $item['query'];
            $query->bindings = print_r($item['bindings'], true);
            $obj->queries[] = $query;
        }

        try {
            self::addContent(json_encode($obj) . "\r\n,");
        } catch (\Throwable $ex) {
            $obj = null;
        }


    }


    public static function finish2() {


        // check user login exists
        $u = Auth::user();
        $login = $u ? $u['login'] : null;
        if (!$login && self::$filter !== '%') {
            return;
        }

        // write log only for user by filter
        if (false === strpos($login, self::$filter) && self::$filter !== '%') {
            return;
        }

        // fix result
        self::$finish = microtime(true);
        $dt = date('Y-m-d H:m:i');
        $sec = round(self::$finish - self::$start, 3);
        $uri = request()->getUri();
        $id = 'id_' . str_replace('.', '_', self::$start);

        $list = HDB::getDefaultConnection()->getQueryLog();
        $timeSQL = 0.0;
        $log = '';
        foreach ($list as $i => $item) {
            $t = $item['time'];
            $q = $item['query'];
            $b = $item['bindings'];
            $timeSQL += $t;
            $idb = $id . '_' . $i;
            $num = $i + 1;
            $log .= "<p onclick='$(\"#{$idb}\").toggle();'>{$num}) <b>[{$t}ms]</b> SQL: <span class='text-secondary'>{$q}</span></p>";
            $log .= "<div id='{$idb}' style='display: none'><pre><code>" . print_r($b, true) . "</code></pre></div>";
        }

        $timeSQL = round($timeSQL);

        $txt = "<p onclick='$(\"#{$id}\").toggle();' style='cursor:pointer;' class='alert alert-info'>";
        $txt .= "<b>{$dt}</b> ({$timeSQL}ms/{$sec} sec) [{$login}]: <b>{$uri}</b>";
        $txt .= "</p>";
        $txt .= "<div id='{$id}' class='p-1' style='display: none'>";
        $txt .= $log;
        $txt .= "</div>";

        self::addContent($txt);

    }

    /**
     * Write log content
     * @param $txt
     */
    public static function addContent($txt) {
        $fn = self::getLogFileName();
        file_put_contents($fn, $txt, FILE_APPEND);
    }

    /**
     * @return false|string
     */
    public static function getLogContent() {
        $fn = self::getLogFileName();
        return file_exists($fn) ? file_get_contents($fn) : null;
    }


}

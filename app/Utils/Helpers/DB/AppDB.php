<?php


namespace App\Utils\Helpers\DB;


use Exception;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Объект подключения к БД
 *
 * Делаем централизованный объект с подключением к БД для всего приложения.
 * Исползовать подключение Laravel по-умолчанию из конфига - не хорошо, не точно.
 *  *
 * @package App\BPM\Model
 */
abstract class AppDB {


    /**
     * Само подключение.
     *
     * @var ConnectionInterface | null
     */
    private static ?ConnectionInterface $_connection = null;


    /**
     * Возвращает имя подключения.
     * Когда делаем объект поключения к БД для приложения,
     * в нем явно указываем имя подключения, которое берется из конфига.
     *
     * @return string
     */
    abstract public function getConnectionName();

    /**
     * Возвращает объект подклчючения к БД
     *
     * @return ConnectionInterface
     */
    public function connection() {
        if (self::$_connection === null) {
            /** @var ConnectionInterface _connection */
            self::$_connection = DB::connection($this->getConnectionName());
        }
        return self::$_connection;
    }


    /**
     * Выполняет прямой запрос к БД
     *
     * @param       $sql
     * @param array $bindings
     * @param bool  $useReadPdo
     * @return array
     */
    public function select($sql, $bindings = [], $useReadPdo = true) {
        return $this->connection()->select($sql, $bindings, $useReadPdo);
    }


    /**
     * Обертка для транзакции
     *
     * @param callable $callback
     * @throws Throwable
     */
    public function transaction(callable $callback) {
        $conn = $this->connection();
        $conn->beginTransaction();
        try {
            /*
             * Add simple operation,
             * else error "PDOException", code: 0, message: "There is no active transaction"
             * Это како-то костыль, возможно для новых версий PGSQL такое не потребуется.
             */
            $conn->statement("select 1");
            $callback();
            $conn->commit();
        } catch (Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }


}

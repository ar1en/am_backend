<?php


namespace App\Utils\Helpers\DB\Model;


use App\Utils\Helpers\DB\AppDB;
use App\Utils\Helpers\DB\HDB;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;

/**
 * Добавляет в MBase класс методы работы с БД и запросами
 *
 * @package App\Utils\Helpers\DB\Model
 */
trait MBaseDBHelpers {


    /**
     * Создает построитель запросов на базе модели
     *
     * @param AppDB $db
     * @return Builder
     */
    public static function q(AppDB $db) {
        $obj = new static();
        $obj->setConnection($db->getConnectionName());
        return $obj->newQuery();
    }


    /**
     * Определяет тип БД
     */
    public function isPgSql() {
        /** @var Connection $conn */
        $connName = $this->getConnectionName() ;
        $connectionsList = config('db.database.connections');
        if ( isset($connectionsList[$connName]['driver'])  ) {
            return isset($connectionsList[$connName]['driver']) === 'pgsql';
        }
        return false;
    }

    /**
     * Переопределяем формат даты, которая нужна для записи в БД.
     *
     * @return string
     */
    public function getDateFormat() {

        if (!$this->isPgSql()) {
            return 'Y-m-d H:i:s.u';
        }
        return parent::getDateFormat();
    }


    /**
     * Return table prefix.
     * @return string
     */
    public static function pref() {
        // todo - брать префикс так не правильно!
        return HDB::getTablePrefix();
    }


    /**
     * Преобрабование параметра в строку для БД
     *
     * @param $param
     * @return Expression
     */
    public static function raw($param) {
        return HDB::raw($param);
    }


    /**
     * Alias for DB::select()
     *
     * @param string $query
     * @param array  $bindings
     * @param bool   $useReadPdo
     * @return array
     */
    public static function select($query, array $bindings = [], $useReadPdo = true) {
        return HDB::select($query, $bindings, $useReadPdo);
    }


    /**
     * Alias for DB::transaction()
     *
     * @param $func
     * @return void
     */
    public static function transaction($func) {
        return HDB::transaction($func);
    }


}

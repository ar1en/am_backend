<?php


namespace App\Utils\Helpers\DB;


use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

/**
 * Набор методов для работы с БД приложения
 *
 * @package App\About\Utils\Helpers
 */
class HDB {

    // для явного определения PGSQL - имя драйвера
    public const C_PGSQL_DRIVER = 'pgsql';

    // для раных типов БД используется разный like
    public const C_LIKER_MSSQL = 'like';
    public const C_LIKER_PGSQL = 'ilike';

    private static string $_defaultConnectionName = '';

    private static ?ConnectionInterface $_defaultConnection = null;


    public static function setAppDefaultConnection(string $appConnectionName) {
        if (!self::$_defaultConnectionName) {
            self::$_defaultConnectionName = $appConnectionName;
        }
    }

    public static function getDefaultConnection(){
        if (!self::$_defaultConnection && self::$_defaultConnectionName) {
            self::$_defaultConnection = DB::connection(self::$_defaultConnectionName);
        }
        return self::$_defaultConnection;
    }



    /**
     * Check is mssql database driver
     * @return bool
     */
    public static function isPgSql() {
        return config('database.default') === self::C_PGSQL_DRIVER;  // todo - remake
    }

    /**
     * Return LIKE operator for DB (defined at config file)
     * @return string
     */
    public static function liker(){
        return self::isPgSql()
            ? self::C_LIKER_PGSQL
            : self::C_LIKER_MSSQL ;
    }

    /**
     * @return string
     */
    public static function isBooleanAsBit(){
        return self::isPgSql()
            ? false
            : true;
    }


    /**
     * Add % at start and finish of string
     * @param $search
     * @return string
     */
    public static function likeStrAny($search) {
        return trim($search) ? "%{$search}%" : "%";
    }


    /**
     * @param Builder $q
     * @param string[] $fields
     * @param string $search
     * @return mixed
     */
    public static function whereLikeAny($q,$fields,$search){
        if ( $search ) {
            $q->where(function ($qq) use ($fields, $search) {
                $s = self::likeStrAny($search);
                foreach ($fields as $field) {
                    $qq->orWhere($field, self::liker(), $s);
                }
            });
        }
        return $q;
    }


    /**
     * Return tables prefix.
     * @return mixed
     */
    public static function getTablePrefix() {
        /** @noinspection PhpUndefinedMethodInspection */
        return self::getDefaultConnection()->getTablePrefix();
        // return DB::getTablePrefix();
    }

    /**
     * @param $param
     * @return Expression
     */
    public static function raw($param) {
        return self::getDefaultConnection()->raw($param);
        // return DB::raw($param);
    }


    /**
     * Alias for DB::select()
     * @param string $query
     * @param array  $bindings
     * @param bool   $useReadPdo
     * @return array
     */
    public static function select(string $query, array $bindings = [], bool $useReadPdo = true) {
        return self::getDefaultConnection()->select($query, $bindings, $useReadPdo);
        // return DB::select($query, $bindings, $useReadPdo);
    }


    /**
     * Alias for DB::transaction()
     * @param $func
     * @return mixed
     */
    public static function transaction($func) {
        return $func($func);
    }




}

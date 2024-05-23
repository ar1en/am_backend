<?php

/** @noinspection PhpUnused */


namespace App\Utils\Helpers\DB\ModelGenerator;

use App\Utils\Helpers\JsonData\JsonModelTransformer;
use Illuminate\Database\Connection;

/**
 * Class HDBStructure
 *
 * Read DB structure and generate model classes.
 *
 * Generate Laravel Model classes like 'MDataTableName' with fields.
 * Generate (if not exists) extended Model classes like 'DDataTableName' extends MDataTableName
 *
 * @package App\BPM\Utils\Helpers\DB
 */
class HDBStructure {

    // columns names
    const F_COL_TABLE_CATALOG            = 'table_catalog';
    const F_COL_TABLE_SCHEMA             = 'table_schema';
    const F_COL_TABLE_NAME               = 'table_name';
    const F_COL_COLUMN_NAME              = 'column_name';
    const F_COL_ORDINAL_POSITION         = 'ordinal_position';
    const F_COL_COLUMN_DEFAULT           = 'column_default';
    const F_COL_IS_NULLABLE              = 'is_nullable';
    const F_COL_DATA_TYPE                = 'data_type';
    const F_COL_UDT_NAME                 = 'udt_name';
    const F_COL_CHARACTER_MAXIMUM_LENGTH = 'character_maximum_length';
    const F_COL_IS_UPDATABLE             = 'is_updatable';

    const F_TAB_TABLE_CATALOG = 'table_catalog';
    const F_TAB_TABLE_SCHEMA  = 'table_schema';
    const F_TAB_TABLE_NAME    = 'table_name';

    const F_VEW_TABLE_CATALOG   = 'table_catalog';
    const F_VEW_TABLE_SCHEMA    = 'table_schema';
    const F_VEW_TABLE_NAME      = 'table_name';
    const F_VEW_VIEW_DEFINITION = 'view_definition';

    const F_PRI_COLUMN_NAME     = 'column_name';
    const F_PRI_CONSTRAINT_NAME = 'constraint_name';
    const F_PRI_TABLE_CATALOG   = 'table_catalog';
    const F_PRI_TABLE_SCHEMA    = 'table_schema';
    const F_PRI_TABLE_NAME      = 'table_name';

    const F_UNI_COLUMN_NAME     = 'column_name';
    const F_UNI_CONSTRAINT_NAME = 'constraint_name';
    const F_UNI_TABLE_CATALOG   = 'table_catalog';
    const F_UNI_TABLE_SCHEMA    = 'table_schema';
    const F_UNI_TABLE_NAME      = 'table_name';

    const F_FOR_CONSTRAINT_NAME     = 'constraint_name';
    const F_FOR_TABLE_CATALOG       = 'table_catalog';
    const F_FOR_TABLE_SCHEMA        = 'table_schema';
    const F_FOR_TABLE_NAME          = 'table_name';
    const F_FOR_COLUMN_NAME         = 'column_name';
    const F_FOR_FOREIGN_TABLE_NAME  = 'foreign_table_name';
    const F_FOR_FOREIGN_COLUMN_NAME = 'foreign_column_name';

    // get schema requests
    const SQL_TABLES  = /** @lang text */
        "SELECT *  FROM information_schema.tables WHERE table_schema='public' AND table_type='BASE TABLE' AND TABLE_NAME ilike ?;";
    const SQL_VIEWS   = /** @lang text */
        "select * from INFORMATION_SCHEMA.views WHERE TABLE_NAME ilike ?;";
    const SQL_COLUMNS = /** @lang text */
        "SELECT * FROM information_schema.columns WHERE table_name ilike ?;"; // table_name
    const SQL_PRIMARY = /** @lang text */
        "SELECT c.column_name, c.ordinal_position, t.*
            FROM information_schema.key_column_usage AS c
            LEFT JOIN information_schema.table_constraints AS t
            ON t.constraint_name = c.constraint_name
            WHERE t.table_name ilike ? AND t.constraint_type = 'PRIMARY KEY';"; // table_name
    const SQL_UNIQUE  = /** @lang text */
        "SELECT c.column_name, c.ordinal_position, t.*
            FROM information_schema.key_column_usage AS c
            LEFT JOIN information_schema.table_constraints AS t
            ON t.constraint_name = c.constraint_name
            WHERE t.table_name ilike ? AND t.constraint_type = 'UNIQUE';"; // table_name
    const SQL_FOREIGN = /** @lang text */
        "select c.constraint_name
                , c.*
                , x.table_schema as schema_name
                , x.table_name
                , x.column_name
                , y.table_schema as foreign_schema_name
                , y.table_name as foreign_table_name
                , y.column_name as foreign_column_name
            from information_schema.referential_constraints c
            left join information_schema.key_column_usage x
                on x.constraint_name = c.constraint_name
            left join information_schema.key_column_usage y
                on y.ordinal_position = x.position_in_unique_constraint
                and y.constraint_name = c.unique_constraint_name
            where x.table_name ilike ?
            order by c.constraint_name, x.ordinal_position"; // table_name


    /**
     * Relation DB data type and json|typescript type
     *
     * @var array|string[]
     */
    public array $fieldTypes = [
        'bool'      => 'boolean',
        'uuid'      => 'string',
        'varchar'   => 'string',
        'text'      => 'string',
        'integer'   => 'int',
        'int'       => 'int',
        'int4'      => 'int',
        'int8'      => 'int',
        'timestamp' => '\\DateTime',
    ];

    /**
     * @var array|string[] - Json types for typescript generation
     */
    public array $fieldTypesJson = [
        'bool'      => 'boolean',
        'uuid'      => 'string',
        'varchar'   => 'string',
        'text'      => 'string',
        'integer'   => 'number',
        'int'       => 'number',
        'int4'      => 'number',
        'int8'      => 'number',
        'timestamp' => 'string',
    ];

    /**
     * Transform methods fo JsonModel class
     * @var array
     */
    public array $fieldJsonTransformer = [
        'timestamp' => JsonModelTransformer::JF_DATETIME,
    ];


    private Connection $_connection;

    private string     $tablePrefix;
    private array      $tables  = [];
    private array      $views   = [];
    private array      $columns = [];
    private array      $foreign = [];
    private array      $primary = [];
    private array      $unique  = [];


    /**
     * HDBStructure constructor.
     * @param Connection $connection
     * @param string     $tablePrefix
     */
    public function __construct($connection, $tablePrefix = '') {
        $this->_connection = $connection;
        $this->tablePrefix = $tablePrefix;
    }

    /**
     * @return Connection
     */
    public function connection() {
        return $this->_connection;
    }

    /**
     * @param $tablePrefix
     */
    public function setTablePrefix($tablePrefix) {
        $this->tablePrefix = $tablePrefix;
    }

    /**
     * @return string
     */
    public function getTablePrefix() {
        return $this->tablePrefix;
    }

    /**
     * @return array
     */
    public function getTables() {
        return $this->tables;
    }

    /**
     * @return array
     */
    public function getViews() {
        return $this->views;
    }

    /**
     * @param $tableName
     * @return array
     */
    public function getColumns($tableName) {
        $result = [];
        foreach ($this->columns as $column) {
            if ($column->{self::F_COL_TABLE_NAME} === $tableName) {
                $result[] = $column;
            }
        }
        uasort($result, function ($a, $b) {
            return intval($a->{self::F_COL_ORDINAL_POSITION}) - intval($b->{self::F_COL_ORDINAL_POSITION});
        });
        return $result;
    }

    /**
     * @param string $tableName
     * @return array
     */
    public function getPrimary(string $tableName) {
        $result = [];
        foreach ($this->primary as $item) {
            if ($item->{self::F_PRI_TABLE_NAME} === $tableName) {
                $result[$item->{self::F_PRI_COLUMN_NAME}] = $item;
            }
        }
        return $result;
    }

    /**
     * @param string $tableName
     * @return array
     */
    public function getUnique(string $tableName) {
        $result = [];
        foreach ($this->unique as $item) {
            if ($item->{self::F_UNI_TABLE_NAME} === $tableName) {
                $result[$item->{self::F_UNI_COLUMN_NAME}] = $item;
            }
        }
        return $result;
    }

    /**
     * @param string $tableName
     * @param bool   $asMany
     * @return array
     */
    public function getForeing(string $tableName, bool $asMany) {
        $result = [];
        $tab = $asMany ? self::F_FOR_FOREIGN_TABLE_NAME : self::F_FOR_TABLE_NAME;
        foreach ($this->foreign as $item) {
            if ($item->{$tab} === $tableName) {
                if (!isset($result[$item->{self::F_FOR_CONSTRAINT_NAME}])) {
                    $result[$item->{self::F_FOR_CONSTRAINT_NAME}] = [];
                }
                $result[$item->{self::F_FOR_CONSTRAINT_NAME}][] = $item;
            }
        }
        return $result;
    }

    /**
     *  Read info about table objects
     */
    public function readSchema() {
        $tableFilter = $this->tablePrefix . '%';
        $this->tables = $this->connection()->select(self::SQL_TABLES, [$tableFilter]);
        $this->views = $this->connection()->select(self::SQL_VIEWS, [$tableFilter]);
        $this->columns = $this->connection()->select(self::SQL_COLUMNS, [$tableFilter]);
        $this->foreign = $this->connection()->select(self::SQL_FOREIGN, [$tableFilter]);
        $this->primary = $this->connection()->select(self::SQL_PRIMARY, [$tableFilter]);
        $this->unique = $this->connection()->select(self::SQL_UNIQUE, [$tableFilter]);
    }

    /**
     * Make php type name by udt type
     * @param string $udtName
     * @return string
     */
    public function getTypePhp(string $udtName) {
        if (isset($this->fieldTypes[$udtName])) {
            return $this->fieldTypes[$udtName];
        }
        // unknown type - generate as error
        return "string({$udtName})";
    }

    /**
     * @param string $udtName
     * @return mixed|string
     */
    public function getTypeJson(string $udtName) {
        if (isset($this->fieldTypesJson[$udtName])) {
            return $this->fieldTypesJson[$udtName];
        }
        // unknown type - generate as error
        return "string({$udtName})";
    }

    /**
     * @param string $udtName
     * @return string | null
     */
    public function getJsonTransformer(string $udtName) {
        if (isset($this->fieldJsonTransformer[$udtName])) {
            return $this->fieldJsonTransformer[$udtName];
        }
        // unknown type - generate as error
        return null;
    }


}

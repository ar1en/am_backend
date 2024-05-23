<?php


namespace App\Utils\Helpers\DB\ModelGenerator;


use App\Utils\Helpers\Formats\HString;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ReflectionClass;
use ReflectionException;


/**
 * Class HDBModelClass
 * @package App\Utils\Helpers\DB\Model
 */
class HDBModelClass {

    public const PATH_SUFFIX_MODEL = 'Model';
    public const PATH_SUFFIX_DATA = 'Data';

    /**
     * Loaded DB structure
     * @var HDBStructure
     */
    private HDBStructure $_hdb;

    /**
     * Base class for Model objects
     * @var string
     */
    private string       $_modelClassBase; //   = DBClass::class;

    //
    // prefix for Data class and Model class (Data extents Model)
    //
    private string       $modelClassPrefix = 'M';
    private string       $dataClassPrefix  = 'D';

    private string       $_tableName;
    private string       $_tableNameNoPrefix;

    private array        $_constants = [];
    private array        $_properties = [];
    private array        $_uses = [];


    /**
     * HDBModelClass constructor.
     *
     * @param HDBStructure $hdb
     * @param string       $modelClassBase
     * @param string       $tableName
     */
    public function __construct(HDBStructure $hdb, string $modelClassBase, string $tableName) {
        $this->_hdb = $hdb;
        $this->_modelClassBase = $modelClassBase;
        $this->_tableName = $tableName;
        $this->_tableNameNoPrefix = substr($tableName, strlen($hdb->getTablePrefix()));
        $this->reflector = new ReflectionClass($this->_modelClassBase);
    }


    /**
     * @param $name
     * @param $value
     */
    private function constantsAdd($name,$value){
        $this->_constants[] = [
            "\tconst ",
            $name,
            '=',
            "'{$value}';",
        ];
    }

    /**
     * @param $type
     * @param $name
     * @param $pos
     * @param $typeNote
     * @param $typeNull
     * @param $isPrimary
     * @param $isUnique
     */
    private function propertiesAdd($type,$name,$pos,$typeNote,$typeNull,$isPrimary,$isUnique){
        $this->_properties[] = [
            " * @property ",
            $type,
            $name,
            $pos,
            $typeNote,
            $typeNull,
            $isPrimary,
            $isUnique,
        ];
    }


    /**
     * @param $name
     */
    private function usesAdd($name){
        $this->_uses[$name] = [
            "use ",
            $name.";",
        ];
    }



    /*
     * ----------------------------------------------------------------
     * Make names
     * ----------------------------------------------------------------
     */

    /**
     * Make name jsonable convert "is_archive" to  "isArchive"
     * @param $value
     * @return string
     */
    public static function makeNameJsonProperty($value) {
        return lcfirst(implode("", array_map(function ($a) {
            return ucfirst($a);
        }, explode("_", $value))));
    }

    /**
     * Make name like is_boolean -> IsBoolean
     * @param string $columnName
     * @return string
     */
    public static function makeNameField(string $columnName) {
        $lines = explode('_', $columnName);
        foreach ($lines as $key => $item) {
            $lines[$key] = ucfirst($item);
        }
        return implode('', $lines);
    }

    /**
     * @param string $tableName
     * @param string $tablePrefix
     * @param string $classNamePrefix
     * @return string
     */
    public static function makeNameClass(string $tableName, string $tablePrefix, string $classNamePrefix = '') {
        $nameWithoutPrefix = substr($tableName, strlen($tablePrefix));
        $lines = explode('_', $nameWithoutPrefix);
        foreach ($lines as $key => $item) {
            $lines[$key] = ucfirst($item);
        }
        return $classNamePrefix . implode('', $lines);
    }

    /**
     * @param $tableName
     * @return string
     */
    public function makeNameModelClass($tableName) {
         return HDBModelClass::makeNameClass($tableName, $this->_hdb->getTablePrefix(), $this->modelClassPrefix);
    }

    /**
     * @param $tableName
     * @return string
     */
    public function makeNameDataClass($tableName) {
        return HDBModelClass::makeNameClass($tableName, $this->_hdb->getTablePrefix(), $this->dataClassPrefix);
    }



    /**
     * @return string
     */
    public function makeNameModelClassNamespace() {
        return $this->reflector->getNamespaceName(). "\\" . self::PATH_SUFFIX_MODEL;
    }

    /**
     * @return string
     */
    public function makeNameDataClassNamespace() {
        return $this->reflector->getNamespaceName() . "\\" . self::PATH_SUFFIX_DATA;
    }

    /**
     * @param $suffix
     * @return string
     */
    public function makeClassPath($suffix) {
        return dirname($this->reflector->getFileName()) . ($suffix ? "/{$suffix}" : "");
    }

    /*
     * ----------------------------------------------------------------
     * Update files
     * ----------------------------------------------------------------
     */

    /**
     * @param bool $isView
     * @return string
     * @throws ReflectionException
     */
    public function updateClassFile(bool $isView = false) {

        // make MModel file name
        $modelClassName = $this->makeNameModelClass($this->_tableName);
        $pathModel = $this->makeClassPath(self::PATH_SUFFIX_MODEL);
        $fileModel = $pathModel . '/' . $modelClassName . ".php";
        $modelClassContent = $this->generateModelClass($this->_tableName, $isView);

        // make DModel file name
        $dataClassName = $this->makeNameDataClass($this->_tableName);
        $pathData = $this->makeClassPath(self::PATH_SUFFIX_DATA);
        $fileData = $pathData . '/' . $dataClassName . ".php";
        $dataClassContent = $this->generateDataClass($this->_tableName);

        // write files
        $result = "\r\n\tUpdate class [{$modelClassName}] & [{$dataClassName}]:";
        $result .= $this->writeMModelFile($fileModel, $modelClassContent);
        $result .= $this->writeDModelFile($fileData, $dataClassContent);

        return $result;

    }

    /**
     * @param $fileModel
     * @param $modelClassContent
     * @return string
     */
    private function writeMModelFile($fileModel, $modelClassContent) {
        $result = '';
        $content = file_exists($fileModel) ? file_get_contents($fileModel) : '';
        if ($content === $modelClassContent) {
            $result .= "\r\n\t\tFile MODEL is actual [$fileModel].";
        } else {
            if (file_exists($fileModel)) {
                $result = "\r\n\t\tRemove old MODEL file [$fileModel]...";
                $result .= unlink($fileModel) ? ' OK.' : ' ERROR!';
            }
            file_put_contents($fileModel, $modelClassContent);
            $result .= "\r\n\t\tFile MODEL created [{$fileModel}].";
        }
        return $result;
    }

    /**
     * @param $fileData
     * @param $dataClassContent
     * @return string
     */
    private function writeDModelFile($fileData, $dataClassContent) {

        $result = '';

        // create DModel file
        if (!file_exists($fileData)) {
            file_put_contents($fileData, $dataClassContent);
            $result .= "\r\n\t\tFile DATA created [{$fileData}].";
        } else {
            $result .= "\r\n\t\tFile DATA exists [{$fileData}].";
        }

        return $result;
    }




    /**
     * @param string $tableName
     * @return string
     */
    public function generateDataClass(string $tableName) {


        $dataNamespace = $this->makeNameDataClassNamespace();
        $dataClassName = $this->makeNameDataClass($tableName);

        $modelNamespace = $this->makeNameModelClassNamespace();
        $modelClassName = $this->makeNameModelClass($tableName);

        $use = "use {$modelNamespace}\\{$modelClassName};";


        $str = "<?php

namespace {$dataNamespace};

{$use}

/**
 * Class {$dataClassName}
 * Data class for db table {$tableName}.
 * @package {$dataNamespace}
 */
class {$dataClassName} extends {$modelClassName} {

}
        ";




        return $str;

    }

    /**
     * @param string $tableName
     * @param bool   $isView
     * @return string
     */
    public function generateModelClass(string $tableName, bool $isView) {


        // make model class names
        $modelNamespace = $this->makeNameModelClassNamespace();
        $modelClassName = $this->makeNameModelClass($tableName);
        $extends = $this->reflector->getShortName();


        // get schema info
        $tableColumns = $this->_hdb->getColumns($tableName);
        $tablePrimaryColumns = $this->_hdb->getPrimary($tableName);
        $tableUniqueColumns = $this->_hdb->getUnique($tableName);
        $tableBelongsToColumns = $this->_hdb->getForeing($tableName, false);
        $tableHasManyColumns = $this->_hdb->getForeing($tableName, true);

        // make properties list
        $this->makeModelClassPropertiesArray($tableColumns, $tablePrimaryColumns, $tableUniqueColumns);

        // make constants list
        $this->makeFieldsConstants($tableColumns);

        // make visible list & fillable
        $visible = $this->getVisibleProperty($tableColumns);
        $fillable = $this->getFillableProperty($isView ? [] : $tableColumns, $tablePrimaryColumns);
        $jsonable = $this->getJsonableProperty($tableColumns, $modelClassName);

        // make relations
        $belongsToMethods = $this->getBelongsToMethods($tableBelongsToColumns);
        $hasManyMethods = $this->getHasManyMethods($tableHasManyColumns);

        // make uses list
        $this->usesAdd($this->_modelClassBase);
        $usesText = HString::implodeArrays(array_values($this->_uses));

        // make properties
        $propertiesText = HString::implodeArrays($this->_properties);

        // make constants
        usort($this->_constants, function($a,$b){return strcmp($a[1],$b[1]);});
        $constantsText = HString::implodeArrays($this->_constants);

        return
            "<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace {$modelNamespace};

{$usesText}

/**
 * Class {$modelClassName}
 * Representation for db table {$tableName}.
{$propertiesText}
 * @package {$modelNamespace}
 */
class {$modelClassName} extends {$extends} {

{$constantsText}

    protected \$table = '{$this->_tableNameNoPrefix}';

{$jsonable}

{$visible}

{$fillable}

{$belongsToMethods}

{$hasManyMethods}

}

";

    }



    /*
     * ----------------------------------------------------------------
     * generate content
     * ----------------------------------------------------------------
     */

    /*
     * ----------------------------------------------------------------
     * make texts
     * ----------------------------------------------------------------
     */
    /**
     * @param $columns
     */
    private function makeFieldsConstants($columns) {

        foreach ($columns as $column) {

            $constField = "F_" . strtoupper($column->{HDBStructure::F_COL_COLUMN_NAME});
            $constFieldTable = "FT_" . strtoupper($column->{HDBStructure::F_COL_COLUMN_NAME});
            $constJsonField = "FJ_" . strtoupper($column->{HDBStructure::F_COL_COLUMN_NAME});

            $fieldName = $column->{HDBStructure::F_COL_COLUMN_NAME};
            $jsonName = HDBModelClass::makeNameJsonProperty($column->{HDBStructure::F_COL_COLUMN_NAME});

            $this->constantsAdd($constField,$fieldName);
            $this->constantsAdd($constFieldTable,$this->_tableNameNoPrefix . '.' . $fieldName);
            $this->constantsAdd($constJsonField,$jsonName);

        }

    }

    /**
     * @param $columns
     * @return string
     */
    private function getVisibleProperty($columns) {
        $result = [];
        foreach ($columns as $column) {
            $name = "self::" . "F_" . strtoupper($column->{HDBStructure::F_COL_COLUMN_NAME});
            $result[] = ["\t\t\t{$name},"];
        }
        return "\t\tprotected \$visible = [" .
            HString::implodeArrays($result) .
            "\r\n\t\t]; ";
    }

    /**
     * @param array  $columns
     * @param string $modelClassName
     * @return string
     */
    private function getJsonableProperty($columns, $modelClassName) {

        $result = [];

        // add visible columns list
        // each column has key as a 'json name'  => ['php name','function convert | null','datatype']
        foreach ($columns as $column) {

            $jName = "{$modelClassName}::" . "FJ_" . strtoupper($column->{HDBStructure::F_COL_COLUMN_NAME});
            $name = "{$modelClassName}::" . "F_" . strtoupper($column->{HDBStructure::F_COL_COLUMN_NAME});

            // define transformation function from json | to json
            $transform = $this->_hdb->getJsonTransformer($column->{HDBStructure::F_COL_UDT_NAME});

            // define php property type
            $t = $this->_hdb->getTypeJson($column->{HDBStructure::F_COL_UDT_NAME});

            $result[] = [
                "\t\t{$jName}",                     // add json name
                "=>[",                              //
                $name, ","
                .($transform?"'{$transform}'":"null").","
                ."'{$t}',",
                '],'];
        }

        return "\tpublic static array \$jsonable = [" .
            HString::implodeArrays($result) .
            "\r\n\t];";
    }




    /**
     * @param $columns
     * @param $primary
     * @return string
     */
    private function getFillableProperty($columns, $primary) {
        $result = [];
        foreach ($columns as $column) {
            $colName = $column->{HDBStructure::F_COL_COLUMN_NAME};
            if (isset($primary[$colName])) {
                continue;
            }
            $name = "self::" . "F_" . strtoupper($colName);
            $result[] = ["\t\t\t {$name},"];
        }
        return "\t\tprotected \$fillable = [" .
            HString::implodeArrays($result) .
            "\r\n\t\t];";
    }

    /**
     * @param array $belongsToList
     * @return string
     */
    public function getBelongsToMethods($belongsToList) {

        $result = [];

        foreach ($belongsToList as $columnsArray) {

            if (count($columnsArray) > 1) {
                // ignore multi columns keys
                continue;
            }

            $column = $columnsArray[0];
            $belongClass = $this->makeNameDataClass($column->{HDBStructure::F_FOR_FOREIGN_TABLE_NAME});
            $belongClassFull = $this->makeNameDataClassNamespace() . '\\' . $belongClass;
            $this->usesAdd($belongClassFull);
            $this->usesAdd(BelongsTo::class);
            $methodName = "rel" . HDBModelClass::makeNameField($column->{HDBStructure::F_FOR_COLUMN_NAME});
            $localField = "self::F_" . strtoupper($column->{HDBStructure::F_FOR_COLUMN_NAME});
            $foreingField = "{$belongClass}::F_" . strtoupper($column->{HDBStructure::F_FOR_FOREIGN_COLUMN_NAME});

            $this->propertiesAdd(
                $belongClass,
                $methodName,
                "", "", "", "","");

            $this->constantsAdd('FR_'.strtoupper($column->{HDBStructure::F_FOR_COLUMN_NAME}),$methodName);

            $txt = "
        /**
         * @return {$belongClass}|BelongsTo
         */
        public function {$methodName}(){
            return \$this->belongsTo({$belongClass}::class,{$localField}, {$foreingField});
        }
            ";
            $result[] = $txt;
        }
        return implode("\r\n", $result);
    }

    /**
     * @param array $hasManyList
     * @return string
     */
    public function getHasManyMethods($hasManyList) {

        $result = [];
        foreach ($hasManyList as $columnsArray) {

            if (count($columnsArray) > 1) {
                // ignore multi columns keys
                continue;
            }
            $column = $columnsArray[0];

            $belongClass = $this->makeNameDataClass($column->{HDBStructure::F_FOR_TABLE_NAME});
            $belongClassFull = $this->makeNameDataClassNamespace() . '\\' . $belongClass;
            $methodName = "rels"
                . HDBModelClass::makeNameClass($column->{HDBStructure::F_FOR_TABLE_NAME}, $this->_hdb->getTablePrefix())
                . "By"
                . HDBModelClass::makeNameField($column->{HDBStructure::F_FOR_COLUMN_NAME});
            $localField = "self::F_" . strtoupper($column->{HDBStructure::F_FOR_FOREIGN_COLUMN_NAME});
            $foreingField = "{$belongClass}::F_" . strtoupper($column->{HDBStructure::F_FOR_COLUMN_NAME});

            $this->usesAdd($belongClassFull);
            $this->usesAdd(HasMany::class);
            $this->propertiesAdd(
                $belongClass . "[]",
                $methodName,
                "", "", "", "",'');
            $this->constantsAdd(
                'FR_'.substr(strtoupper($column->{HDBStructure::F_FOR_TABLE_NAME}),strlen($this->_hdb->getTablePrefix()))
                .'_BY_'
                .strtoupper($column->{HDBStructure::F_FOR_COLUMN_NAME}),
                $methodName);

            $txt = "
        /**
         * @return {$belongClass}[]|HasMany
         */
        public function {$methodName}(){
            return \$this->hasMany({$belongClass}::class, {$foreingField}, {$localField});
        }
            ";
            $result[] = $txt;


        }
        return implode("\r\n", $result);
    }

    /**
     * @param array $columns
     * @param array $primary
     * @param array $unique
     */
    public function makeModelClassPropertiesArray($columns, $primary, $unique) {

        foreach ($columns as $column) {

            $type = $this->_hdb->getTypePhp($column->{HDBStructure::F_COL_UDT_NAME});
            $pos = $column->{HDBStructure::F_COL_ORDINAL_POSITION};
            $name = $column->{HDBStructure::F_COL_COLUMN_NAME};
            $maxLength = $column->{HDBStructure::F_COL_CHARACTER_MAXIMUM_LENGTH};
            $isNull = strtolower($column->{HDBStructure::F_COL_IS_NULLABLE}) === 'yes';
            $typeNote = "type:"
                . $column->{HDBStructure::F_COL_UDT_NAME}
                . ($maxLength ? "({$maxLength})" : "");
            $typeNull = $isNull ? "" : "!NULL";
            $isPrimary = isset($primary[$name]);
            $isUnique = isset($unique[$name]);

            $this->propertiesAdd(
                $type,
                $name,
                "[{$pos}]",
                $typeNote,
                $typeNull,
                $isPrimary ? "PRIMARY" : "",
                $isUnique ? "UNIQUE" : ""
            );
        }

    }


}

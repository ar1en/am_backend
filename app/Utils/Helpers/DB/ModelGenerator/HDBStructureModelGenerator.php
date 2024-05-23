<?php


namespace App\Utils\Helpers\DB\ModelGenerator;


use App\Utils\Helpers\DB\Model\MBase;
use App\Utils\Helpers\Reflection\HClass;
use Illuminate\Console\OutputStyle;
use ReflectionException;


/**
 * Class HDBStructureModelGenerator
 * @package App\BPM\Utils\Helpers\DB
 */
class HDBStructureModelGenerator {


    private HDBStructure $_hdb;

    private string       $modelClassBase;


    /**
     * HDBStructureModelGenerator constructor.
     * @param HDBStructure $hdb
     * @param string       $modelBaseClass
     */
    public function __construct(HDBStructure $hdb, string $modelBaseClass) {
        $this->_hdb           = $hdb;
        $this->modelClassBase = $modelBaseClass;
    }

    /**
     * Update DBClasses
     * @param OutputStyle $out
     * @throws ReflectionException
     */
    public function checkClasses(OutputStyle $out) {

        // enum tables
        foreach ($this->_hdb->getTables() as $item) {
            $this->checkTableClass($item, false, $out);
        }

        // enum views
        foreach ($this->_hdb->getViews() as $item) {
            $this->checkTableClass($item, true, $out);
        }

    }

    /**
     * @param OutputStyle $out
     * @throws ReflectionException
     */
    public function checkToRemove(OutputStyle $out) {

        // index all table views names
        $idx     = [];
        $prefLen = strlen($this->_hdb->getTablePrefix());
        array_map(function ($tableRow) use ($prefLen, &$idx) {
            return $idx[substr($tableRow->{HDBStructure::F_TAB_TABLE_NAME}, $prefLen)] = 1;
        }, $this->_hdb->getTables());
        array_map(function ($tableRow) use ($prefLen, &$idx) {
            return $idx[substr($tableRow->{HDBStructure::F_TAB_TABLE_NAME}, $prefLen)] = 1;
        }, $this->_hdb->getViews());

        // check table exists
        $list = HClass::getChildrenClasses(MBase::class);
        /** @var MBase $item */
        foreach ($list as $item) {
            $tab = $item::table();
            if ($tab && !array_key_exists($tab, $idx)) {
                $out->text("Remove class (table not exists: {$tab}): {$item}");
            }
        }


    }

    /**
     * @param mixed            $tableRow
     * @param bool             $isView
     * @param OutputStyle|null $out
     * @throws ReflectionException
     */
    public function checkTableClass($tableRow, bool $isView = false, $out = null) {

        $tName = $tableRow->{HDBStructure::F_TAB_TABLE_NAME};

        // create new class and write file
        $classGenerator = new HDBModelClass($this->_hdb, $this->modelClassBase, $tName);
        $result         = $classGenerator->updateClassFile($isView);

        $out->text("Table [{$tName}]:" . $result);

    }


}

<?php /** @noinspection PhpMissingFieldTypeInspection */


namespace App\Utils\Console\Api;


use App\PVU\Http\Api\Router\ApiRouterBaseTypeScriptDefinition;
use App\PVU\Http\Api\Router\ControllerApi;
use App\PVU\Http\Api\Router\ControllerApiRouter;
use App\Utils\Helpers\Reflection\HClass;
use App\Utils\Helpers\IO\HFiles;
use App\Utils\Helpers\Reflection\HReflectionClass;
use Illuminate\Console\Command;
use Illuminate\Console\OutputStyle;
use ReflectionException;
use Throwable;


/**
 * TypeScript file updater - generate all API ts classes.
 *
 * @package App\Console\Commands\api
 */
abstract class CommandMakeApiTypeScript extends Command {

    /** @var string - имя консольной команды */
    protected $signature = 'make:ts';

    /** @var string - описание команды */
    protected $description = 'Generate TypeScript files for ApiData classes.';

    /** @var OutputStyle - console output */
    private OutputStyle $out;

    /** @var int - Update file counter */
    private int $updateCounter = 1;

    /** @var string[] - Checked files list */
    private array $checkedFiles = [];


    /**
     * @return string like -  __DIR__ . '/../../../../resources/app/remoteTypes/remoteTypes.ts' or null

     */
    abstract public function getTypeScriptSingleFilePath():string;

    /**
     * @return string  like - __DIR__ . '/../../../../resources/app/remoteTypes/cls/';
     */
    abstract public function getTypeScriptFileFolder():string;


    /**
     * Handle command
     */
    public function handle() {
        try {
            $this->doHandle();
        } catch (Throwable $e) {
            $this->out->warning($e->getMessage() . " : " . $e->getFile() . "  (" . $e->getLine() . ")");
            $this->error($e->getMessage(), $e);
        }
    }


    /**
     * @throws ReflectionException
     */
    private function doHandle() {

        $this->out = $this->getOutput();
        $this->out->title($this->description);

        $apiClassesList = HClass::getChildrenClasses(ControllerApi::class);
        usort($apiClassesList, function ($a, $b) {
            /** @var ControllerApi $a */
            /** @var ControllerApi $b */
            return strcmp($a::getApiName(), $b::getApiName());
        });

        // when single file path is defined - write all as single file
        if ( $this->getTypeScriptSingleFilePath() ) {
            $this->out->text('Write as a single file: '.$this->getTypeScriptSingleFilePath());
            $this->updateApiClassesTypeScriptSingleFile($apiClassesList);
        }

        // generate files of types
        if ( $this->getTypeScriptFileFolder() ) {
            $this->out->text('Write as multi files to folder: '.$this->getTypeScriptFileFolder());
            $this->updateApiClassesTypeScriptFilesAtFolder($apiClassesList);
        }


    }

    /**
     * @param string[] $apiRouterBaseClassList
     * @throws ReflectionException
     */
    public function updateApiClassesTypeScriptSingleFile($apiRouterBaseClassList) {

        $classes = [];
        $txtServices = '';
        $txtServices .= "
import {ApiAction} from \"../backend/stores/base/ApiAction\";
import {ApiActionGroup} from \"../backend/stores/base/ApiActionGroup\";

        ";

        // create storage class
        $txtStoreClass = '';

        /** @var HReflectionClass $apiRouteBaseClass */
        foreach ($apiRouterBaseClassList as $apiRouteBaseClass) {

            /** @var ControllerApi $cls */
            $clsRef = new HReflectionClass($apiRouteBaseClass);
            $methodsList = $clsRef->getMethods(ControllerApiRouter::FUNCTION_ACTION_PREFIX);
            foreach ($methodsList as $method) {
                $txtServices .= ApiRouterBaseTypeScriptDefinition::getTypeScriptMethodDefinition($clsRef,
                    $method, $classes);
            }

            $txtStoreClass .= ApiRouterBaseTypeScriptDefinition::getTypeScriptStoreClassDefinition($clsRef);

        }

        // add store classes
        $txtServices .= $txtStoreClass;

        /** @var HReflectionClass $cls */
        $txtServices .= "\r\n\r\n\r\n//region Additional types\r\n";
        usort($classes, function ($a, $b) { return strcmp($a->getName(), $b->getname()); });
        $imports = [];
        foreach ($classes as $cls) {
            $txtServices .= $cls->getTypeScriptDefinition(false, $imports);
        }
        $txtServices .= "\r\n\r\n//endregion\r\n";


        file_put_contents($this->getTypeScriptSingleFilePath(), $txtServices);

        $this->out->text("Updated/verified TypeScript definitions file (" . count($classes) . " classes): "
            . $this->getTypeScriptSingleFilePath());

    }

    /**
     * @param $apiRouterBaseClassList
     * @throws ReflectionException
     */
    public function updateApiClassesTypeScriptFilesAtFolder($apiRouterBaseClassList) {

        // collect all classes list
        /** @var HReflectionClass[] $classes */
        $classes = [];
        $storeClasses = [];

        /** @var HReflectionClass $apiRouteBaseClass */
        foreach ($apiRouterBaseClassList as $apiRouteBaseClass) {

            /** @var ControllerApi $cls */
            $clsRef = new HReflectionClass($apiRouteBaseClass);
            $methodsList = $clsRef->getMethods(ControllerApiRouter::FUNCTION_ACTION_PREFIX);
            $storeImports = [];
            $storeFunctions = [];

            // get api functions definitions
            foreach ($methodsList as $method) {
                $def = ApiRouterBaseTypeScriptDefinition::getTypeScriptMethodDefinition($clsRef, $method, $storeImports);
                $storeFunctions[] = $def;
            }

            // collect store classes
            $storeDef = ApiRouterBaseTypeScriptDefinition::getTypeScriptStoreClassDefinition($clsRef);
            $storeClasses[] = [
                'class'      => $clsRef,
                'definition' => $storeDef,
                'functions'  => $storeFunctions,
                'imports'    => $storeImports,
            ];

            // add all classes list
            $classes = array_merge($classes, $storeImports);
        }


        foreach ($classes as $class) {
            $this->updateClassFile($class);
        }

        foreach ($storeClasses as $class) {
            $this->updateStoreClassFile($class);
        }

        $cntRemoved = $this->removeFiles();

        $cntStore = count($storeClasses);
        $cntClass = count($classes);
        $cntAll = $this->updateCounter - 1;
        $this->out->text("Generated ({$cntStore}) Store classes and ($cntClass) Data classes, updated files: {$cntAll} ");
        if ( $cntRemoved>0 ) {
            $this->out->text("Removed files ({$cntRemoved})");
        }else {
            $this->out->text("No one file removed!");
        }

    }

    /**
     * @param $storeClass
     */
    private function updateStoreClassFile($storeClass) {

        $hRefClass = $storeClass['class'];
        $clsName = $hRefClass->getTypeScriptName();
        $fileName = $this->getTypeScriptFileFolder() . $clsName . ".ts";

        $def = $storeClass['definition'];
        $functions = $storeClass['functions'];
        $imports = $storeClass['imports'];

        $newContent =
            "import {ApiAction} from \"../../applib/api/ApiAction\";\r\n" .
            "import {ApiActionGroup} from \"../../applib/api/ApiActionGroup\";\r\n".
            "import {ApiActionStore} from \"../../applib/api/ApiActionStore\";\r\n";

        $newContent .= $this->getImports($imports)
            . implode("\r\n", $functions)
            . "\r\n"
            . $def;

        $this->updateFile($fileName, $newContent);

    }

    /**
     * @param HReflectionClass $hRefClass
     * @throws ReflectionException
     */
    private function updateClassFile(HReflectionClass $hRefClass) {
        $imports = [];
        $clsName = $hRefClass->getTypeScriptName();
        $fileName = $this->getTypeScriptFileFolder() . $clsName . ".ts";
        $clsDef = $hRefClass->getTypeScriptDefinition(false, $imports);
        $newContent = $this->getImports($imports) . $clsDef;
        $this->updateFile($fileName, $newContent);

    }

    /**
     * @param HReflectionClass[] $imports
     * @return string
     */
    private function getImports($imports) {
        return implode("\r\n", array_map(function ($el) {
                $name = $el->getTypeScriptName();
                return "import {{$name}} from './{$name}'";
            }, $imports)) . "\r\n";
    }

    /**
     * @param $fileName
     * @param $newContent
     */
    private function updateFile($fileName, $newContent) {
        $this->checkedFiles[basename($fileName)] = 1;
        $content = file_exists($fileName) ? file_get_contents($fileName) : "";
        if ($content !== $newContent) {
            $this->out->text($this->updateCounter . ") updated: " . $fileName);
            $this->updateCounter++;
            file_put_contents($fileName, $newContent);
        }
    }

    /**
     * Remove unused files
     * @return int
     */
    private function removeFiles(){
        $files = HFiles::list($this->getTypeScriptFileFolder(),['ts']);
        $cnt = 0;
        foreach ($files as $file){
            if ( !array_key_exists($file['name'],$this->checkedFiles) ){
                unlink($file['path']);
                $cnt++;
                $this->out->text("Remove file: {$file['name']}");
            }
        }
        return $cnt;
    }
}

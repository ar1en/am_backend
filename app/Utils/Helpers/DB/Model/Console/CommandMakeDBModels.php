<?php /** @noinspection PhpMissingFieldTypeInspection */


namespace App\Utils\Helpers\DB\Model\Console;


use App\Utils\Helpers\DB\ModelGenerator\HDBStructure;
use App\Utils\Helpers\DB\ModelGenerator\HDBStructureModelGenerator;
use Illuminate\Console\Command;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use ReflectionException;


/**
 * Class DBModels
 *
 * Console command - php artisan make:dbModels [db objects prefix]
 * Generate Eloquent Model class from database scheme.
 *
 * @package App\Console\Commands
 */
abstract class CommandMakeDBModels extends Command {


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:dbModels {prefix : tables prefix}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Model classes from db schemes';


    /**
     * Return DB connection name for Model generation
     * @return string
     */
    abstract public function getConnectionName(): string;


    /**
     * Return full base class (example DBClass)  name
     * @return string
     */
    abstract public function getDBModelBaseClassName(): string;


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        try {
            $this->doHandle();
        } catch (ReflectionException $e) {
            $this->error($e->getMessage(), $e);
        }
        return 0;
    }

    /**
     * @throws ReflectionException
     */
    private function doHandle() {

        // get db objects prefix
        $prefix = $this->argument('prefix');

        // get output console
        $out = $this->getOutput();
        $out->title($this->description);
        $out->text("Read DB objects with prefix [$prefix]...");

        // make DB connection
        /** @var Connection $dbc */
        $dbc = DB::connection($this->getConnectionName());

        if ($prefix <> "noprefix"){
            $dbc->setTablePrefix($prefix);
        }

        // read schema
        /** @noinspection PhpUndefinedMethodInspection */
        $hdb = new HDBStructure($dbc, $dbc->getTablePrefix());
        $hdb->readSchema();

        // generate classes
        $hdbGenerator = new HDBStructureModelGenerator($hdb, $this->getDBModelBaseClassName());
        $hdbGenerator->checkClasses($out);

        // list removing
        $hdbGenerator->checkToRemove($out);

    }
}

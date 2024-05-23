<?php /** @noinspection PhpMissingFieldTypeInspection */


namespace App\Utils\Console\NamespaceChecker;


use App\Utils\Helpers\IO\HFiles;
use Illuminate\Console\Command;
use Throwable;

/**
 *
 * Console command - php artisan make:namespaces
 * Check namespace directives for all class files with directory structure in app/.
 *
 * Обходит все файлы в каталоге app/ и сверяет имя namespace  на соответствие пути файла.
 * Если параметр указан true то подменят namespace
 *
 * @package App\Console\Commands
 */
abstract class CommandNamespacesCorrect extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:namespaces {update : true|false} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check namespace directive for all class file at app folder with directory structure.';


    /**
     * Execute the console command.
     */
    public function handle() {
        try {
            $this->doHandle();
        } catch (Throwable $e) {
            $this->warn($e->getMessage() . " : " . $e->getFile() . "  (" . $e->getLine() . ")");
            $this->error($e->getMessage(), $e);
        }

    }

    /**
     * Execute handle()
     */
    private function doHandle() {

        // make update?
        $doUpdate = $this->argument('update')==='true';

        $this->output->title($this->description);
        if  ($doUpdate) {
            $this->output->text("Files will be updated!!!");
        }


        // output root folder info
        $rootPath = $this->getClassRootPath();
        //| $this->getRootAppPath();
        if (!$rootPath) {
            $this->warn('Root folder not found as [app]!');
            return;
        }
        $this->info("Root path: " . $rootPath);

        // find all class files
        $files = HFiles::list($rootPath, ['php'], false, true, 10);
        $this->info("Found files: " . count($files));
        $corrected = 0;
        $notFound  = 0;

        // check each file
        foreach ($files as $k => $file) {

            // get file lines
            $content = file_get_contents($file['path']);
            $lines   = explode("\n", $content);

            // extract namespace value
            $nameLine = array_filter($lines, function ($line) { return str_starts_with(trim($line), "namespace"); });
            if (empty($nameLine)) {
                $this->info("{$k}. Not found namespace for: {$file['path']}");
                $notFound++;
                continue;
            }
            $rowNums   = array_keys($nameLine);
            $rowNum    = array_shift($rowNums);
            $names     = explode(" ", trim(array_shift($nameLine), " \r\n\0\t;"));
            $namespace = end($names);

            // check namespace is correct
            $curNamespace = "App" . str_replace("/", "\\", substr(dirname($file['path']), strlen($rootPath)));
            if ($curNamespace !== $namespace) {

                // update namespace directive value
                $this->info("{$k}. [{$namespace}] !== [{$curNamespace}] for [{$rowNum}]: {$file['path']}");
                $lines[$rowNum] = "namespace {$curNamespace};";
                if ( $doUpdate ) {
                    file_put_contents($file['path'], implode("\r\n", $lines));
                }
                $corrected++;
            }
        }

        $cnt = count($files);
        $this->info("Found {$cnt} files. Corrected {$corrected}. Not found namespace {$notFound}.");
    }


    /**
     * Find root app path
     * @return string
     */
    public function getClassRootPath(): string {
        return app_path();
    }


}

<?php /** @noinspection PhpMissingFieldTypeInspection */


namespace App\Utils\Console\AssetVersionIncrement;


use Illuminate\Console\Command;


/**
 * Class VersionIncrement
 *
 *      php artisan addVersion
 *
 * Команда автоматического увеличения номера версии
 * статического контента приложения.
 * Использовать надо автоматом при выводе сайта с режима обслуживания.
 * *
 * @package App\Commands
 */
class CommandVersionIncrement extends Command {


    public const APP_ASSET_VERSION_NAME = "APP_ASSET_VERSION";

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:asset';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Increment application minor version';

    /**
     * Execute command
     *  - find .env file
     *  - find line with parameter APP_ASSET_VERSION
     *  - add version value, and save file
     */
    public function handle() {

        $this->output->title($this->description);

        $path = app_path()."\..\.env";
        if ( !file_exists($path) ) {
            $this->output->error(".env file not found [{$path}]!");
            return;
        }

        // read file
        $txt = file_get_contents($path);
        $lines = explode("\n",str_replace("\r\n","\n",$txt));

        // check lines
        foreach ($lines as $row=>$line){
            if ( str_starts_with($line, self::APP_ASSET_VERSION_NAME ."=") ){
                $this->output->text("Found version line[{$row}]: ".$line);
                $nums = explode('.',$line);
                if ( count($nums)<2 ){
                    $this->output->error("Minor version number not found: ".$line);
                    return;
                }
                $nums[count($nums)-1] = intval($nums[count($nums)-1])+1;
                $lines[$row] = implode('.',$nums);
                $this->output->text("Increment minor version number: ".$lines[$row]);
                file_put_contents($path,implode("\r\n",$lines));
                $this->output->warning("Do not forgot update config:cache!!!");
                return;
            }
        }

        $this->output->error("Version line '".self::APP_ASSET_VERSION_NAME."=' not found at .env file!");

    }

}

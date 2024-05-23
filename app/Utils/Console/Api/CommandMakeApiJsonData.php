<?php /** @noinspection PhpMissingFieldTypeInspection */


namespace App\Utils\Console\Api;


use App\Utils\Helpers\Reflection\HClass;
use App\Utils\Helpers\Reflection\HReflectionClass;
use App\Utils\Helpers\Reflection\HReflectionProperty;
use Illuminate\Console\Command;
use Illuminate\Console\OutputStyle;
use ReflectionException;
use Throwable;

/**
 *
 * JsonData bases classes updater,
 * generate and update function toArray() & fromArray()
 * for all classes inherited on JsonData
 *
 * @package App\Console\Commands\api
 */
abstract class  CommandMakeApiJsonData extends Command {

    /*
     * В целевых классах, наследованных от JsonData добавляем код конвертации в/из json
     * Обалсть сгенерированного кода помечаем якорями начала и конца
     */
    public const C_BLOCK_BEGIN = '/** ApiData start **/';
    public const C_BLOCK_END = '/** ApiData end **/';


    /** @var string - имя консольной команды */
    protected $signature = 'make:jsonData';

    /** @var string - описание команды */
    protected $description = 'Generate toArray() and fromArray() methods for ApiData classes.';


    /** @var int - счетчик обновлений локальный */
    private int $updateCounter = 0;

    /** @var int - счетчик обновлений */
    private int $updateCounterAll = 0;

    /** @var OutputStyle - консоль для вывода */
    private OutputStyle $out;


    /**
     * @return string - default base class is - JsonData::class;
     */
    abstract public function getBaseJsonDataClass(): string;


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
    public function doHandle() {

        $this->out = $this->getOutput();
        $this->out->title($this->description);

        // находим все дочернии классы по отношению к базовому
        $list = HClass::getChildrenClasses($this->getBaseJsonDataClass());

        // обновляем каждый класс
        array_map(function ($cls) {
            $this->updateApiDataClass($cls);
            return $cls;
        }, $list);

        $this->out->text("JsonData classes: updated {$this->updateCounter} from {$this->updateCounterAll}\r\n");
    }

    /**
     * Обвноление php кода класса - добавляем методы toArray() fromArray()
     * @param string $cls
     * @throws ReflectionException
     */
    private function updateApiDataClass($cls) {

        $ref      = new HReflectionClass($cls);
        $rowsTo   = $this->generateRowsToArrayMethod($ref);
        $rowsFrom = $this->generateRowsFromArrowMethod($ref);
        $rows     = array_merge(
            ["\t" . self::C_BLOCK_BEGIN, '',],
            ["\t //region Generated fromArray() toArray() code"],
            $rowsTo,
            $rowsFrom,
            ["\t//endregion"],
            ['', "\t" . self::C_BLOCK_END,],
        );

        // clear content and add
        $file           = $ref->getFileName();
        $content        = file_get_contents($file);
        $contentCleaned = $this->contentClearBlock($content);
        $contentNew     = $this->contentAddBlock($contentCleaned, $rows);

        $this->updateCounterAll++;

        // если есть расхожденя в содержимом - обновляем файл
        if ($contentNew !== $content) {
            file_put_contents($file, $contentNew);
            // Logging
            $this->updateCounter++;
            $this->out->text("{$this->updateCounter})Updated: {$file}");
        }


    }


    /**
     * Генерируем код для методов toArray() fromArray()
     * @param HReflectionClass $ref
     * @return array
     */
    private function generateRowsToArrayMethod($ref) {

        $rowsTo   = [];
        $rowsTo[] = "";
        $rowsTo[] = "\tpublic function toArray(){";
        $rowsTo[] = "\t\t\$arr = [];";

        $props = $ref->getProperties();
        $props = array_filter($props, function ($p) {
            $property = $p->getProperty();
            return $property->isPublic() && (!$property->isStatic());
        });

        foreach ($props as $prop) {
            $rowsTo[] = $this->getRowToArrowMethod($prop);
        }
        $rowsTo[] = "\t\treturn \$arr;";
        $rowsTo[] = "\t}";
        $rowsTo[] = "";

        return $rowsTo;

    }

    /**
     * @param HReflectionClass $ref
     * @return array
     */
    private function generateRowsFromArrowMethod($ref) {

        $rowsFrom   = [];
        $rowsFrom[] = "";
        $rowsFrom[] = "\tpublic function fromArray(array \$data){";

        $props = $ref->getProperties();
        $props = array_filter($props, function ($p) {
            $property = $p->getProperty();
            return $property->isPublic() && (!$property->isStatic());
        });

        foreach ($props as $prop) {
            $rowsFrom[] = $this->getRowFromArrayMethod($prop);
        }

        $rowsFrom[] = "\t\treturn \$this;";
        $rowsFrom[] = "\t}";
        $rowsFrom[] = "";

        return $rowsFrom;
    }


    /**
     * @param HReflectionProperty $prop
     * @return string
     */
    public function getRowToArrowMethod(HReflectionProperty $prop) {

        $name = $prop->getName();
        $type = $prop->getType();


        if ($type->isArray) {
            if ($type->isClass) {
                $txt = "\t\t\$arr['{$name}'] = is_array(\$this->{$name})
                        ?array_map(function(\$el){return \$el->toArray();},\$this->{$name})
                        :null;";
            } else {
                $txt = "\t\t\$arr['{$name}'] = is_array(\$this->{$name})
                        ?array_map(function(\$el){return \$el;},\$this->{$name})
                        :null;";
            }
            return $txt;
        }


        if ($type->isClass) {
            $txt = "\t\t\$arr['{$name}'] = \$this->{$name}?\$this->{$name}->toArray():null;";
        } else {
            $txt = "\t\t\$arr['{$name}'] = \$this->{$name};";
        }

        return $txt;

    }

    /**
     * @param HReflectionProperty $prop
     * @return string
     */
    public function getRowFromArrayMethod(HReflectionProperty $prop) {

        if ($prop->getType()->isArray) {
            $txt = $this->getRowFromArrayMethodTypeArray($prop);
        } else {
            $txt = $this->getRowFromArrayMethodTypeSimple($prop);
        }

        $hasDefault = $prop->getProperty()->isDefault(); // yar 2023-01-31 hasDefaultValue();
        // may be? $default = $hasDefault?$prop->getProperty()->getDefaultValue():null;
        if ($hasDefault) {
            $txt = "\t\tif(array_key_exists('{$prop->getName()}',\$data) && !empty(\$data['{$prop->getName()}'])){\r\n" . $txt . "\r\n\t\t}\r\n";
        }

        return "\r\n\t\t// {$prop->getName()} \r\n" . $txt;

    }

    /**
     * @param HReflectionProperty $prop
     * @return string
     */
    private function getRowFromArrayMethodTypeSimple(HReflectionProperty $prop) {

        $name = $prop->getName();
        $type = $prop->getType();

        // property is not array
        if ($type->isClass) {

            // it is object
            $class = $type->typeClassName;
            if ($type->isNullable) {
                $txt = "\t\t\$this->{$name} = \\{$class}::fromArrayStatic((!isset(\$data['{$name}']))?null:\$data['$name']);";
            } else {
                $txt = "\t\t\$this->{$name} = \\{$class}::fromArrayStatic(\$data['$name']);";
            }
            return $txt;
        }


        // it is simple type - set simple type value
        if ($type->isNullable) {
            $txt = "\t\t\$this->{$name} = (!isset(\$data['{$name}']))" .
                "\r\n\t\t\t?null" .
                "\r\n\t\t\t:\$data['{$name}'];";
        } else {
            $txt = "\t\t\$this->{$name} = \$data['{$name}'];";
        }

        return $txt;
    }

    /**
     * @param HReflectionProperty $prop
     * @return string
     */
    private function getRowFromArrayMethodTypeArray(HReflectionProperty $prop) {

        $name = $prop->getName();
        $type = $prop->getType();

        // property is array
        if ($type->isClass) {
            // array of object
            $class = $type->typeClassName;
            if ($type->isNullable) {
                $txt = "\t\t\$this->{$name} = \\{$class}::fromArrayArrayStatic((!isset(\$data['{$name}']))?null:\$data['$name']);";
            } else {
                $txt = "\t\t\$this->{$name} = \\{$class}::fromArrayArrayStatic(\$data['$name']);";
            }
            return "\r\n\t\t// \r\n" . $txt;
        }


        // array of simple types
        if ($type->isNullable) {
            $txt = "\t\t\$this->{$name} = (isset(\$data['{$name}']) && is_array(\$data['{$name}']) )" .
                "\r\n\t\t\t?\$data['{$name}']" .
                "\r\n\t\t\t:null;";
        } else {
            $txt = "\t\t\$this->{$name} = \$data['{$name}'];";
        }

        return "\r\n\t\t// \r\n" . $txt;

    }

    /**
     * Insert generated rows for ApiData classes.
     *
     * @param string $content
     * @param array  $insert
     * @return string
     */
    public function contentAddBlock(string $content, array $insert) {
        $rows = explode("\n", $content);
        for ($i = count($rows) - 1; $i > 0; $i--) {
            if (str_ends_with(trim($rows[$i]), '}')) {
                array_splice($rows, $i, 0, $insert);
                break;
            }
        }
        return implode("\n", $rows);
    }

    /**
     * Remove injected rows for ApiData classes.
     *
     * @param string $content
     * @return string
     */
    public function contentClearBlock($content) {
        $rows  = explode("\n", $content);
        $block = false;
        $rows  = array_filter($rows, function ($row) use (&$block) {
            if (!$block && (trim($row) === self::C_BLOCK_BEGIN)) {
                $block = true;
            }
            if ($block && (trim($row) === self::C_BLOCK_END)) {
                $block = false;
                return false;
            }
            return !$block;
        });
        return implode("\n", $rows);
    }

}

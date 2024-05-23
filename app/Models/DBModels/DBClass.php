<?php

namespace App\Models\DBModels;

use App\Utils\Helpers\DB\Model\MBase;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class DBClass extends Mbase
{
    use SoftDeletes;

    public $timestamps = true;
    public bool $validFromUntil = true;
    private string $name;

    //Конструктор для того, что все новые инстансы моделей сразу имели ид и добавления наименования модели
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        //Задаем ИД сразу после создания инстанса модели
        $this->setAttribute('id', Str::uuid());

        //Записываем имя модели по имени класса
        $this->name = (new \ReflectionClass($this))->getShortName();
    }

    public function name(): string{
        return $this->name;
    }
}

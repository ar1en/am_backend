<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes, UUID;

    protected $table = 'departments';

    protected $fillable = [
        'bpmId',
        'sasId',
        'name',
        'level',
        'path',
        'created_at',
        'updated_at'
    ];

    protected static array $validationRules = [
        //'name' => ['nullable','string'],
        //'type' => ['uuid','exists:vacancies_types,id','required'],
        //'department'=> ['uuid','exists:departments,id','required'],
        //'validFrom' => 'data',
        //'validUntil' => 'data',
    ];
    public function getValidationRules(): array
    {
        return static::$validationRules;
    }
}

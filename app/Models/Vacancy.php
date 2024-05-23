<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vacancy extends Model
{
    use HasFactory, UUID, SoftDeletes;

    protected $table = 'vacancies';
    protected $fillable = [
        'name',
        'validFrom',
        'validUntil',
        'author',
    ];

    protected static array $validationRules = [
        'name' => ['nullable','string'],
        'VacancyType' => ['uuid','exists:vacancies_types,id','required'],
        'Departments'=> ['uuid','exists:departments,id'],
        //'validFrom' => 'data',
        //'validUntil' => 'data',
    ];
    public function getValidationRules(): array {
        return static::$validationRules;
    }

    public function users(): object {
        return $this->belongsToMany(User::class, 'user_vacancy', 'vacancy', 'user');
    }

    public function VacancyType(): object {
        return $this->belongsTo(VacancyType::class, 'type','id');
    }

    public function Departments(): object {
        return $this->belongsTo(Department::class, 'department', 'id');
    }
}

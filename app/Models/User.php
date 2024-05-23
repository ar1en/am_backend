<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use app\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, UUID;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'author',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static array $validationRules = [
        'name' => ['required','string'],
        'Vacancy' => ['nullable'],
        'Vacancy.*' => ['nullable','uuid','exists:vacancies,id', 'unique:user_vacancy,vacancy'],
        //'Departments'=> ['uuid','exists:departments,id'],
        //'validFrom' => 'data',
        //'validUntil' => 'data',
    ];
    public function getValidationRules(): array {
        return static::$validationRules;
    }

    public function Vacancy(): object
    {
        return $this->belongsToMany(Vacancy::class, 'user_vacancy', 'user', 'vacancy');
    }
}

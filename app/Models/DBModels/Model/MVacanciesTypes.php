<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DVacancies;
use  Illuminate\Database\Eloquent\Relations\HasMany;
use  App\Models\DBModels\DBClass;

/**
 * Class MVacanciesTypes
 * Representation for db table ent_vacancies_types.

 * @property  string       id                  [1] type:uuid      !NULL PRIMARY
 * @property  string       name                [2] type:text      !NULL
 * @property  string       author              [3] type:uuid      !NULL
 * @property  \DateTime    created_at          [4] type:timestamp
 * @property  \DateTime    updated_at          [5] type:timestamp
 * @property  \DateTime    deleted_at          [6] type:timestamp
 * @property  DUsers       relAuthor
 * @property  DVacancies[] relsVacanciesByType
 * @package App\Models\DBModels\Model
 */
class MVacanciesTypes extends DBClass {


	const  FJ_AUTHOR            = 'author';
	const  FJ_CREATED_AT        = 'createdAt';
	const  FJ_DELETED_AT        = 'deletedAt';
	const  FJ_ID                = 'id';
	const  FJ_NAME              = 'name';
	const  FJ_UPDATED_AT        = 'updatedAt';
	const  FR_AUTHOR            = 'relAuthor';
	const  FR_VACANCIES_BY_TYPE = 'relsVacanciesByType';
	const  FT_AUTHOR            = 'vacancies_types.author';
	const  FT_CREATED_AT        = 'vacancies_types.created_at';
	const  FT_DELETED_AT        = 'vacancies_types.deleted_at';
	const  FT_ID                = 'vacancies_types.id';
	const  FT_NAME              = 'vacancies_types.name';
	const  FT_UPDATED_AT        = 'vacancies_types.updated_at';
	const  F_AUTHOR             = 'author';
	const  F_CREATED_AT         = 'created_at';
	const  F_DELETED_AT         = 'deleted_at';
	const  F_ID                 = 'id';
	const  F_NAME               = 'name';
	const  F_UPDATED_AT         = 'updated_at';

    protected $table = 'ent_vacancies_types';

	public static array $jsonable = [
		MVacanciesTypes::FJ_ID         =>[ MVacanciesTypes::F_ID         ,null,'string',           ],
		MVacanciesTypes::FJ_NAME       =>[ MVacanciesTypes::F_NAME       ,null,'string',           ],
		MVacanciesTypes::FJ_AUTHOR     =>[ MVacanciesTypes::F_AUTHOR     ,null,'string',           ],
		MVacanciesTypes::FJ_CREATED_AT =>[ MVacanciesTypes::F_CREATED_AT ,'jsonDateTime','string', ],
		MVacanciesTypes::FJ_UPDATED_AT =>[ MVacanciesTypes::F_UPDATED_AT ,'jsonDateTime','string', ],
		MVacanciesTypes::FJ_DELETED_AT =>[ MVacanciesTypes::F_DELETED_AT ,'jsonDateTime','string', ],
	];

		protected $visible = [
			self::F_ID,
			self::F_NAME,
			self::F_AUTHOR,
			self::F_CREATED_AT,
			self::F_UPDATED_AT,
			self::F_DELETED_AT,
		];

		protected $fillable = [
			 self::F_NAME,
			 self::F_AUTHOR,
			 self::F_CREATED_AT,
			 self::F_UPDATED_AT,
			 self::F_DELETED_AT,
		];


        /**
         * @return DUsers|BelongsTo
         */
        public function relAuthor(){
            return $this->belongsTo(DUsers::class,self::F_AUTHOR, DUsers::F_ID);
        }



        /**
         * @return DVacancies[]|HasMany
         */
        public function relsVacanciesByType(){
            return $this->hasMany(DVacancies::class, DVacancies::F_TYPE, self::F_ID);
        }


}


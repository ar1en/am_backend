<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DDepartments;
use  App\Models\DBModels\Data\DVacanciesTypes;
use  App\Models\DBModels\Data\DProcesses;
use  Illuminate\Database\Eloquent\Relations\HasMany;
use  App\Models\DBModels\DBClass;

/**
 * Class MVacancies
 * Representation for db table ent_vacancies.

 * @property  string          id                   [1]  type:uuid         !NULL PRIMARY
 * @property  string          desc                 [2]  type:varchar(255)
 * @property  string          type                 [3]  type:uuid
 * @property  string          department           [4]  type:uuid         !NULL
 * @property  \DateTime       validFrom            [5]  type:timestamp    !NULL
 * @property  \DateTime       validUntil           [6]  type:timestamp
 * @property  string          author               [7]  type:uuid         !NULL
 * @property  \DateTime       created_at           [8]  type:timestamp
 * @property  \DateTime       updated_at           [9]  type:timestamp
 * @property  \DateTime       deleted_at           [10] type:timestamp
 * @property  DUsers          relAuthor
 * @property  DDepartments    relDepartment
 * @property  DVacanciesTypes relType
 * @property  DProcesses[]    relsProcessesByOwner
 * @package App\Models\DBModels\Model
 */
class MVacancies extends DBClass {


	const  FJ_AUTHOR             = 'author';
	const  FJ_CREATED_AT         = 'createdAt';
	const  FJ_DELETED_AT         = 'deletedAt';
	const  FJ_DEPARTMENT         = 'department';
	const  FJ_DESC               = 'desc';
	const  FJ_ID                 = 'id';
	const  FJ_TYPE               = 'type';
	const  FJ_UPDATED_AT         = 'updatedAt';
	const  FJ_VALIDFROM          = 'validFrom';
	const  FJ_VALIDUNTIL         = 'validUntil';
	const  FR_AUTHOR             = 'relAuthor';
	const  FR_DEPARTMENT         = 'relDepartment';
	const  FR_PROCESSES_BY_OWNER = 'relsProcessesByOwner';
	const  FR_TYPE               = 'relType';
	const  FT_AUTHOR             = 'vacancies.author';
	const  FT_CREATED_AT         = 'vacancies.created_at';
	const  FT_DELETED_AT         = 'vacancies.deleted_at';
	const  FT_DEPARTMENT         = 'vacancies.department';
	const  FT_DESC               = 'vacancies.desc';
	const  FT_ID                 = 'vacancies.id';
	const  FT_TYPE               = 'vacancies.type';
	const  FT_UPDATED_AT         = 'vacancies.updated_at';
	const  FT_VALIDFROM          = 'vacancies.validFrom';
	const  FT_VALIDUNTIL         = 'vacancies.validUntil';
	const  F_AUTHOR              = 'author';
	const  F_CREATED_AT          = 'created_at';
	const  F_DELETED_AT          = 'deleted_at';
	const  F_DEPARTMENT          = 'department';
	const  F_DESC                = 'desc';
	const  F_ID                  = 'id';
	const  F_TYPE                = 'type';
	const  F_UPDATED_AT          = 'updated_at';
	const  F_VALIDFROM           = 'validFrom';
	const  F_VALIDUNTIL          = 'validUntil';

    protected $table = 'ent_vacancies';

	public static array $jsonable = [
		MVacancies::FJ_ID         =>[ MVacancies::F_ID         ,null,'string',           ],
		MVacancies::FJ_DESC       =>[ MVacancies::F_DESC       ,null,'string',           ],
		MVacancies::FJ_TYPE       =>[ MVacancies::F_TYPE       ,null,'string',           ],
		MVacancies::FJ_DEPARTMENT =>[ MVacancies::F_DEPARTMENT ,null,'string',           ],
		MVacancies::FJ_VALIDFROM  =>[ MVacancies::F_VALIDFROM  ,'jsonDateTime','string', ],
		MVacancies::FJ_VALIDUNTIL =>[ MVacancies::F_VALIDUNTIL ,'jsonDateTime','string', ],
		MVacancies::FJ_AUTHOR     =>[ MVacancies::F_AUTHOR     ,null,'string',           ],
		MVacancies::FJ_CREATED_AT =>[ MVacancies::F_CREATED_AT ,'jsonDateTime','string', ],
		MVacancies::FJ_UPDATED_AT =>[ MVacancies::F_UPDATED_AT ,'jsonDateTime','string', ],
		MVacancies::FJ_DELETED_AT =>[ MVacancies::F_DELETED_AT ,'jsonDateTime','string', ],
	];

		protected $visible = [
			self::F_ID,
			self::F_DESC,
			self::F_TYPE,
			self::F_DEPARTMENT,
			self::F_VALIDFROM,
			self::F_VALIDUNTIL,
			self::F_AUTHOR,
			self::F_CREATED_AT,
			self::F_UPDATED_AT,
			self::F_DELETED_AT,
		];

		protected $fillable = [
			 self::F_DESC,
			 self::F_TYPE,
			 self::F_DEPARTMENT,
			 self::F_VALIDFROM,
			 self::F_VALIDUNTIL,
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
         * @return DDepartments|BelongsTo
         */
        public function relDepartment(){
            return $this->belongsTo(DDepartments::class,self::F_DEPARTMENT, DDepartments::F_ID);
        }


        /**
         * @return DVacanciesTypes|BelongsTo
         */
        public function relType(){
            return $this->belongsTo(DVacanciesTypes::class,self::F_TYPE, DVacanciesTypes::F_ID);
        }



        /**
         * @return DProcesses[]|HasMany
         */
        public function relsProcessesByOwner(){
            return $this->hasMany(DProcesses::class, DProcesses::F_OWNER, self::F_ID);
        }


}


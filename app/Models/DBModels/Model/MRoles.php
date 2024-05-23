<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DDepartments;
use  App\Models\DBModels\Data\DObjects;
use  App\Models\DBModels\Data\DRoles;
use  App\Models\DBModels\Data\DProcesses;
use  Illuminate\Database\Eloquent\Relations\HasMany;
use  App\Models\DBModels\DBClass;

/**
 * Class MRoles
 * Representation for db table ent_roles.

 * @property  string       id                    [1]  type:uuid         !NULL PRIMARY UNIQUE
 * @property  string       parentRole            [2]  type:uuid
 * @property  string       department            [3]  type:uuid
 * @property  string       object                [4]  type:uuid
 * @property  string       process               [5]  type:uuid
 * @property  string       name                  [6]  type:varchar(255) !NULL
 * @property  string       author                [7]  type:uuid         !NULL
 * @property  \DateTime    validFrom             [8]  type:timestamp    !NULL
 * @property  \DateTime    validUntil            [9]  type:timestamp
 * @property  \DateTime    created_at            [10] type:timestamp
 * @property  \DateTime    updated_at            [11] type:timestamp
 * @property  \DateTime    deleted_at            [12] type:timestamp
 * @property  DUsers       relAuthor
 * @property  DDepartments relDepartment
 * @property  DObjects     relObject
 * @property  DRoles       relParentRole
 * @property  DProcesses   relProcess
 * @property  DRoles[]     relsRolesByParentRole
 * @package App\Models\DBModels\Model
 */
class MRoles extends DBClass {


	const  FJ_AUTHOR              = 'author';
	const  FJ_CREATED_AT          = 'createdAt';
	const  FJ_DELETED_AT          = 'deletedAt';
	const  FJ_DEPARTMENT          = 'department';
	const  FJ_ID                  = 'id';
	const  FJ_NAME                = 'name';
	const  FJ_OBJECT              = 'object';
	const  FJ_PARENTROLE          = 'parentRole';
	const  FJ_PROCESS             = 'process';
	const  FJ_UPDATED_AT          = 'updatedAt';
	const  FJ_VALIDFROM           = 'validFrom';
	const  FJ_VALIDUNTIL          = 'validUntil';
	const  FR_AUTHOR              = 'relAuthor';
	const  FR_DEPARTMENT          = 'relDepartment';
	const  FR_OBJECT              = 'relObject';
	const  FR_PARENTROLE          = 'relParentRole';
	const  FR_PROCESS             = 'relProcess';
	const  FR_ROLES_BY_PARENTROLE = 'relsRolesByParentRole';
	const  FT_AUTHOR              = 'roles.author';
	const  FT_CREATED_AT          = 'roles.created_at';
	const  FT_DELETED_AT          = 'roles.deleted_at';
	const  FT_DEPARTMENT          = 'roles.department';
	const  FT_ID                  = 'roles.id';
	const  FT_NAME                = 'roles.name';
	const  FT_OBJECT              = 'roles.object';
	const  FT_PARENTROLE          = 'roles.parentRole';
	const  FT_PROCESS             = 'roles.process';
	const  FT_UPDATED_AT          = 'roles.updated_at';
	const  FT_VALIDFROM           = 'roles.validFrom';
	const  FT_VALIDUNTIL          = 'roles.validUntil';
	const  F_AUTHOR               = 'author';
	const  F_CREATED_AT           = 'created_at';
	const  F_DELETED_AT           = 'deleted_at';
	const  F_DEPARTMENT           = 'department';
	const  F_ID                   = 'id';
	const  F_NAME                 = 'name';
	const  F_OBJECT               = 'object';
	const  F_PARENTROLE           = 'parentRole';
	const  F_PROCESS              = 'process';
	const  F_UPDATED_AT           = 'updated_at';
	const  F_VALIDFROM            = 'validFrom';
	const  F_VALIDUNTIL           = 'validUntil';

    protected $table = 'ent_roles';

	public static array $jsonable = [
		MRoles::FJ_ID         =>[ MRoles::F_ID         ,null,'string',           ],
		MRoles::FJ_PARENTROLE =>[ MRoles::F_PARENTROLE ,null,'string',           ],
		MRoles::FJ_DEPARTMENT =>[ MRoles::F_DEPARTMENT ,null,'string',           ],
		MRoles::FJ_OBJECT     =>[ MRoles::F_OBJECT     ,null,'string',           ],
		MRoles::FJ_PROCESS    =>[ MRoles::F_PROCESS    ,null,'string',           ],
		MRoles::FJ_NAME       =>[ MRoles::F_NAME       ,null,'string',           ],
		MRoles::FJ_AUTHOR     =>[ MRoles::F_AUTHOR     ,null,'string',           ],
		MRoles::FJ_VALIDFROM  =>[ MRoles::F_VALIDFROM  ,'jsonDateTime','string', ],
		MRoles::FJ_VALIDUNTIL =>[ MRoles::F_VALIDUNTIL ,'jsonDateTime','string', ],
		MRoles::FJ_CREATED_AT =>[ MRoles::F_CREATED_AT ,'jsonDateTime','string', ],
		MRoles::FJ_UPDATED_AT =>[ MRoles::F_UPDATED_AT ,'jsonDateTime','string', ],
		MRoles::FJ_DELETED_AT =>[ MRoles::F_DELETED_AT ,'jsonDateTime','string', ],
	];

		protected $visible = [
			self::F_ID,
			self::F_PARENTROLE,
			self::F_DEPARTMENT,
			self::F_OBJECT,
			self::F_PROCESS,
			self::F_NAME,
			self::F_AUTHOR,
			self::F_VALIDFROM,
			self::F_VALIDUNTIL,
			self::F_CREATED_AT,
			self::F_UPDATED_AT,
			self::F_DELETED_AT,
		];

		protected $fillable = [
			 self::F_PARENTROLE,
			 self::F_DEPARTMENT,
			 self::F_OBJECT,
			 self::F_PROCESS,
			 self::F_NAME,
			 self::F_AUTHOR,
			 self::F_VALIDFROM,
			 self::F_VALIDUNTIL,
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
         * @return DObjects|BelongsTo
         */
        public function relObject(){
            return $this->belongsTo(DObjects::class,self::F_OBJECT, DObjects::F_ID);
        }


        /**
         * @return DRoles|BelongsTo
         */
        public function relParentRole(){
            return $this->belongsTo(DRoles::class,self::F_PARENTROLE, DRoles::F_ID);
        }


        /**
         * @return DProcesses|BelongsTo
         */
        public function relProcess(){
            return $this->belongsTo(DProcesses::class,self::F_PROCESS, DProcesses::F_ID);
        }



        /**
         * @return DRoles[]|HasMany
         */
        public function relsRolesByParentRole(){
            return $this->hasMany(DRoles::class, DRoles::F_PARENTROLE, self::F_ID);
        }


}


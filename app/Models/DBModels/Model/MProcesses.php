<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DVacancies;
use  App\Models\DBModels\Data\DProcesses;
use  App\Models\DBModels\Data\DProcessesTypes;
use  App\Models\DBModels\Data\DIcsMatrices;
use  Illuminate\Database\Eloquent\Relations\HasMany;
use  App\Models\DBModels\Data\DIcsWorks;
use  App\Models\DBModels\Data\DRoles;
use  App\Models\DBModels\DBClass;

/**
 * Class MProcesses
 * Representation for db table ent_processes.

 * @property  string          id                           [1]  type:uuid         !NULL PRIMARY UNIQUE
 * @property  string          parentProcess                [2]  type:uuid
 * @property  string          path                         [3]  type:text         !NULL
 * @property  int             level                        [4]  type:int4         !NULL
 * @property  string          type                         [5]  type:uuid         !NULL
 * @property  string          sasId                        [6]  type:varchar(255) !NULL         UNIQUE
 * @property  string          bpmId                        [7]  type:varchar(255) !NULL         UNIQUE
 * @property  string          name                         [8]  type:text         !NULL
 * @property  string          code                         [9]  type:text         !NULL
 * @property  string          owner                        [10] type:uuid         !NULL
 * @property  \DateTime       validFrom                    [11] type:timestamp    !NULL
 * @property  \DateTime       validUntil                   [12] type:timestamp
 * @property  string          author                       [13] type:uuid         !NULL
 * @property  \DateTime       created_at                   [14] type:timestamp
 * @property  \DateTime       updated_at                   [15] type:timestamp
 * @property  \DateTime       deleted_at                   [16] type:timestamp
 * @property  DUsers          relAuthor
 * @property  DVacancies      relOwner
 * @property  DProcesses      relParentProcess
 * @property  DProcessesTypes relType
 * @property  DIcsMatrices[]  relsIcsMatricesByProcess
 * @property  DIcsWorks[]     relsIcsWorksByProcess
 * @property  DProcesses[]    relsProcessesByParentProcess
 * @property  DRoles[]        relsRolesByProcess
 * @package App\Models\DBModels\Model
 */
class MProcesses extends DBClass {


	const  FJ_AUTHOR                     = 'author';
	const  FJ_BPMID                      = 'bpmId';
	const  FJ_CODE                       = 'code';
	const  FJ_CREATED_AT                 = 'createdAt';
	const  FJ_DELETED_AT                 = 'deletedAt';
	const  FJ_ID                         = 'id';
	const  FJ_LEVEL                      = 'level';
	const  FJ_NAME                       = 'name';
	const  FJ_OWNER                      = 'owner';
	const  FJ_PARENTPROCESS              = 'parentProcess';
	const  FJ_PATH                       = 'path';
	const  FJ_SASID                      = 'sasId';
	const  FJ_TYPE                       = 'type';
	const  FJ_UPDATED_AT                 = 'updatedAt';
	const  FJ_VALIDFROM                  = 'validFrom';
	const  FJ_VALIDUNTIL                 = 'validUntil';
	const  FR_AUTHOR                     = 'relAuthor';
	const  FR_ICS_MATRICES_BY_PROCESS    = 'relsIcsMatricesByProcess';
	const  FR_ICS_WORKS_BY_PROCESS       = 'relsIcsWorksByProcess';
	const  FR_OWNER                      = 'relOwner';
	const  FR_PARENTPROCESS              = 'relParentProcess';
	const  FR_PROCESSES_BY_PARENTPROCESS = 'relsProcessesByParentProcess';
	const  FR_ROLES_BY_PROCESS           = 'relsRolesByProcess';
	const  FR_TYPE                       = 'relType';
	const  FT_AUTHOR                     = 'processes.author';
	const  FT_BPMID                      = 'processes.bpmId';
	const  FT_CODE                       = 'processes.code';
	const  FT_CREATED_AT                 = 'processes.created_at';
	const  FT_DELETED_AT                 = 'processes.deleted_at';
	const  FT_ID                         = 'processes.id';
	const  FT_LEVEL                      = 'processes.level';
	const  FT_NAME                       = 'processes.name';
	const  FT_OWNER                      = 'processes.owner';
	const  FT_PARENTPROCESS              = 'processes.parentProcess';
	const  FT_PATH                       = 'processes.path';
	const  FT_SASID                      = 'processes.sasId';
	const  FT_TYPE                       = 'processes.type';
	const  FT_UPDATED_AT                 = 'processes.updated_at';
	const  FT_VALIDFROM                  = 'processes.validFrom';
	const  FT_VALIDUNTIL                 = 'processes.validUntil';
	const  F_AUTHOR                      = 'author';
	const  F_BPMID                       = 'bpmId';
	const  F_CODE                        = 'code';
	const  F_CREATED_AT                  = 'created_at';
	const  F_DELETED_AT                  = 'deleted_at';
	const  F_ID                          = 'id';
	const  F_LEVEL                       = 'level';
	const  F_NAME                        = 'name';
	const  F_OWNER                       = 'owner';
	const  F_PARENTPROCESS               = 'parentProcess';
	const  F_PATH                        = 'path';
	const  F_SASID                       = 'sasId';
	const  F_TYPE                        = 'type';
	const  F_UPDATED_AT                  = 'updated_at';
	const  F_VALIDFROM                   = 'validFrom';
	const  F_VALIDUNTIL                  = 'validUntil';

    protected $table = 'ent_processes';

	public static array $jsonable = [
		MProcesses::FJ_ID            =>[ MProcesses::F_ID            ,null,'string',           ],
		MProcesses::FJ_PARENTPROCESS =>[ MProcesses::F_PARENTPROCESS ,null,'string',           ],
		MProcesses::FJ_PATH          =>[ MProcesses::F_PATH          ,null,'string',           ],
		MProcesses::FJ_LEVEL         =>[ MProcesses::F_LEVEL         ,null,'number',           ],
		MProcesses::FJ_TYPE          =>[ MProcesses::F_TYPE          ,null,'string',           ],
		MProcesses::FJ_SASID         =>[ MProcesses::F_SASID         ,null,'string',           ],
		MProcesses::FJ_BPMID         =>[ MProcesses::F_BPMID         ,null,'string',           ],
		MProcesses::FJ_NAME          =>[ MProcesses::F_NAME          ,null,'string',           ],
		MProcesses::FJ_CODE          =>[ MProcesses::F_CODE          ,null,'string',           ],
		MProcesses::FJ_OWNER         =>[ MProcesses::F_OWNER         ,null,'string',           ],
		MProcesses::FJ_VALIDFROM     =>[ MProcesses::F_VALIDFROM     ,'jsonDateTime','string', ],
		MProcesses::FJ_VALIDUNTIL    =>[ MProcesses::F_VALIDUNTIL    ,'jsonDateTime','string', ],
		MProcesses::FJ_AUTHOR        =>[ MProcesses::F_AUTHOR        ,null,'string',           ],
		MProcesses::FJ_CREATED_AT    =>[ MProcesses::F_CREATED_AT    ,'jsonDateTime','string', ],
		MProcesses::FJ_UPDATED_AT    =>[ MProcesses::F_UPDATED_AT    ,'jsonDateTime','string', ],
		MProcesses::FJ_DELETED_AT    =>[ MProcesses::F_DELETED_AT    ,'jsonDateTime','string', ],
	];

		protected $visible = [
			self::F_ID,
			self::F_PARENTPROCESS,
			self::F_PATH,
			self::F_LEVEL,
			self::F_TYPE,
			self::F_SASID,
			self::F_BPMID,
			self::F_NAME,
			self::F_CODE,
			self::F_OWNER,
			self::F_VALIDFROM,
			self::F_VALIDUNTIL,
			self::F_AUTHOR,
			self::F_CREATED_AT,
			self::F_UPDATED_AT,
			self::F_DELETED_AT,
		];

		protected $fillable = [
			 self::F_PARENTPROCESS,
			 self::F_PATH,
			 self::F_LEVEL,
			 self::F_TYPE,
			 self::F_SASID,
			 self::F_BPMID,
			 self::F_NAME,
			 self::F_CODE,
			 self::F_OWNER,
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
         * @return DVacancies|BelongsTo
         */
        public function relOwner(){
            return $this->belongsTo(DVacancies::class,self::F_OWNER, DVacancies::F_ID);
        }


        /**
         * @return DProcesses|BelongsTo
         */
        public function relParentProcess(){
            return $this->belongsTo(DProcesses::class,self::F_PARENTPROCESS, DProcesses::F_ID);
        }


        /**
         * @return DProcessesTypes|BelongsTo
         */
        public function relType(){
            return $this->belongsTo(DProcessesTypes::class,self::F_TYPE, DProcessesTypes::F_ID);
        }



        /**
         * @return DIcsMatrices[]|HasMany
         */
        public function relsIcsMatricesByProcess(){
            return $this->hasMany(DIcsMatrices::class, DIcsMatrices::F_PROCESS, self::F_ID);
        }


        /**
         * @return DIcsWorks[]|HasMany
         */
        public function relsIcsWorksByProcess(){
            return $this->hasMany(DIcsWorks::class, DIcsWorks::F_PROCESS, self::F_ID);
        }


        /**
         * @return DProcesses[]|HasMany
         */
        public function relsProcessesByParentProcess(){
            return $this->hasMany(DProcesses::class, DProcesses::F_PARENTPROCESS, self::F_ID);
        }


        /**
         * @return DRoles[]|HasMany
         */
        public function relsRolesByProcess(){
            return $this->hasMany(DRoles::class, DRoles::F_PROCESS, self::F_ID);
        }


}


<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DDepartments;
use  App\Models\DBModels\Data\DAutomatedMonitoring;
use  Illuminate\Database\Eloquent\Relations\HasMany;
use  App\Models\DBModels\Data\DCollegiateBodies;
use  App\Models\DBModels\Data\DExternalInspections;
use  App\Models\DBModels\Data\DInternalInspections;
use  App\Models\DBModels\Data\DObjects;
use  App\Models\DBModels\Data\DRoles;
use  App\Models\DBModels\Data\DVacancies;
use  App\Models\DBModels\DBClass;

/**
 * Class MDepartments
 * Representation for db table ent_departments.

 * @property  string                 id                                        [1]  type:uuid         !NULL PRIMARY UNIQUE
 * @property  string                 parentDepartment                          [2]  type:uuid
 * @property  string                 bpmId                                     [3]  type:varchar(255)
 * @property  string                 sasId                                     [4]  type:varchar(255)
 * @property  string                 name                                      [5]  type:text         !NULL
 * @property  int                    level                                     [6]  type:int4         !NULL
 * @property  string                 path                                      [7]  type:text         !NULL
 * @property  string                 author                                    [8]  type:uuid         !NULL
 * @property  \DateTime              created_at                                [9]  type:timestamp
 * @property  \DateTime              updated_at                                [10] type:timestamp
 * @property  \DateTime              deleted_at                                [11] type:timestamp
 * @property  DUsers                 relAuthor
 * @property  DDepartments           relParentDepartment
 * @property  DAutomatedMonitoring[] relsAutomatedMonitoringByDepartment
 * @property  DCollegiateBodies[]    relsCollegiateBodiesBySourceDepartment
 * @property  DDepartments[]         relsDepartmentsByParentDepartment
 * @property  DExternalInspections[] relsExternalInspectionsBySourceDepartment
 * @property  DInternalInspections[] relsInternalInspectionsByDepartment
 * @property  DObjects[]             relsObjectsBySupervisingDivision
 * @property  DRoles[]               relsRolesByDepartment
 * @property  DVacancies[]           relsVacanciesByDepartment
 * @package App\Models\DBModels\Model
 */
class MDepartments extends DBClass {


	const  FJ_AUTHOR                                   = 'author';
	const  FJ_BPMID                                    = 'bpmId';
	const  FJ_CREATED_AT                               = 'createdAt';
	const  FJ_DELETED_AT                               = 'deletedAt';
	const  FJ_ID                                       = 'id';
	const  FJ_LEVEL                                    = 'level';
	const  FJ_NAME                                     = 'name';
	const  FJ_PARENTDEPARTMENT                         = 'parentDepartment';
	const  FJ_PATH                                     = 'path';
	const  FJ_SASID                                    = 'sasId';
	const  FJ_UPDATED_AT                               = 'updatedAt';
	const  FR_AUTHOR                                   = 'relAuthor';
	const  FR_AUTOMATED_MONITORING_BY_DEPARTMENT       = 'relsAutomatedMonitoringByDepartment';
	const  FR_COLLEGIATE_BODIES_BY_SOURCEDEPARTMENT    = 'relsCollegiateBodiesBySourceDepartment';
	const  FR_DEPARTMENTS_BY_PARENTDEPARTMENT          = 'relsDepartmentsByParentDepartment';
	const  FR_EXTERNAL_INSPECTIONS_BY_SOURCEDEPARTMENT = 'relsExternalInspectionsBySourceDepartment';
	const  FR_INTERNAL_INSPECTIONS_BY_DEPARTMENT       = 'relsInternalInspectionsByDepartment';
	const  FR_OBJECTS_BY_SUPERVISINGDIVISION           = 'relsObjectsBySupervisingDivision';
	const  FR_PARENTDEPARTMENT                         = 'relParentDepartment';
	const  FR_ROLES_BY_DEPARTMENT                      = 'relsRolesByDepartment';
	const  FR_VACANCIES_BY_DEPARTMENT                  = 'relsVacanciesByDepartment';
	const  FT_AUTHOR                                   = 'departments.author';
	const  FT_BPMID                                    = 'departments.bpmId';
	const  FT_CREATED_AT                               = 'departments.created_at';
	const  FT_DELETED_AT                               = 'departments.deleted_at';
	const  FT_ID                                       = 'departments.id';
	const  FT_LEVEL                                    = 'departments.level';
	const  FT_NAME                                     = 'departments.name';
	const  FT_PARENTDEPARTMENT                         = 'departments.parentDepartment';
	const  FT_PATH                                     = 'departments.path';
	const  FT_SASID                                    = 'departments.sasId';
	const  FT_UPDATED_AT                               = 'departments.updated_at';
	const  F_AUTHOR                                    = 'author';
	const  F_BPMID                                     = 'bpmId';
	const  F_CREATED_AT                                = 'created_at';
	const  F_DELETED_AT                                = 'deleted_at';
	const  F_ID                                        = 'id';
	const  F_LEVEL                                     = 'level';
	const  F_NAME                                      = 'name';
	const  F_PARENTDEPARTMENT                          = 'parentDepartment';
	const  F_PATH                                      = 'path';
	const  F_SASID                                     = 'sasId';
	const  F_UPDATED_AT                                = 'updated_at';

    protected $table = 'env_departments';

	public static array $jsonable = [
		MDepartments::FJ_ID               =>[ MDepartments::F_ID               ,null,'string',           ],
		MDepartments::FJ_PARENTDEPARTMENT =>[ MDepartments::F_PARENTDEPARTMENT ,null,'string',           ],
		MDepartments::FJ_BPMID            =>[ MDepartments::F_BPMID            ,null,'string',           ],
		MDepartments::FJ_SASID            =>[ MDepartments::F_SASID            ,null,'string',           ],
		MDepartments::FJ_NAME             =>[ MDepartments::F_NAME             ,null,'string',           ],
		MDepartments::FJ_LEVEL            =>[ MDepartments::F_LEVEL            ,null,'number',           ],
		MDepartments::FJ_PATH             =>[ MDepartments::F_PATH             ,null,'string',           ],
		MDepartments::FJ_AUTHOR           =>[ MDepartments::F_AUTHOR           ,null,'string',           ],
		MDepartments::FJ_CREATED_AT       =>[ MDepartments::F_CREATED_AT       ,'jsonDateTime','string', ],
		MDepartments::FJ_UPDATED_AT       =>[ MDepartments::F_UPDATED_AT       ,'jsonDateTime','string', ],
		MDepartments::FJ_DELETED_AT       =>[ MDepartments::F_DELETED_AT       ,'jsonDateTime','string', ],
	];

		protected $visible = [
			self::F_ID,
			self::F_PARENTDEPARTMENT,
			self::F_BPMID,
			self::F_SASID,
			self::F_NAME,
			self::F_LEVEL,
			self::F_PATH,
			self::F_AUTHOR,
			self::F_CREATED_AT,
			self::F_UPDATED_AT,
			self::F_DELETED_AT,
		];

		protected $fillable = [
			 self::F_PARENTDEPARTMENT,
			 self::F_BPMID,
			 self::F_SASID,
			 self::F_NAME,
			 self::F_LEVEL,
			 self::F_PATH,
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
        public function relParentDepartment(){
            return $this->belongsTo(DDepartments::class,self::F_PARENTDEPARTMENT, DDepartments::F_ID);
        }



        /**
         * @return DAutomatedMonitoring[]|HasMany
         */
        public function relsAutomatedMonitoringByDepartment(){
            return $this->hasMany(DAutomatedMonitoring::class, DAutomatedMonitoring::F_DEPARTMENT, self::F_ID);
        }


        /**
         * @return DCollegiateBodies[]|HasMany
         */
        public function relsCollegiateBodiesBySourceDepartment(){
            return $this->hasMany(DCollegiateBodies::class, DCollegiateBodies::F_SOURCEDEPARTMENT, self::F_ID);
        }


        /**
         * @return DDepartments[]|HasMany
         */
        public function relsDepartmentsByParentDepartment(){
            return $this->hasMany(DDepartments::class, DDepartments::F_PARENTDEPARTMENT, self::F_ID);
        }


        /**
         * @return DExternalInspections[]|HasMany
         */
        public function relsExternalInspectionsBySourceDepartment(){
            return $this->hasMany(DExternalInspections::class, DExternalInspections::F_SOURCEDEPARTMENT, self::F_ID);
        }


        /**
         * @return DInternalInspections[]|HasMany
         */
        public function relsInternalInspectionsByDepartment(){
            return $this->hasMany(DInternalInspections::class, DInternalInspections::F_DEPARTMENT, self::F_ID);
        }


        /**
         * @return DObjects[]|HasMany
         */
        public function relsObjectsBySupervisingDivision(){
            return $this->hasMany(DObjects::class, DObjects::F_SUPERVISINGDIVISION, self::F_ID);
        }


        /**
         * @return DRoles[]|HasMany
         */
        public function relsRolesByDepartment(){
            return $this->hasMany(DRoles::class, DRoles::F_DEPARTMENT, self::F_ID);
        }


        /**
         * @return DVacancies[]|HasMany
         */
        public function relsVacanciesByDepartment(){
            return $this->hasMany(DVacancies::class, DVacancies::F_DEPARTMENT, self::F_ID);
        }


}


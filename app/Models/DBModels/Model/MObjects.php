<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DDepartments;
use  App\Models\DBModels\Data\DExternalInspections;
use  Illuminate\Database\Eloquent\Relations\HasMany;
use  App\Models\DBModels\Data\DFines;
use  App\Models\DBModels\Data\DIcsMatrices;
use  App\Models\DBModels\Data\DIcsWorks;
use  App\Models\DBModels\Data\DRoles;
use  App\Models\DBModels\DBClass;

/**
 * Class MObjects
 * Representation for db table ent_objects.

 * @property  string                 id                              [1]  type:uuid      !NULL PRIMARY 
 * @property  string                 sasId                           [2]  type:text      !NULL         
 * @property  string                 name                            [3]  type:text      !NULL         
 * @property  string                 code                            [4]  type:text      !NULL         
 * @property  string                 supervisingDivision             [5]  type:uuid      !NULL         
 * @property  string                 supervisor                      [6]  type:uuid      !NULL         
 * @property  \DateTime              validFrom                       [7]  type:timestamp !NULL         
 * @property  \DateTime              validUntil                      [8]  type:timestamp               
 * @property  string                 author                          [9]  type:uuid      !NULL         
 * @property  \DateTime              created_at                      [10] type:timestamp               
 * @property  \DateTime              updated_at                      [11] type:timestamp               
 * @property  \DateTime              deleted_at                      [12] type:timestamp               
 * @property  DUsers                 relAuthor                                                         
 * @property  DDepartments           relSupervisingDivision                                            
 * @property  DUsers                 relSupervisor                                                     
 * @property  DExternalInspections[] relsExternalInspectionsByObject                                   
 * @property  DFines[]               relsFinesByObject                                                 
 * @property  DIcsMatrices[]         relsIcsMatricesByObject                                           
 * @property  DIcsWorks[]            relsIcsWorksByObject                                              
 * @property  DRoles[]               relsRolesByObject                                                 
 * @package App\Models\DBModels\Model
 */
class MObjects extends DBClass {


	const  FJ_AUTHOR                         = 'author';
	const  FJ_CODE                           = 'code';
	const  FJ_CREATED_AT                     = 'createdAt';
	const  FJ_DELETED_AT                     = 'deletedAt';
	const  FJ_ID                             = 'id';
	const  FJ_NAME                           = 'name';
	const  FJ_SASID                          = 'sasId';
	const  FJ_SUPERVISINGDIVISION            = 'supervisingDivision';
	const  FJ_SUPERVISOR                     = 'supervisor';
	const  FJ_UPDATED_AT                     = 'updatedAt';
	const  FJ_VALIDFROM                      = 'validFrom';
	const  FJ_VALIDUNTIL                     = 'validUntil';
	const  FR_AUTHOR                         = 'relAuthor';
	const  FR_EXTERNAL_INSPECTIONS_BY_OBJECT = 'relsExternalInspectionsByObject';
	const  FR_FINES_BY_OBJECT                = 'relsFinesByObject';
	const  FR_ICS_MATRICES_BY_OBJECT         = 'relsIcsMatricesByObject';
	const  FR_ICS_WORKS_BY_OBJECT            = 'relsIcsWorksByObject';
	const  FR_ROLES_BY_OBJECT                = 'relsRolesByObject';
	const  FR_SUPERVISINGDIVISION            = 'relSupervisingDivision';
	const  FR_SUPERVISOR                     = 'relSupervisor';
	const  FT_AUTHOR                         = 'objects.author';
	const  FT_CODE                           = 'objects.code';
	const  FT_CREATED_AT                     = 'objects.created_at';
	const  FT_DELETED_AT                     = 'objects.deleted_at';
	const  FT_ID                             = 'objects.id';
	const  FT_NAME                           = 'objects.name';
	const  FT_SASID                          = 'objects.sasId';
	const  FT_SUPERVISINGDIVISION            = 'objects.supervisingDivision';
	const  FT_SUPERVISOR                     = 'objects.supervisor';
	const  FT_UPDATED_AT                     = 'objects.updated_at';
	const  FT_VALIDFROM                      = 'objects.validFrom';
	const  FT_VALIDUNTIL                     = 'objects.validUntil';
	const  F_AUTHOR                          = 'author';
	const  F_CODE                            = 'code';
	const  F_CREATED_AT                      = 'created_at';
	const  F_DELETED_AT                      = 'deleted_at';
	const  F_ID                              = 'id';
	const  F_NAME                            = 'name';
	const  F_SASID                           = 'sasId';
	const  F_SUPERVISINGDIVISION             = 'supervisingDivision';
	const  F_SUPERVISOR                      = 'supervisor';
	const  F_UPDATED_AT                      = 'updated_at';
	const  F_VALIDFROM                       = 'validFrom';
	const  F_VALIDUNTIL                      = 'validUntil';

    protected $table = 'objects';

	public static array $jsonable = [
		MObjects::FJ_ID                  =>[ MObjects::F_ID                  ,null,'string',           ],
		MObjects::FJ_SASID               =>[ MObjects::F_SASID               ,null,'string',           ],
		MObjects::FJ_NAME                =>[ MObjects::F_NAME                ,null,'string',           ],
		MObjects::FJ_CODE                =>[ MObjects::F_CODE                ,null,'string',           ],
		MObjects::FJ_SUPERVISINGDIVISION =>[ MObjects::F_SUPERVISINGDIVISION ,null,'string',           ],
		MObjects::FJ_SUPERVISOR          =>[ MObjects::F_SUPERVISOR          ,null,'string',           ],
		MObjects::FJ_VALIDFROM           =>[ MObjects::F_VALIDFROM           ,'jsonDateTime','string', ],
		MObjects::FJ_VALIDUNTIL          =>[ MObjects::F_VALIDUNTIL          ,'jsonDateTime','string', ],
		MObjects::FJ_AUTHOR              =>[ MObjects::F_AUTHOR              ,null,'string',           ],
		MObjects::FJ_CREATED_AT          =>[ MObjects::F_CREATED_AT          ,'jsonDateTime','string', ],
		MObjects::FJ_UPDATED_AT          =>[ MObjects::F_UPDATED_AT          ,'jsonDateTime','string', ],
		MObjects::FJ_DELETED_AT          =>[ MObjects::F_DELETED_AT          ,'jsonDateTime','string', ],
	];

		protected $visible = [
			self::F_ID,
			self::F_SASID,
			self::F_NAME,
			self::F_CODE,
			self::F_SUPERVISINGDIVISION,
			self::F_SUPERVISOR,
			self::F_VALIDFROM,
			self::F_VALIDUNTIL,
			self::F_AUTHOR,
			self::F_CREATED_AT,
			self::F_UPDATED_AT,
			self::F_DELETED_AT,
		]; 

		protected $fillable = [
			 self::F_SASID,
			 self::F_NAME,
			 self::F_CODE,
			 self::F_SUPERVISINGDIVISION,
			 self::F_SUPERVISOR,
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
        public function relSupervisingDivision(){
            return $this->belongsTo(DDepartments::class,self::F_SUPERVISINGDIVISION, DDepartments::F_ID);
        }
            

        /**
         * @return DUsers|BelongsTo
         */
        public function relSupervisor(){
            return $this->belongsTo(DUsers::class,self::F_SUPERVISOR, DUsers::F_ID);
        }
            


        /**
         * @return DExternalInspections[]|HasMany
         */
        public function relsExternalInspectionsByObject(){
            return $this->hasMany(DExternalInspections::class, DExternalInspections::F_OBJECT, self::F_ID);
        }
            

        /**
         * @return DFines[]|HasMany
         */
        public function relsFinesByObject(){
            return $this->hasMany(DFines::class, DFines::F_OBJECT, self::F_ID);
        }
            

        /**
         * @return DIcsMatrices[]|HasMany
         */
        public function relsIcsMatricesByObject(){
            return $this->hasMany(DIcsMatrices::class, DIcsMatrices::F_OBJECT, self::F_ID);
        }
            

        /**
         * @return DIcsWorks[]|HasMany
         */
        public function relsIcsWorksByObject(){
            return $this->hasMany(DIcsWorks::class, DIcsWorks::F_OBJECT, self::F_ID);
        }
            

        /**
         * @return DRoles[]|HasMany
         */
        public function relsRolesByObject(){
            return $this->hasMany(DRoles::class, DRoles::F_OBJECT, self::F_ID);
        }
            

}


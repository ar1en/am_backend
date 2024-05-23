<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DExternalControllersTypes;
use  App\Models\DBModels\Data\DObjects;
use  App\Models\DBModels\Data\DDepartments;
use  App\Models\DBModels\DBClass;

/**
 * Class MExternalInspections
 * Representation for db table ent_external_inspections.

 * @property  string                    id                        [1] type:uuid      !NULL PRIMARY 
 * @property  string                    externalControllerType    [2] type:uuid      !NULL         
 * @property  string                    desc                      [3] type:text                    
 * @property  string                    object                    [4] type:uuid      !NULL         
 * @property  string                    sourceDepartment          [5] type:uuid      !NULL         
 * @property  string                    author                    [6] type:uuid      !NULL         
 * @property  \DateTime                 created_at                [7] type:timestamp               
 * @property  \DateTime                 updated_at                [8] type:timestamp               
 * @property  \DateTime                 deleted_at                [9] type:timestamp               
 * @property  DUsers                    relAuthor                                                  
 * @property  DExternalControllersTypes relExternalControllerType                                  
 * @property  DObjects                  relObject                                                  
 * @property  DDepartments              relSourceDepartment                                        
 * @package App\Models\DBModels\Model
 */
class MExternalInspections extends DBClass {


	const  FJ_AUTHOR                 = 'author';
	const  FJ_CREATED_AT             = 'createdAt';
	const  FJ_DELETED_AT             = 'deletedAt';
	const  FJ_DESC                   = 'desc';
	const  FJ_EXTERNALCONTROLLERTYPE = 'externalControllerType';
	const  FJ_ID                     = 'id';
	const  FJ_OBJECT                 = 'object';
	const  FJ_SOURCEDEPARTMENT       = 'sourceDepartment';
	const  FJ_UPDATED_AT             = 'updatedAt';
	const  FR_AUTHOR                 = 'relAuthor';
	const  FR_EXTERNALCONTROLLERTYPE = 'relExternalControllerType';
	const  FR_OBJECT                 = 'relObject';
	const  FR_SOURCEDEPARTMENT       = 'relSourceDepartment';
	const  FT_AUTHOR                 = 'external_inspections.author';
	const  FT_CREATED_AT             = 'external_inspections.created_at';
	const  FT_DELETED_AT             = 'external_inspections.deleted_at';
	const  FT_DESC                   = 'external_inspections.desc';
	const  FT_EXTERNALCONTROLLERTYPE = 'external_inspections.externalControllerType';
	const  FT_ID                     = 'external_inspections.id';
	const  FT_OBJECT                 = 'external_inspections.object';
	const  FT_SOURCEDEPARTMENT       = 'external_inspections.sourceDepartment';
	const  FT_UPDATED_AT             = 'external_inspections.updated_at';
	const  F_AUTHOR                  = 'author';
	const  F_CREATED_AT              = 'created_at';
	const  F_DELETED_AT              = 'deleted_at';
	const  F_DESC                    = 'desc';
	const  F_EXTERNALCONTROLLERTYPE  = 'externalControllerType';
	const  F_ID                      = 'id';
	const  F_OBJECT                  = 'object';
	const  F_SOURCEDEPARTMENT        = 'sourceDepartment';
	const  F_UPDATED_AT              = 'updated_at';

    protected $table = 'external_inspections';

	public static array $jsonable = [
		MExternalInspections::FJ_ID                     =>[ MExternalInspections::F_ID                     ,null,'string',           ],
		MExternalInspections::FJ_EXTERNALCONTROLLERTYPE =>[ MExternalInspections::F_EXTERNALCONTROLLERTYPE ,null,'string',           ],
		MExternalInspections::FJ_DESC                   =>[ MExternalInspections::F_DESC                   ,null,'string',           ],
		MExternalInspections::FJ_OBJECT                 =>[ MExternalInspections::F_OBJECT                 ,null,'string',           ],
		MExternalInspections::FJ_SOURCEDEPARTMENT       =>[ MExternalInspections::F_SOURCEDEPARTMENT       ,null,'string',           ],
		MExternalInspections::FJ_AUTHOR                 =>[ MExternalInspections::F_AUTHOR                 ,null,'string',           ],
		MExternalInspections::FJ_CREATED_AT             =>[ MExternalInspections::F_CREATED_AT             ,'jsonDateTime','string', ],
		MExternalInspections::FJ_UPDATED_AT             =>[ MExternalInspections::F_UPDATED_AT             ,'jsonDateTime','string', ],
		MExternalInspections::FJ_DELETED_AT             =>[ MExternalInspections::F_DELETED_AT             ,'jsonDateTime','string', ],
	];

		protected $visible = [
			self::F_ID,
			self::F_EXTERNALCONTROLLERTYPE,
			self::F_DESC,
			self::F_OBJECT,
			self::F_SOURCEDEPARTMENT,
			self::F_AUTHOR,
			self::F_CREATED_AT,
			self::F_UPDATED_AT,
			self::F_DELETED_AT,
		]; 

		protected $fillable = [
			 self::F_EXTERNALCONTROLLERTYPE,
			 self::F_DESC,
			 self::F_OBJECT,
			 self::F_SOURCEDEPARTMENT,
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
         * @return DExternalControllersTypes|BelongsTo
         */
        public function relExternalControllerType(){
            return $this->belongsTo(DExternalControllersTypes::class,self::F_EXTERNALCONTROLLERTYPE, DExternalControllersTypes::F_ID);
        }
            

        /**
         * @return DObjects|BelongsTo
         */
        public function relObject(){
            return $this->belongsTo(DObjects::class,self::F_OBJECT, DObjects::F_ID);
        }
            

        /**
         * @return DDepartments|BelongsTo
         */
        public function relSourceDepartment(){
            return $this->belongsTo(DDepartments::class,self::F_SOURCEDEPARTMENT, DDepartments::F_ID);
        }
            



}


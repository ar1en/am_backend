<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DExternalInspections;
use  Illuminate\Database\Eloquent\Relations\HasMany;
use  App\Models\DBModels\DBClass;

/**
 * Class MExternalControllersTypes
 * Representation for db table ent_external_controllers_types.

 * @property  string                 id                                              [1] type:uuid      !NULL PRIMARY 
 * @property  string                 name                                            [2] type:text      !NULL         
 * @property  string                 author                                          [3] type:uuid      !NULL         
 * @property  \DateTime              created_at                                      [4] type:timestamp               
 * @property  \DateTime              updated_at                                      [5] type:timestamp               
 * @property  \DateTime              deleted_at                                      [6] type:timestamp               
 * @property  DUsers                 relAuthor                                                                        
 * @property  DExternalInspections[] relsExternalInspectionsByExternalControllerType                                  
 * @package App\Models\DBModels\Model
 */
class MExternalControllersTypes extends DBClass {


	const  FJ_AUTHOR                                         = 'author';
	const  FJ_CREATED_AT                                     = 'createdAt';
	const  FJ_DELETED_AT                                     = 'deletedAt';
	const  FJ_ID                                             = 'id';
	const  FJ_NAME                                           = 'name';
	const  FJ_UPDATED_AT                                     = 'updatedAt';
	const  FR_AUTHOR                                         = 'relAuthor';
	const  FR_EXTERNAL_INSPECTIONS_BY_EXTERNALCONTROLLERTYPE = 'relsExternalInspectionsByExternalControllerType';
	const  FT_AUTHOR                                         = 'external_controllers_types.author';
	const  FT_CREATED_AT                                     = 'external_controllers_types.created_at';
	const  FT_DELETED_AT                                     = 'external_controllers_types.deleted_at';
	const  FT_ID                                             = 'external_controllers_types.id';
	const  FT_NAME                                           = 'external_controllers_types.name';
	const  FT_UPDATED_AT                                     = 'external_controllers_types.updated_at';
	const  F_AUTHOR                                          = 'author';
	const  F_CREATED_AT                                      = 'created_at';
	const  F_DELETED_AT                                      = 'deleted_at';
	const  F_ID                                              = 'id';
	const  F_NAME                                            = 'name';
	const  F_UPDATED_AT                                      = 'updated_at';

    protected $table = 'external_controllers_types';

	public static array $jsonable = [
		MExternalControllersTypes::FJ_ID         =>[ MExternalControllersTypes::F_ID         ,null,'string',           ],
		MExternalControllersTypes::FJ_NAME       =>[ MExternalControllersTypes::F_NAME       ,null,'string',           ],
		MExternalControllersTypes::FJ_AUTHOR     =>[ MExternalControllersTypes::F_AUTHOR     ,null,'string',           ],
		MExternalControllersTypes::FJ_CREATED_AT =>[ MExternalControllersTypes::F_CREATED_AT ,'jsonDateTime','string', ],
		MExternalControllersTypes::FJ_UPDATED_AT =>[ MExternalControllersTypes::F_UPDATED_AT ,'jsonDateTime','string', ],
		MExternalControllersTypes::FJ_DELETED_AT =>[ MExternalControllersTypes::F_DELETED_AT ,'jsonDateTime','string', ],
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
         * @return DExternalInspections[]|HasMany
         */
        public function relsExternalInspectionsByExternalControllerType(){
            return $this->hasMany(DExternalInspections::class, DExternalInspections::F_EXTERNALCONTROLLERTYPE, self::F_ID);
        }
            

}


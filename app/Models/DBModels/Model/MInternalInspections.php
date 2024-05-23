<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DDepartments;
use  App\Models\DBModels\DBClass;

/**
 * Class MInternalInspections
 * Representation for db table ent_internal_inspections.

 * @property  string       id            [1] type:uuid      !NULL PRIMARY 
 * @property  string       department    [2] type:uuid      !NULL         
 * @property  string       desc          [3] type:text      !NULL         
 * @property  string       object        [4] type:uuid      !NULL         
 * @property  string       author        [5] type:uuid      !NULL         
 * @property  \DateTime    created_at    [6] type:timestamp               
 * @property  \DateTime    updated_at    [7] type:timestamp               
 * @property  \DateTime    deleted_at    [8] type:timestamp               
 * @property  DUsers       relAuthor                                      
 * @property  DDepartments relDepartment                                  
 * @package App\Models\DBModels\Model
 */
class MInternalInspections extends DBClass {


	const  FJ_AUTHOR     = 'author';
	const  FJ_CREATED_AT = 'createdAt';
	const  FJ_DELETED_AT = 'deletedAt';
	const  FJ_DEPARTMENT = 'department';
	const  FJ_DESC       = 'desc';
	const  FJ_ID         = 'id';
	const  FJ_OBJECT     = 'object';
	const  FJ_UPDATED_AT = 'updatedAt';
	const  FR_AUTHOR     = 'relAuthor';
	const  FR_DEPARTMENT = 'relDepartment';
	const  FT_AUTHOR     = 'internal_inspections.author';
	const  FT_CREATED_AT = 'internal_inspections.created_at';
	const  FT_DELETED_AT = 'internal_inspections.deleted_at';
	const  FT_DEPARTMENT = 'internal_inspections.department';
	const  FT_DESC       = 'internal_inspections.desc';
	const  FT_ID         = 'internal_inspections.id';
	const  FT_OBJECT     = 'internal_inspections.object';
	const  FT_UPDATED_AT = 'internal_inspections.updated_at';
	const  F_AUTHOR      = 'author';
	const  F_CREATED_AT  = 'created_at';
	const  F_DELETED_AT  = 'deleted_at';
	const  F_DEPARTMENT  = 'department';
	const  F_DESC        = 'desc';
	const  F_ID          = 'id';
	const  F_OBJECT      = 'object';
	const  F_UPDATED_AT  = 'updated_at';

    protected $table = 'internal_inspections';

	public static array $jsonable = [
		MInternalInspections::FJ_ID         =>[ MInternalInspections::F_ID         ,null,'string',           ],
		MInternalInspections::FJ_DEPARTMENT =>[ MInternalInspections::F_DEPARTMENT ,null,'string',           ],
		MInternalInspections::FJ_DESC       =>[ MInternalInspections::F_DESC       ,null,'string',           ],
		MInternalInspections::FJ_OBJECT     =>[ MInternalInspections::F_OBJECT     ,null,'string',           ],
		MInternalInspections::FJ_AUTHOR     =>[ MInternalInspections::F_AUTHOR     ,null,'string',           ],
		MInternalInspections::FJ_CREATED_AT =>[ MInternalInspections::F_CREATED_AT ,'jsonDateTime','string', ],
		MInternalInspections::FJ_UPDATED_AT =>[ MInternalInspections::F_UPDATED_AT ,'jsonDateTime','string', ],
		MInternalInspections::FJ_DELETED_AT =>[ MInternalInspections::F_DELETED_AT ,'jsonDateTime','string', ],
	];

		protected $visible = [
			self::F_ID,
			self::F_DEPARTMENT,
			self::F_DESC,
			self::F_OBJECT,
			self::F_AUTHOR,
			self::F_CREATED_AT,
			self::F_UPDATED_AT,
			self::F_DELETED_AT,
		]; 

		protected $fillable = [
			 self::F_DEPARTMENT,
			 self::F_DESC,
			 self::F_OBJECT,
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
            



}


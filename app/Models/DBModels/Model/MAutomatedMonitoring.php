<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DDepartments;
use  App\Models\DBModels\DBClass;

/**
 * Class MAutomatedMonitoring
 * Representation for db table ent_automated_monitoring.

 * @property  string       id            [1] type:uuid      !NULL PRIMARY 
 * @property  string       department    [2] type:uuid      !NULL         
 * @property  string       desc          [3] type:text                    
 * @property  string       author        [4] type:uuid      !NULL         
 * @property  \DateTime    created_at    [5] type:timestamp               
 * @property  \DateTime    updated_at    [6] type:timestamp               
 * @property  \DateTime    deleted_at    [7] type:timestamp               
 * @property  DUsers       relAuthor                                      
 * @property  DDepartments relDepartment                                  
 * @package App\Models\DBModels\Model
 */
class MAutomatedMonitoring extends DBClass {


	const  FJ_AUTHOR     = 'author';
	const  FJ_CREATED_AT = 'createdAt';
	const  FJ_DELETED_AT = 'deletedAt';
	const  FJ_DEPARTMENT = 'department';
	const  FJ_DESC       = 'desc';
	const  FJ_ID         = 'id';
	const  FJ_UPDATED_AT = 'updatedAt';
	const  FR_AUTHOR     = 'relAuthor';
	const  FR_DEPARTMENT = 'relDepartment';
	const  FT_AUTHOR     = 'automated_monitoring.author';
	const  FT_CREATED_AT = 'automated_monitoring.created_at';
	const  FT_DELETED_AT = 'automated_monitoring.deleted_at';
	const  FT_DEPARTMENT = 'automated_monitoring.department';
	const  FT_DESC       = 'automated_monitoring.desc';
	const  FT_ID         = 'automated_monitoring.id';
	const  FT_UPDATED_AT = 'automated_monitoring.updated_at';
	const  F_AUTHOR      = 'author';
	const  F_CREATED_AT  = 'created_at';
	const  F_DELETED_AT  = 'deleted_at';
	const  F_DEPARTMENT  = 'department';
	const  F_DESC        = 'desc';
	const  F_ID          = 'id';
	const  F_UPDATED_AT  = 'updated_at';

    protected $table = 'automated_monitoring';

	public static array $jsonable = [
		MAutomatedMonitoring::FJ_ID         =>[ MAutomatedMonitoring::F_ID         ,null,'string',           ],
		MAutomatedMonitoring::FJ_DEPARTMENT =>[ MAutomatedMonitoring::F_DEPARTMENT ,null,'string',           ],
		MAutomatedMonitoring::FJ_DESC       =>[ MAutomatedMonitoring::F_DESC       ,null,'string',           ],
		MAutomatedMonitoring::FJ_AUTHOR     =>[ MAutomatedMonitoring::F_AUTHOR     ,null,'string',           ],
		MAutomatedMonitoring::FJ_CREATED_AT =>[ MAutomatedMonitoring::F_CREATED_AT ,'jsonDateTime','string', ],
		MAutomatedMonitoring::FJ_UPDATED_AT =>[ MAutomatedMonitoring::F_UPDATED_AT ,'jsonDateTime','string', ],
		MAutomatedMonitoring::FJ_DELETED_AT =>[ MAutomatedMonitoring::F_DELETED_AT ,'jsonDateTime','string', ],
	];

		protected $visible = [
			self::F_ID,
			self::F_DEPARTMENT,
			self::F_DESC,
			self::F_AUTHOR,
			self::F_CREATED_AT,
			self::F_UPDATED_AT,
			self::F_DELETED_AT,
		]; 

		protected $fillable = [
			 self::F_DEPARTMENT,
			 self::F_DESC,
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


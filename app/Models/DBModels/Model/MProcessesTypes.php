<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DProcesses;
use  Illuminate\Database\Eloquent\Relations\HasMany;
use  App\Models\DBModels\DBClass;

/**
 * Class MProcessesTypes
 * Representation for db table ent_processes_types.

 * @property  string       id                  [1] type:uuid      !NULL PRIMARY 
 * @property  string       name                [2] type:text      !NULL         
 * @property  string       author              [3] type:uuid      !NULL         
 * @property  \DateTime    created_at          [4] type:timestamp               
 * @property  \DateTime    updated_at          [5] type:timestamp               
 * @property  \DateTime    deleted_at          [6] type:timestamp               
 * @property  DUsers       relAuthor                                            
 * @property  DProcesses[] relsProcessesByType                                  
 * @package App\Models\DBModels\Model
 */
class MProcessesTypes extends DBClass {


	const  FJ_AUTHOR            = 'author';
	const  FJ_CREATED_AT        = 'createdAt';
	const  FJ_DELETED_AT        = 'deletedAt';
	const  FJ_ID                = 'id';
	const  FJ_NAME              = 'name';
	const  FJ_UPDATED_AT        = 'updatedAt';
	const  FR_AUTHOR            = 'relAuthor';
	const  FR_PROCESSES_BY_TYPE = 'relsProcessesByType';
	const  FT_AUTHOR            = 'processes_types.author';
	const  FT_CREATED_AT        = 'processes_types.created_at';
	const  FT_DELETED_AT        = 'processes_types.deleted_at';
	const  FT_ID                = 'processes_types.id';
	const  FT_NAME              = 'processes_types.name';
	const  FT_UPDATED_AT        = 'processes_types.updated_at';
	const  F_AUTHOR             = 'author';
	const  F_CREATED_AT         = 'created_at';
	const  F_DELETED_AT         = 'deleted_at';
	const  F_ID                 = 'id';
	const  F_NAME               = 'name';
	const  F_UPDATED_AT         = 'updated_at';

    protected $table = 'processes_types';

	public static array $jsonable = [
		MProcessesTypes::FJ_ID         =>[ MProcessesTypes::F_ID         ,null,'string',           ],
		MProcessesTypes::FJ_NAME       =>[ MProcessesTypes::F_NAME       ,null,'string',           ],
		MProcessesTypes::FJ_AUTHOR     =>[ MProcessesTypes::F_AUTHOR     ,null,'string',           ],
		MProcessesTypes::FJ_CREATED_AT =>[ MProcessesTypes::F_CREATED_AT ,'jsonDateTime','string', ],
		MProcessesTypes::FJ_UPDATED_AT =>[ MProcessesTypes::F_UPDATED_AT ,'jsonDateTime','string', ],
		MProcessesTypes::FJ_DELETED_AT =>[ MProcessesTypes::F_DELETED_AT ,'jsonDateTime','string', ],
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
         * @return DProcesses[]|HasMany
         */
        public function relsProcessesByType(){
            return $this->hasMany(DProcesses::class, DProcesses::F_TYPE, self::F_ID);
        }
            

}


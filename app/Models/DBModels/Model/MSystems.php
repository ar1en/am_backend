<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\DBClass;

/**
 * Class MSystems
 * Representation for db table ent_systems.

 * @property  string    id         [1] type:uuid      !NULL PRIMARY 
 * @property  string    name       [2] type:text                    
 * @property  string    author     [3] type:uuid      !NULL         
 * @property  \DateTime created_at [4] type:timestamp               
 * @property  \DateTime updated_at [5] type:timestamp               
 * @property  \DateTime deleted_at [6] type:timestamp               
 * @property  DUsers    relAuthor                                   
 * @package App\Models\DBModels\Model
 */
class MSystems extends DBClass {


	const  FJ_AUTHOR     = 'author';
	const  FJ_CREATED_AT = 'createdAt';
	const  FJ_DELETED_AT = 'deletedAt';
	const  FJ_ID         = 'id';
	const  FJ_NAME       = 'name';
	const  FJ_UPDATED_AT = 'updatedAt';
	const  FR_AUTHOR     = 'relAuthor';
	const  FT_AUTHOR     = 'systems.author';
	const  FT_CREATED_AT = 'systems.created_at';
	const  FT_DELETED_AT = 'systems.deleted_at';
	const  FT_ID         = 'systems.id';
	const  FT_NAME       = 'systems.name';
	const  FT_UPDATED_AT = 'systems.updated_at';
	const  F_AUTHOR      = 'author';
	const  F_CREATED_AT  = 'created_at';
	const  F_DELETED_AT  = 'deleted_at';
	const  F_ID          = 'id';
	const  F_NAME        = 'name';
	const  F_UPDATED_AT  = 'updated_at';

    protected $table = 'systems';

	public static array $jsonable = [
		MSystems::FJ_ID         =>[ MSystems::F_ID         ,null,'string',           ],
		MSystems::FJ_NAME       =>[ MSystems::F_NAME       ,null,'string',           ],
		MSystems::FJ_AUTHOR     =>[ MSystems::F_AUTHOR     ,null,'string',           ],
		MSystems::FJ_CREATED_AT =>[ MSystems::F_CREATED_AT ,'jsonDateTime','string', ],
		MSystems::FJ_UPDATED_AT =>[ MSystems::F_UPDATED_AT ,'jsonDateTime','string', ],
		MSystems::FJ_DELETED_AT =>[ MSystems::F_DELETED_AT ,'jsonDateTime','string', ],
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
            



}


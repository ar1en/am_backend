<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\DBClass;

/**
 * Class MAudits
 * Representation for db table ent_audits.

 * @property  string    id         [1] type:uuid         !NULL PRIMARY 
 * @property  string    sasId      [2] type:varchar(255) !NULL         UNIQUE
 * @property  string    code       [3] type:varchar(255) !NULL         
 * @property  string    name       [4] type:text         !NULL         
 * @property  string    desc       [5] type:text         !NULL         
 * @property  string    author     [6] type:uuid         !NULL         
 * @property  \DateTime created_at [7] type:timestamp                  
 * @property  \DateTime updated_at [8] type:timestamp                  
 * @property  \DateTime deleted_at [9] type:timestamp                  
 * @property  DUsers    relAuthor                                      
 * @package App\Models\DBModels\Model
 */
class MAudits extends DBClass {


	const  FJ_AUTHOR     = 'author';
	const  FJ_CODE       = 'code';
	const  FJ_CREATED_AT = 'createdAt';
	const  FJ_DELETED_AT = 'deletedAt';
	const  FJ_DESC       = 'desc';
	const  FJ_ID         = 'id';
	const  FJ_NAME       = 'name';
	const  FJ_SASID      = 'sasId';
	const  FJ_UPDATED_AT = 'updatedAt';
	const  FR_AUTHOR     = 'relAuthor';
	const  FT_AUTHOR     = 'audits.author';
	const  FT_CODE       = 'audits.code';
	const  FT_CREATED_AT = 'audits.created_at';
	const  FT_DELETED_AT = 'audits.deleted_at';
	const  FT_DESC       = 'audits.desc';
	const  FT_ID         = 'audits.id';
	const  FT_NAME       = 'audits.name';
	const  FT_SASID      = 'audits.sasId';
	const  FT_UPDATED_AT = 'audits.updated_at';
	const  F_AUTHOR      = 'author';
	const  F_CODE        = 'code';
	const  F_CREATED_AT  = 'created_at';
	const  F_DELETED_AT  = 'deleted_at';
	const  F_DESC        = 'desc';
	const  F_ID          = 'id';
	const  F_NAME        = 'name';
	const  F_SASID       = 'sasId';
	const  F_UPDATED_AT  = 'updated_at';

    protected $table = 'audits';

	public static array $jsonable = [
		MAudits::FJ_ID         =>[ MAudits::F_ID         ,null,'string',           ],
		MAudits::FJ_SASID      =>[ MAudits::F_SASID      ,null,'string',           ],
		MAudits::FJ_CODE       =>[ MAudits::F_CODE       ,null,'string',           ],
		MAudits::FJ_NAME       =>[ MAudits::F_NAME       ,null,'string',           ],
		MAudits::FJ_DESC       =>[ MAudits::F_DESC       ,null,'string',           ],
		MAudits::FJ_AUTHOR     =>[ MAudits::F_AUTHOR     ,null,'string',           ],
		MAudits::FJ_CREATED_AT =>[ MAudits::F_CREATED_AT ,'jsonDateTime','string', ],
		MAudits::FJ_UPDATED_AT =>[ MAudits::F_UPDATED_AT ,'jsonDateTime','string', ],
		MAudits::FJ_DELETED_AT =>[ MAudits::F_DELETED_AT ,'jsonDateTime','string', ],
	];

		protected $visible = [
			self::F_ID,
			self::F_SASID,
			self::F_CODE,
			self::F_NAME,
			self::F_DESC,
			self::F_AUTHOR,
			self::F_CREATED_AT,
			self::F_UPDATED_AT,
			self::F_DELETED_AT,
		]; 

		protected $fillable = [
			 self::F_SASID,
			 self::F_CODE,
			 self::F_NAME,
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
            



}


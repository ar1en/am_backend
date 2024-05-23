<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\DBClass;

/**
 * Class MPermissions
 * Representation for db table ent_permissions.

 * @property  string    id         [1] type:uuid         !NULL PRIMARY 
 * @property  string    name       [2] type:varchar(255) !NULL         
 * @property  string    permission [3] type:varchar(255) !NULL         
 * @property  string    desc       [4] type:varchar(255) !NULL         
 * @property  string    author     [5] type:uuid         !NULL         
 * @property  \DateTime created_at [6] type:timestamp                  
 * @property  \DateTime updated_at [7] type:timestamp                  
 * @property  \DateTime deleted_at [8] type:timestamp                  
 * @property  DUsers    relAuthor                                      
 * @package App\Models\DBModels\Model
 */
class MPermissions extends DBClass {


	const  FJ_AUTHOR     = 'author';
	const  FJ_CREATED_AT = 'createdAt';
	const  FJ_DELETED_AT = 'deletedAt';
	const  FJ_DESC       = 'desc';
	const  FJ_ID         = 'id';
	const  FJ_NAME       = 'name';
	const  FJ_PERMISSION = 'permission';
	const  FJ_UPDATED_AT = 'updatedAt';
	const  FR_AUTHOR     = 'relAuthor';
	const  FT_AUTHOR     = 'permissions.author';
	const  FT_CREATED_AT = 'permissions.created_at';
	const  FT_DELETED_AT = 'permissions.deleted_at';
	const  FT_DESC       = 'permissions.desc';
	const  FT_ID         = 'permissions.id';
	const  FT_NAME       = 'permissions.name';
	const  FT_PERMISSION = 'permissions.permission';
	const  FT_UPDATED_AT = 'permissions.updated_at';
	const  F_AUTHOR      = 'author';
	const  F_CREATED_AT  = 'created_at';
	const  F_DELETED_AT  = 'deleted_at';
	const  F_DESC        = 'desc';
	const  F_ID          = 'id';
	const  F_NAME        = 'name';
	const  F_PERMISSION  = 'permission';
	const  F_UPDATED_AT  = 'updated_at';

    protected $table = 'permissions';

	public static array $jsonable = [
		MPermissions::FJ_ID         =>[ MPermissions::F_ID         ,null,'string',           ],
		MPermissions::FJ_NAME       =>[ MPermissions::F_NAME       ,null,'string',           ],
		MPermissions::FJ_PERMISSION =>[ MPermissions::F_PERMISSION ,null,'string',           ],
		MPermissions::FJ_DESC       =>[ MPermissions::F_DESC       ,null,'string',           ],
		MPermissions::FJ_AUTHOR     =>[ MPermissions::F_AUTHOR     ,null,'string',           ],
		MPermissions::FJ_CREATED_AT =>[ MPermissions::F_CREATED_AT ,'jsonDateTime','string', ],
		MPermissions::FJ_UPDATED_AT =>[ MPermissions::F_UPDATED_AT ,'jsonDateTime','string', ],
		MPermissions::FJ_DELETED_AT =>[ MPermissions::F_DELETED_AT ,'jsonDateTime','string', ],
	];

		protected $visible = [
			self::F_ID,
			self::F_NAME,
			self::F_PERMISSION,
			self::F_DESC,
			self::F_AUTHOR,
			self::F_CREATED_AT,
			self::F_UPDATED_AT,
			self::F_DELETED_AT,
		]; 

		protected $fillable = [
			 self::F_NAME,
			 self::F_PERMISSION,
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


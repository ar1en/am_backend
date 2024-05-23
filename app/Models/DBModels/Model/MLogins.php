<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\DBClass;

/**
 * Class MLogins
 * Representation for db table ent_logins.

 * @property  string    id             [1]  type:uuid         !NULL PRIMARY
 * @property  string    user           [2]  type:uuid         !NULL
 * @property  string    login          [3]  type:varchar(255) !NULL
 * @property  string    password       [4]  type:varchar(255) !NULL
 * @property  string    remember_token [5]  type:varchar(255)
 * @property  string    author         [6]  type:uuid         !NULL
 * @property  \DateTime validFrom      [7]  type:timestamp    !NULL
 * @property  \DateTime validUntil     [8]  type:timestamp
 * @property  \DateTime created_at     [9]  type:timestamp
 * @property  \DateTime updated_at     [10] type:timestamp
 * @property  \DateTime deleted_at     [11] type:timestamp
 * @property  DUsers    relAuthor
 * @property  DUsers    relUser
 * @package App\Models\DBModels\Model
 */
class MLogins extends DBClass {


	const  FJ_AUTHOR         = 'author';
	const  FJ_CREATED_AT     = 'createdAt';
	const  FJ_DELETED_AT     = 'deletedAt';
	const  FJ_ID             = 'id';
	const  FJ_LOGIN          = 'login';
	const  FJ_PASSWORD       = 'password';
	const  FJ_REMEMBER_TOKEN = 'rememberToken';
	const  FJ_UPDATED_AT     = 'updatedAt';
	const  FJ_USER           = 'user';
	const  FJ_VALIDFROM      = 'validFrom';
	const  FJ_VALIDUNTIL     = 'validUntil';
	const  FR_AUTHOR         = 'relAuthor';
	const  FR_USER           = 'relUser';
	const  FT_AUTHOR         = 'logins.author';
	const  FT_CREATED_AT     = 'logins.created_at';
	const  FT_DELETED_AT     = 'logins.deleted_at';
	const  FT_ID             = 'logins.id';
	const  FT_LOGIN          = 'logins.login';
	const  FT_PASSWORD       = 'logins.password';
	const  FT_REMEMBER_TOKEN = 'logins.remember_token';
	const  FT_UPDATED_AT     = 'logins.updated_at';
	const  FT_USER           = 'logins.user';
	const  FT_VALIDFROM      = 'logins.validFrom';
	const  FT_VALIDUNTIL     = 'logins.validUntil';
	const  F_AUTHOR          = 'author';
	const  F_CREATED_AT      = 'created_at';
	const  F_DELETED_AT      = 'deleted_at';
	const  F_ID              = 'id';
	const  F_LOGIN           = 'login';
	const  F_PASSWORD        = 'password';
	const  F_REMEMBER_TOKEN  = 'remember_token';
	const  F_UPDATED_AT      = 'updated_at';
	const  F_USER            = 'user';
	const  F_VALIDFROM       = 'validFrom';
	const  F_VALIDUNTIL      = 'validUntil';

    protected $table = 'ent_logins';

	public static array $jsonable = [
		MLogins::FJ_ID             =>[ MLogins::F_ID             ,null,'string',           ],
		MLogins::FJ_USER           =>[ MLogins::F_USER           ,null,'string',           ],
		MLogins::FJ_LOGIN          =>[ MLogins::F_LOGIN          ,null,'string',           ],
		MLogins::FJ_PASSWORD       =>[ MLogins::F_PASSWORD       ,null,'string',           ],
		MLogins::FJ_REMEMBER_TOKEN =>[ MLogins::F_REMEMBER_TOKEN ,null,'string',           ],
		MLogins::FJ_AUTHOR         =>[ MLogins::F_AUTHOR         ,null,'string',           ],
		MLogins::FJ_VALIDFROM      =>[ MLogins::F_VALIDFROM      ,'jsonDateTime','string', ],
		MLogins::FJ_VALIDUNTIL     =>[ MLogins::F_VALIDUNTIL     ,'jsonDateTime','string', ],
		MLogins::FJ_CREATED_AT     =>[ MLogins::F_CREATED_AT     ,'jsonDateTime','string', ],
		MLogins::FJ_UPDATED_AT     =>[ MLogins::F_UPDATED_AT     ,'jsonDateTime','string', ],
		MLogins::FJ_DELETED_AT     =>[ MLogins::F_DELETED_AT     ,'jsonDateTime','string', ],
	];

		protected $visible = [
			self::F_ID,
			self::F_USER,
			self::F_LOGIN,
			self::F_PASSWORD,
			self::F_REMEMBER_TOKEN,
			self::F_AUTHOR,
			self::F_VALIDFROM,
			self::F_VALIDUNTIL,
			self::F_CREATED_AT,
			self::F_UPDATED_AT,
			self::F_DELETED_AT,
		];

		protected $fillable = [
			 self::F_USER,
			 self::F_LOGIN,
			 self::F_PASSWORD,
			 self::F_REMEMBER_TOKEN,
			 self::F_AUTHOR,
			 self::F_VALIDFROM,
			 self::F_VALIDUNTIL,
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
         * @return DUsers|BelongsTo
         */
        public function relUser(){
            return $this->belongsTo(DUsers::class,self::F_USER, DUsers::F_ID);
        }
}


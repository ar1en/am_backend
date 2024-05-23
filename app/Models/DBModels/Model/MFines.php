<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DObjects;
use  App\Models\DBModels\DBClass;

/**
 * Class MFines
 * Representation for db table ent_fines.

 * @property  string         id         [1] type:uuid      !NULL PRIMARY 
 * @property  string         object     [2] type:uuid                    
 * @property  string(float8) sum        [3] type:float8    !NULL         
 * @property  string         desc       [4] type:text                    
 * @property  string         author     [5] type:uuid      !NULL         
 * @property  \DateTime      created_at [6] type:timestamp               
 * @property  \DateTime      updated_at [7] type:timestamp               
 * @property  \DateTime      deleted_at [8] type:timestamp               
 * @property  DUsers         relAuthor                                   
 * @property  DObjects       relObject                                   
 * @package App\Models\DBModels\Model
 */
class MFines extends DBClass {


	const  FJ_AUTHOR     = 'author';
	const  FJ_CREATED_AT = 'createdAt';
	const  FJ_DELETED_AT = 'deletedAt';
	const  FJ_DESC       = 'desc';
	const  FJ_ID         = 'id';
	const  FJ_OBJECT     = 'object';
	const  FJ_SUM        = 'sum';
	const  FJ_UPDATED_AT = 'updatedAt';
	const  FR_AUTHOR     = 'relAuthor';
	const  FR_OBJECT     = 'relObject';
	const  FT_AUTHOR     = 'fines.author';
	const  FT_CREATED_AT = 'fines.created_at';
	const  FT_DELETED_AT = 'fines.deleted_at';
	const  FT_DESC       = 'fines.desc';
	const  FT_ID         = 'fines.id';
	const  FT_OBJECT     = 'fines.object';
	const  FT_SUM        = 'fines.sum';
	const  FT_UPDATED_AT = 'fines.updated_at';
	const  F_AUTHOR      = 'author';
	const  F_CREATED_AT  = 'created_at';
	const  F_DELETED_AT  = 'deleted_at';
	const  F_DESC        = 'desc';
	const  F_ID          = 'id';
	const  F_OBJECT      = 'object';
	const  F_SUM         = 'sum';
	const  F_UPDATED_AT  = 'updated_at';

    protected $table = 'fines';

	public static array $jsonable = [
		MFines::FJ_ID         =>[ MFines::F_ID         ,null,'string',           ],
		MFines::FJ_OBJECT     =>[ MFines::F_OBJECT     ,null,'string',           ],
		MFines::FJ_SUM        =>[ MFines::F_SUM        ,null,'string(float8)',   ],
		MFines::FJ_DESC       =>[ MFines::F_DESC       ,null,'string',           ],
		MFines::FJ_AUTHOR     =>[ MFines::F_AUTHOR     ,null,'string',           ],
		MFines::FJ_CREATED_AT =>[ MFines::F_CREATED_AT ,'jsonDateTime','string', ],
		MFines::FJ_UPDATED_AT =>[ MFines::F_UPDATED_AT ,'jsonDateTime','string', ],
		MFines::FJ_DELETED_AT =>[ MFines::F_DELETED_AT ,'jsonDateTime','string', ],
	];

		protected $visible = [
			self::F_ID,
			self::F_OBJECT,
			self::F_SUM,
			self::F_DESC,
			self::F_AUTHOR,
			self::F_CREATED_AT,
			self::F_UPDATED_AT,
			self::F_DELETED_AT,
		]; 

		protected $fillable = [
			 self::F_OBJECT,
			 self::F_SUM,
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
         * @return DObjects|BelongsTo
         */
        public function relObject(){
            return $this->belongsTo(DObjects::class,self::F_OBJECT, DObjects::F_ID);
        }
            



}


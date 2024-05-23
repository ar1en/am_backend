<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DIcsWorks;
use  Illuminate\Database\Eloquent\Relations\HasMany;
use  App\Models\DBModels\DBClass;

/**
 * Class MIcsWorksTypes
 * Representation for db table ent_ics_works_types.

 * @property  string      id                 [1] type:uuid      !NULL PRIMARY 
 * @property  string      name               [2] type:text      !NULL         
 * @property  string      author             [3] type:uuid      !NULL         
 * @property  \DateTime   created_at         [4] type:timestamp               
 * @property  \DateTime   updated_at         [5] type:timestamp               
 * @property  \DateTime   deleted_at         [6] type:timestamp               
 * @property  DUsers      relAuthor                                           
 * @property  DIcsWorks[] relsIcsWorksByType                                  
 * @package App\Models\DBModels\Model
 */
class MIcsWorksTypes extends DBClass {


	const  FJ_AUTHOR            = 'author';
	const  FJ_CREATED_AT        = 'createdAt';
	const  FJ_DELETED_AT        = 'deletedAt';
	const  FJ_ID                = 'id';
	const  FJ_NAME              = 'name';
	const  FJ_UPDATED_AT        = 'updatedAt';
	const  FR_AUTHOR            = 'relAuthor';
	const  FR_ICS_WORKS_BY_TYPE = 'relsIcsWorksByType';
	const  FT_AUTHOR            = 'ics_works_types.author';
	const  FT_CREATED_AT        = 'ics_works_types.created_at';
	const  FT_DELETED_AT        = 'ics_works_types.deleted_at';
	const  FT_ID                = 'ics_works_types.id';
	const  FT_NAME              = 'ics_works_types.name';
	const  FT_UPDATED_AT        = 'ics_works_types.updated_at';
	const  F_AUTHOR             = 'author';
	const  F_CREATED_AT         = 'created_at';
	const  F_DELETED_AT         = 'deleted_at';
	const  F_ID                 = 'id';
	const  F_NAME               = 'name';
	const  F_UPDATED_AT         = 'updated_at';

    protected $table = 'ics_works_types';

	public static array $jsonable = [
		MIcsWorksTypes::FJ_ID         =>[ MIcsWorksTypes::F_ID         ,null,'string',           ],
		MIcsWorksTypes::FJ_NAME       =>[ MIcsWorksTypes::F_NAME       ,null,'string',           ],
		MIcsWorksTypes::FJ_AUTHOR     =>[ MIcsWorksTypes::F_AUTHOR     ,null,'string',           ],
		MIcsWorksTypes::FJ_CREATED_AT =>[ MIcsWorksTypes::F_CREATED_AT ,'jsonDateTime','string', ],
		MIcsWorksTypes::FJ_UPDATED_AT =>[ MIcsWorksTypes::F_UPDATED_AT ,'jsonDateTime','string', ],
		MIcsWorksTypes::FJ_DELETED_AT =>[ MIcsWorksTypes::F_DELETED_AT ,'jsonDateTime','string', ],
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
         * @return DIcsWorks[]|HasMany
         */
        public function relsIcsWorksByType(){
            return $this->hasMany(DIcsWorks::class, DIcsWorks::F_TYPE, self::F_ID);
        }
            

}


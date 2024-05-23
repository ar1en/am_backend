<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\DBClass;

/**
 * Class MInternalInspectionsTypes
 * Representation for db table ent_internal_inspections_types.

 * @property  string    id         [1] type:uuid      !NULL PRIMARY 
 * @property  string    name       [2] type:text      !NULL         
 * @property  string    author     [3] type:uuid      !NULL         
 * @property  \DateTime created_at [4] type:timestamp               
 * @property  \DateTime updated_at [5] type:timestamp               
 * @property  \DateTime deleted_at [6] type:timestamp               
 * @property  DUsers    relAuthor                                   
 * @package App\Models\DBModels\Model
 */
class MInternalInspectionsTypes extends DBClass {


	const  FJ_AUTHOR     = 'author';
	const  FJ_CREATED_AT = 'createdAt';
	const  FJ_DELETED_AT = 'deletedAt';
	const  FJ_ID         = 'id';
	const  FJ_NAME       = 'name';
	const  FJ_UPDATED_AT = 'updatedAt';
	const  FR_AUTHOR     = 'relAuthor';
	const  FT_AUTHOR     = 'internal_inspections_types.author';
	const  FT_CREATED_AT = 'internal_inspections_types.created_at';
	const  FT_DELETED_AT = 'internal_inspections_types.deleted_at';
	const  FT_ID         = 'internal_inspections_types.id';
	const  FT_NAME       = 'internal_inspections_types.name';
	const  FT_UPDATED_AT = 'internal_inspections_types.updated_at';
	const  F_AUTHOR      = 'author';
	const  F_CREATED_AT  = 'created_at';
	const  F_DELETED_AT  = 'deleted_at';
	const  F_ID          = 'id';
	const  F_NAME        = 'name';
	const  F_UPDATED_AT  = 'updated_at';

    protected $table = 'internal_inspections_types';

	public static array $jsonable = [
		MInternalInspectionsTypes::FJ_ID         =>[ MInternalInspectionsTypes::F_ID         ,null,'string',           ],
		MInternalInspectionsTypes::FJ_NAME       =>[ MInternalInspectionsTypes::F_NAME       ,null,'string',           ],
		MInternalInspectionsTypes::FJ_AUTHOR     =>[ MInternalInspectionsTypes::F_AUTHOR     ,null,'string',           ],
		MInternalInspectionsTypes::FJ_CREATED_AT =>[ MInternalInspectionsTypes::F_CREATED_AT ,'jsonDateTime','string', ],
		MInternalInspectionsTypes::FJ_UPDATED_AT =>[ MInternalInspectionsTypes::F_UPDATED_AT ,'jsonDateTime','string', ],
		MInternalInspectionsTypes::FJ_DELETED_AT =>[ MInternalInspectionsTypes::F_DELETED_AT ,'jsonDateTime','string', ],
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


<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DCollegiateBodies;
use  Illuminate\Database\Eloquent\Relations\HasMany;
use  App\Models\DBModels\DBClass;

/**
 * Class MCollegiateBodiesTypes
 * Representation for db table ent_collegiate_bodies_types.

 * @property  string              id                         [1] type:uuid      !NULL PRIMARY 
 * @property  string              name                       [2] type:text      !NULL         
 * @property  string              author                     [3] type:uuid      !NULL         
 * @property  \DateTime           created_at                 [4] type:timestamp               
 * @property  \DateTime           updated_at                 [5] type:timestamp               
 * @property  \DateTime           deleted_at                 [6] type:timestamp               
 * @property  DUsers              relAuthor                                                   
 * @property  DCollegiateBodies[] relsCollegiateBodiesByType                                  
 * @package App\Models\DBModels\Model
 */
class MCollegiateBodiesTypes extends DBClass {


	const  FJ_AUTHOR                    = 'author';
	const  FJ_CREATED_AT                = 'createdAt';
	const  FJ_DELETED_AT                = 'deletedAt';
	const  FJ_ID                        = 'id';
	const  FJ_NAME                      = 'name';
	const  FJ_UPDATED_AT                = 'updatedAt';
	const  FR_AUTHOR                    = 'relAuthor';
	const  FR_COLLEGIATE_BODIES_BY_TYPE = 'relsCollegiateBodiesByType';
	const  FT_AUTHOR                    = 'collegiate_bodies_types.author';
	const  FT_CREATED_AT                = 'collegiate_bodies_types.created_at';
	const  FT_DELETED_AT                = 'collegiate_bodies_types.deleted_at';
	const  FT_ID                        = 'collegiate_bodies_types.id';
	const  FT_NAME                      = 'collegiate_bodies_types.name';
	const  FT_UPDATED_AT                = 'collegiate_bodies_types.updated_at';
	const  F_AUTHOR                     = 'author';
	const  F_CREATED_AT                 = 'created_at';
	const  F_DELETED_AT                 = 'deleted_at';
	const  F_ID                         = 'id';
	const  F_NAME                       = 'name';
	const  F_UPDATED_AT                 = 'updated_at';

    protected $table = 'collegiate_bodies_types';

	public static array $jsonable = [
		MCollegiateBodiesTypes::FJ_ID         =>[ MCollegiateBodiesTypes::F_ID         ,null,'string',           ],
		MCollegiateBodiesTypes::FJ_NAME       =>[ MCollegiateBodiesTypes::F_NAME       ,null,'string',           ],
		MCollegiateBodiesTypes::FJ_AUTHOR     =>[ MCollegiateBodiesTypes::F_AUTHOR     ,null,'string',           ],
		MCollegiateBodiesTypes::FJ_CREATED_AT =>[ MCollegiateBodiesTypes::F_CREATED_AT ,'jsonDateTime','string', ],
		MCollegiateBodiesTypes::FJ_UPDATED_AT =>[ MCollegiateBodiesTypes::F_UPDATED_AT ,'jsonDateTime','string', ],
		MCollegiateBodiesTypes::FJ_DELETED_AT =>[ MCollegiateBodiesTypes::F_DELETED_AT ,'jsonDateTime','string', ],
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
         * @return DCollegiateBodies[]|HasMany
         */
        public function relsCollegiateBodiesByType(){
            return $this->hasMany(DCollegiateBodies::class, DCollegiateBodies::F_TYPE, self::F_ID);
        }
            

}


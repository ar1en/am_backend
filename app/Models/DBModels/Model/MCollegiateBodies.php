<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DDepartments;
use  App\Models\DBModels\Data\DCollegiateBodiesTypes;
use  App\Models\DBModels\DBClass;

/**
 * Class MCollegiateBodies
 * Representation for db table ent_collegiate_bodies.

 * @property  string                 id                  [1] type:uuid      !NULL PRIMARY 
 * @property  string                 type                [2] type:uuid      !NULL         
 * @property  string                 desc                [3] type:text                    
 * @property  string                 sourceDepartment    [4] type:uuid      !NULL         
 * @property  string                 author              [5] type:uuid      !NULL         
 * @property  \DateTime              created_at          [6] type:timestamp               
 * @property  \DateTime              updated_at          [7] type:timestamp               
 * @property  \DateTime              deleted_at          [8] type:timestamp               
 * @property  DUsers                 relAuthor                                            
 * @property  DDepartments           relSourceDepartment                                  
 * @property  DCollegiateBodiesTypes relType                                              
 * @package App\Models\DBModels\Model
 */
class MCollegiateBodies extends DBClass {


	const  FJ_AUTHOR           = 'author';
	const  FJ_CREATED_AT       = 'createdAt';
	const  FJ_DELETED_AT       = 'deletedAt';
	const  FJ_DESC             = 'desc';
	const  FJ_ID               = 'id';
	const  FJ_SOURCEDEPARTMENT = 'sourceDepartment';
	const  FJ_TYPE             = 'type';
	const  FJ_UPDATED_AT       = 'updatedAt';
	const  FR_AUTHOR           = 'relAuthor';
	const  FR_SOURCEDEPARTMENT = 'relSourceDepartment';
	const  FR_TYPE             = 'relType';
	const  FT_AUTHOR           = 'collegiate_bodies.author';
	const  FT_CREATED_AT       = 'collegiate_bodies.created_at';
	const  FT_DELETED_AT       = 'collegiate_bodies.deleted_at';
	const  FT_DESC             = 'collegiate_bodies.desc';
	const  FT_ID               = 'collegiate_bodies.id';
	const  FT_SOURCEDEPARTMENT = 'collegiate_bodies.sourceDepartment';
	const  FT_TYPE             = 'collegiate_bodies.type';
	const  FT_UPDATED_AT       = 'collegiate_bodies.updated_at';
	const  F_AUTHOR            = 'author';
	const  F_CREATED_AT        = 'created_at';
	const  F_DELETED_AT        = 'deleted_at';
	const  F_DESC              = 'desc';
	const  F_ID                = 'id';
	const  F_SOURCEDEPARTMENT  = 'sourceDepartment';
	const  F_TYPE              = 'type';
	const  F_UPDATED_AT        = 'updated_at';

    protected $table = 'collegiate_bodies';

	public static array $jsonable = [
		MCollegiateBodies::FJ_ID               =>[ MCollegiateBodies::F_ID               ,null,'string',           ],
		MCollegiateBodies::FJ_TYPE             =>[ MCollegiateBodies::F_TYPE             ,null,'string',           ],
		MCollegiateBodies::FJ_DESC             =>[ MCollegiateBodies::F_DESC             ,null,'string',           ],
		MCollegiateBodies::FJ_SOURCEDEPARTMENT =>[ MCollegiateBodies::F_SOURCEDEPARTMENT ,null,'string',           ],
		MCollegiateBodies::FJ_AUTHOR           =>[ MCollegiateBodies::F_AUTHOR           ,null,'string',           ],
		MCollegiateBodies::FJ_CREATED_AT       =>[ MCollegiateBodies::F_CREATED_AT       ,'jsonDateTime','string', ],
		MCollegiateBodies::FJ_UPDATED_AT       =>[ MCollegiateBodies::F_UPDATED_AT       ,'jsonDateTime','string', ],
		MCollegiateBodies::FJ_DELETED_AT       =>[ MCollegiateBodies::F_DELETED_AT       ,'jsonDateTime','string', ],
	];

		protected $visible = [
			self::F_ID,
			self::F_TYPE,
			self::F_DESC,
			self::F_SOURCEDEPARTMENT,
			self::F_AUTHOR,
			self::F_CREATED_AT,
			self::F_UPDATED_AT,
			self::F_DELETED_AT,
		]; 

		protected $fillable = [
			 self::F_TYPE,
			 self::F_DESC,
			 self::F_SOURCEDEPARTMENT,
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
        public function relSourceDepartment(){
            return $this->belongsTo(DDepartments::class,self::F_SOURCEDEPARTMENT, DDepartments::F_ID);
        }
            

        /**
         * @return DCollegiateBodiesTypes|BelongsTo
         */
        public function relType(){
            return $this->belongsTo(DCollegiateBodiesTypes::class,self::F_TYPE, DCollegiateBodiesTypes::F_ID);
        }
            



}


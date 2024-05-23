<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DRisks;
use  Illuminate\Database\Eloquent\Relations\HasMany;
use  App\Models\DBModels\DBClass;

/**
 * Class MRisksTypes
 * Representation for db table ent_risks_types.

 * @property  string    id              [1] type:uuid      !NULL PRIMARY 
 * @property  string    name            [2] type:text      !NULL         
 * @property  string    author          [3] type:uuid      !NULL         
 * @property  \DateTime created_at      [4] type:timestamp               
 * @property  \DateTime updated_at      [5] type:timestamp               
 * @property  \DateTime deleted_at      [6] type:timestamp               
 * @property  DUsers    relAuthor                                        
 * @property  DRisks[]  relsRisksByType                                  
 * @package App\Models\DBModels\Model
 */
class MRisksTypes extends DBClass {


	const  FJ_AUTHOR        = 'author';
	const  FJ_CREATED_AT    = 'createdAt';
	const  FJ_DELETED_AT    = 'deletedAt';
	const  FJ_ID            = 'id';
	const  FJ_NAME          = 'name';
	const  FJ_UPDATED_AT    = 'updatedAt';
	const  FR_AUTHOR        = 'relAuthor';
	const  FR_RISKS_BY_TYPE = 'relsRisksByType';
	const  FT_AUTHOR        = 'risks_types.author';
	const  FT_CREATED_AT    = 'risks_types.created_at';
	const  FT_DELETED_AT    = 'risks_types.deleted_at';
	const  FT_ID            = 'risks_types.id';
	const  FT_NAME          = 'risks_types.name';
	const  FT_UPDATED_AT    = 'risks_types.updated_at';
	const  F_AUTHOR         = 'author';
	const  F_CREATED_AT     = 'created_at';
	const  F_DELETED_AT     = 'deleted_at';
	const  F_ID             = 'id';
	const  F_NAME           = 'name';
	const  F_UPDATED_AT     = 'updated_at';

    protected $table = 'risks_types';

	public static array $jsonable = [
		MRisksTypes::FJ_ID         =>[ MRisksTypes::F_ID         ,null,'string',           ],
		MRisksTypes::FJ_NAME       =>[ MRisksTypes::F_NAME       ,null,'string',           ],
		MRisksTypes::FJ_AUTHOR     =>[ MRisksTypes::F_AUTHOR     ,null,'string',           ],
		MRisksTypes::FJ_CREATED_AT =>[ MRisksTypes::F_CREATED_AT ,'jsonDateTime','string', ],
		MRisksTypes::FJ_UPDATED_AT =>[ MRisksTypes::F_UPDATED_AT ,'jsonDateTime','string', ],
		MRisksTypes::FJ_DELETED_AT =>[ MRisksTypes::F_DELETED_AT ,'jsonDateTime','string', ],
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
         * @return DRisks[]|HasMany
         */
        public function relsRisksByType(){
            return $this->hasMany(DRisks::class, DRisks::F_TYPE, self::F_ID);
        }
            

}


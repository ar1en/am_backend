<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DPeriods;
use  App\Models\DBModels\DBClass;

/**
 * Class MAssuranceMaps
 * Representation for db table ent_assurance_maps.

 * @property  string    id         [1] type:uuid         !NULL PRIMARY
 * @property  string    period     [2] type:uuid         !NULL
 * @property  string    name       [3] type:varchar(255) !NULL
 * @property  string    status     [4] type:uuid         !NULL
 * @property  \DateTime statusDate [5] type:timestamp    !NULL
 * @property  string    author     [6] type:uuid         !NULL
 * @property  \DateTime created_at [7] type:timestamp
 * @property  \DateTime updated_at [8] type:timestamp
 * @property  \DateTime deleted_at [9] type:timestamp
 * @property  DUsers    relAuthor
 * @property  DPeriods  relPeriod
 * @package App\Models\DBModels\Model
 */
class MAssuranceMaps extends DBClass {


	const  FJ_AUTHOR     = 'author';
	const  FJ_CREATED_AT = 'createdAt';
	const  FJ_DELETED_AT = 'deletedAt';
	const  FJ_ID         = 'id';
	const  FJ_NAME       = 'name';
	const  FJ_PERIOD     = 'period';
	const  FJ_STATUS     = 'status';
	const  FJ_STATUSDATE = 'statusDate';
	const  FJ_UPDATED_AT = 'updatedAt';
	const  FR_AUTHOR     = 'relAuthor';
	const  FR_PERIOD     = 'relPeriod';
	const  FT_AUTHOR     = 'assurance_maps.author';
	const  FT_CREATED_AT = 'assurance_maps.created_at';
	const  FT_DELETED_AT = 'assurance_maps.deleted_at';
	const  FT_ID         = 'assurance_maps.id';
	const  FT_NAME       = 'assurance_maps.name';
	const  FT_PERIOD     = 'assurance_maps.period';
	const  FT_STATUS     = 'assurance_maps.status';
	const  FT_STATUSDATE = 'assurance_maps.statusDate';
	const  FT_UPDATED_AT = 'assurance_maps.updated_at';
	const  F_AUTHOR      = 'author';
	const  F_CREATED_AT  = 'created_at';
	const  F_DELETED_AT  = 'deleted_at';
	const  F_ID          = 'id';
	const  F_NAME        = 'name';
	const  F_PERIOD      = 'period';
	const  F_STATUS      = 'status';
	const  F_STATUSDATE  = 'statusDate';
	const  F_UPDATED_AT  = 'updated_at';

    protected $table = 'assurance_maps';

	public static array $jsonable = [
		MAssuranceMaps::FJ_ID         =>[ MAssuranceMaps::F_ID         ,null,'string',           ],
		MAssuranceMaps::FJ_PERIOD     =>[ MAssuranceMaps::F_PERIOD     ,null,'string',           ],
		MAssuranceMaps::FJ_NAME       =>[ MAssuranceMaps::F_NAME       ,null,'string',           ],
		MAssuranceMaps::FJ_STATUS     =>[ MAssuranceMaps::F_STATUS     ,null,'string',           ],
		MAssuranceMaps::FJ_STATUSDATE =>[ MAssuranceMaps::F_STATUSDATE ,'jsonDateTime','string', ],
		MAssuranceMaps::FJ_AUTHOR     =>[ MAssuranceMaps::F_AUTHOR     ,null,'string',           ],
		MAssuranceMaps::FJ_CREATED_AT =>[ MAssuranceMaps::F_CREATED_AT ,'jsonDateTime','string', ],
		MAssuranceMaps::FJ_UPDATED_AT =>[ MAssuranceMaps::F_UPDATED_AT ,'jsonDateTime','string', ],
		MAssuranceMaps::FJ_DELETED_AT =>[ MAssuranceMaps::F_DELETED_AT ,'jsonDateTime','string', ],
	];

		protected $visible = [
			self::F_ID,
			self::F_PERIOD,
			self::F_NAME,
			self::F_STATUS,
			self::F_STATUSDATE,
			self::F_AUTHOR,
			self::F_CREATED_AT,
			self::F_UPDATED_AT,
			self::F_DELETED_AT,
		];

		protected $fillable = [
			 self::F_PERIOD,
			 self::F_NAME,
			 self::F_STATUS,
			 self::F_STATUSDATE,
			 self::F_AUTHOR,
			 self::F_CREATED_AT,
			 self::F_UPDATED_AT,
			 self::F_DELETED_AT,
		];


        /**
         * @return DUsers|BelongsTo
         */
        public function relAuthor(): DUsers{
            return $this->belongsTo(DUsers::class,self::F_AUTHOR, DUsers::F_ID);
        }


        /**
         * @return DPeriods|BelongsTo
         */
        public function relPeriod(){
            return $this->belongsTo(DPeriods::class,self::F_PERIOD, DPeriods::F_ID);
        }




}


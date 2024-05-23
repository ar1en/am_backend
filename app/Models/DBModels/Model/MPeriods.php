<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DAssuranceMaps;
use  Illuminate\Database\Eloquent\Relations\HasMany;
use  App\Models\DBModels\DBClass;

/**
 * Class MPeriods
 * Representation for db table ent_periods.

 * @property  string           id                        [1] type:uuid         !NULL PRIMARY 
 * @property  string           name                      [2] type:varchar(255) !NULL         
 * @property  string(date)     start                     [3] type:date         !NULL         
 * @property  string(date)     end                       [4] type:date         !NULL         
 * @property  string           desk                      [5] type:text         !NULL         
 * @property  string           author                    [6] type:uuid         !NULL         
 * @property  \DateTime        created_at                [7] type:timestamp                  
 * @property  \DateTime        updated_at                [8] type:timestamp                  
 * @property  \DateTime        deleted_at                [9] type:timestamp                  
 * @property  DUsers           relAuthor                                                     
 * @property  DAssuranceMaps[] relsAssuranceMapsByPeriod                                     
 * @package App\Models\DBModels\Model
 */
class MPeriods extends DBClass {


	const  FJ_AUTHOR                   = 'author';
	const  FJ_CREATED_AT               = 'createdAt';
	const  FJ_DELETED_AT               = 'deletedAt';
	const  FJ_DESK                     = 'desk';
	const  FJ_END                      = 'end';
	const  FJ_ID                       = 'id';
	const  FJ_NAME                     = 'name';
	const  FJ_START                    = 'start';
	const  FJ_UPDATED_AT               = 'updatedAt';
	const  FR_ASSURANCE_MAPS_BY_PERIOD = 'relsAssuranceMapsByPeriod';
	const  FR_AUTHOR                   = 'relAuthor';
	const  FT_AUTHOR                   = 'periods.author';
	const  FT_CREATED_AT               = 'periods.created_at';
	const  FT_DELETED_AT               = 'periods.deleted_at';
	const  FT_DESK                     = 'periods.desk';
	const  FT_END                      = 'periods.end';
	const  FT_ID                       = 'periods.id';
	const  FT_NAME                     = 'periods.name';
	const  FT_START                    = 'periods.start';
	const  FT_UPDATED_AT               = 'periods.updated_at';
	const  F_AUTHOR                    = 'author';
	const  F_CREATED_AT                = 'created_at';
	const  F_DELETED_AT                = 'deleted_at';
	const  F_DESK                      = 'desk';
	const  F_END                       = 'end';
	const  F_ID                        = 'id';
	const  F_NAME                      = 'name';
	const  F_START                     = 'start';
	const  F_UPDATED_AT                = 'updated_at';

    protected $table = 'periods';

	public static array $jsonable = [
		MPeriods::FJ_ID         =>[ MPeriods::F_ID         ,null,'string',           ],
		MPeriods::FJ_NAME       =>[ MPeriods::F_NAME       ,null,'string',           ],
		MPeriods::FJ_START      =>[ MPeriods::F_START      ,null,'string(date)',     ],
		MPeriods::FJ_END        =>[ MPeriods::F_END        ,null,'string(date)',     ],
		MPeriods::FJ_DESK       =>[ MPeriods::F_DESK       ,null,'string',           ],
		MPeriods::FJ_AUTHOR     =>[ MPeriods::F_AUTHOR     ,null,'string',           ],
		MPeriods::FJ_CREATED_AT =>[ MPeriods::F_CREATED_AT ,'jsonDateTime','string', ],
		MPeriods::FJ_UPDATED_AT =>[ MPeriods::F_UPDATED_AT ,'jsonDateTime','string', ],
		MPeriods::FJ_DELETED_AT =>[ MPeriods::F_DELETED_AT ,'jsonDateTime','string', ],
	];

		protected $visible = [
			self::F_ID,
			self::F_NAME,
			self::F_START,
			self::F_END,
			self::F_DESK,
			self::F_AUTHOR,
			self::F_CREATED_AT,
			self::F_UPDATED_AT,
			self::F_DELETED_AT,
		]; 

		protected $fillable = [
			 self::F_NAME,
			 self::F_START,
			 self::F_END,
			 self::F_DESK,
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
         * @return DAssuranceMaps[]|HasMany
         */
        public function relsAssuranceMapsByPeriod(){
            return $this->hasMany(DAssuranceMaps::class, DAssuranceMaps::F_PERIOD, self::F_ID);
        }
            

}


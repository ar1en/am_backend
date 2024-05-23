<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DIssuesTypes;
use  App\Models\DBModels\DBClass;

/**
 * Class MIssues
 * Representation for db table ent_issues.

 * @property  string       id         [1] type:uuid         !NULL PRIMARY 
 * @property  string       sasId      [2] type:varchar(255) !NULL         UNIQUE
 * @property  string       type       [3] type:uuid         !NULL         
 * @property  string       desk       [4] type:text                       
 * @property  string       author     [5] type:uuid         !NULL         
 * @property  \DateTime    created_at [6] type:timestamp                  
 * @property  \DateTime    updated_at [7] type:timestamp                  
 * @property  \DateTime    deleted_at [8] type:timestamp                  
 * @property  DUsers       relAuthor                                      
 * @property  DIssuesTypes relType                                        
 * @package App\Models\DBModels\Model
 */
class MIssues extends DBClass {


	const  FJ_AUTHOR     = 'author';
	const  FJ_CREATED_AT = 'createdAt';
	const  FJ_DELETED_AT = 'deletedAt';
	const  FJ_DESK       = 'desk';
	const  FJ_ID         = 'id';
	const  FJ_SASID      = 'sasId';
	const  FJ_TYPE       = 'type';
	const  FJ_UPDATED_AT = 'updatedAt';
	const  FR_AUTHOR     = 'relAuthor';
	const  FR_TYPE       = 'relType';
	const  FT_AUTHOR     = 'issues.author';
	const  FT_CREATED_AT = 'issues.created_at';
	const  FT_DELETED_AT = 'issues.deleted_at';
	const  FT_DESK       = 'issues.desk';
	const  FT_ID         = 'issues.id';
	const  FT_SASID      = 'issues.sasId';
	const  FT_TYPE       = 'issues.type';
	const  FT_UPDATED_AT = 'issues.updated_at';
	const  F_AUTHOR      = 'author';
	const  F_CREATED_AT  = 'created_at';
	const  F_DELETED_AT  = 'deleted_at';
	const  F_DESK        = 'desk';
	const  F_ID          = 'id';
	const  F_SASID       = 'sasId';
	const  F_TYPE        = 'type';
	const  F_UPDATED_AT  = 'updated_at';

    protected $table = 'issues';

	public static array $jsonable = [
		MIssues::FJ_ID         =>[ MIssues::F_ID         ,null,'string',           ],
		MIssues::FJ_SASID      =>[ MIssues::F_SASID      ,null,'string',           ],
		MIssues::FJ_TYPE       =>[ MIssues::F_TYPE       ,null,'string',           ],
		MIssues::FJ_DESK       =>[ MIssues::F_DESK       ,null,'string',           ],
		MIssues::FJ_AUTHOR     =>[ MIssues::F_AUTHOR     ,null,'string',           ],
		MIssues::FJ_CREATED_AT =>[ MIssues::F_CREATED_AT ,'jsonDateTime','string', ],
		MIssues::FJ_UPDATED_AT =>[ MIssues::F_UPDATED_AT ,'jsonDateTime','string', ],
		MIssues::FJ_DELETED_AT =>[ MIssues::F_DELETED_AT ,'jsonDateTime','string', ],
	];

		protected $visible = [
			self::F_ID,
			self::F_SASID,
			self::F_TYPE,
			self::F_DESK,
			self::F_AUTHOR,
			self::F_CREATED_AT,
			self::F_UPDATED_AT,
			self::F_DELETED_AT,
		]; 

		protected $fillable = [
			 self::F_SASID,
			 self::F_TYPE,
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
         * @return DIssuesTypes|BelongsTo
         */
        public function relType(){
            return $this->belongsTo(DIssuesTypes::class,self::F_TYPE, DIssuesTypes::F_ID);
        }
            



}


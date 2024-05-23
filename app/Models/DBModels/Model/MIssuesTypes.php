<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DIssues;
use  Illuminate\Database\Eloquent\Relations\HasMany;
use  App\Models\DBModels\DBClass;

/**
 * Class MIssuesTypes
 * Representation for db table ent_issues_types.

 * @property  string    id               [1] type:uuid      !NULL PRIMARY 
 * @property  string    name             [2] type:text      !NULL         
 * @property  string    author           [3] type:uuid      !NULL         
 * @property  \DateTime created_at       [4] type:timestamp               
 * @property  \DateTime updated_at       [5] type:timestamp               
 * @property  \DateTime deleted_at       [6] type:timestamp               
 * @property  DUsers    relAuthor                                         
 * @property  DIssues[] relsIssuesByType                                  
 * @package App\Models\DBModels\Model
 */
class MIssuesTypes extends DBClass {


	const  FJ_AUTHOR         = 'author';
	const  FJ_CREATED_AT     = 'createdAt';
	const  FJ_DELETED_AT     = 'deletedAt';
	const  FJ_ID             = 'id';
	const  FJ_NAME           = 'name';
	const  FJ_UPDATED_AT     = 'updatedAt';
	const  FR_AUTHOR         = 'relAuthor';
	const  FR_ISSUES_BY_TYPE = 'relsIssuesByType';
	const  FT_AUTHOR         = 'issues_types.author';
	const  FT_CREATED_AT     = 'issues_types.created_at';
	const  FT_DELETED_AT     = 'issues_types.deleted_at';
	const  FT_ID             = 'issues_types.id';
	const  FT_NAME           = 'issues_types.name';
	const  FT_UPDATED_AT     = 'issues_types.updated_at';
	const  F_AUTHOR          = 'author';
	const  F_CREATED_AT      = 'created_at';
	const  F_DELETED_AT      = 'deleted_at';
	const  F_ID              = 'id';
	const  F_NAME            = 'name';
	const  F_UPDATED_AT      = 'updated_at';

    protected $table = 'issues_types';

	public static array $jsonable = [
		MIssuesTypes::FJ_ID         =>[ MIssuesTypes::F_ID         ,null,'string',           ],
		MIssuesTypes::FJ_NAME       =>[ MIssuesTypes::F_NAME       ,null,'string',           ],
		MIssuesTypes::FJ_AUTHOR     =>[ MIssuesTypes::F_AUTHOR     ,null,'string',           ],
		MIssuesTypes::FJ_CREATED_AT =>[ MIssuesTypes::F_CREATED_AT ,'jsonDateTime','string', ],
		MIssuesTypes::FJ_UPDATED_AT =>[ MIssuesTypes::F_UPDATED_AT ,'jsonDateTime','string', ],
		MIssuesTypes::FJ_DELETED_AT =>[ MIssuesTypes::F_DELETED_AT ,'jsonDateTime','string', ],
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
         * @return DIssues[]|HasMany
         */
        public function relsIssuesByType(){
            return $this->hasMany(DIssues::class, DIssues::F_TYPE, self::F_ID);
        }
            

}


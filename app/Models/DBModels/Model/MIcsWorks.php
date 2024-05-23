<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DObjects;
use  App\Models\DBModels\Data\DProcesses;
use  App\Models\DBModels\Data\DIcsWorksTypes;
use  App\Models\DBModels\DBClass;

/**
 * Class MIcsWorks
 * Representation for db table ent_ics_works.

 * @property  string         id           [1]  type:uuid      !NULL PRIMARY 
 * @property  string         type         [2]  type:uuid      !NULL         
 * @property  string         object       [3]  type:uuid      !NULL         
 * @property  string         process      [4]  type:uuid      !NULL         
 * @property  string         desc         [5]  type:text                    
 * @property  string         executor     [6]  type:text                    
 * @property  string(date)   deadline     [7]  type:date                    
 * @property  boolean        icsPerimeter [8]  type:bool                    
 * @property  string         author       [9]  type:uuid      !NULL         
 * @property  \DateTime      created_at   [10] type:timestamp               
 * @property  \DateTime      updated_at   [11] type:timestamp               
 * @property  \DateTime      deleted_at   [12] type:timestamp               
 * @property  DUsers         relAuthor                                      
 * @property  DObjects       relObject                                      
 * @property  DProcesses     relProcess                                     
 * @property  DIcsWorksTypes relType                                        
 * @package App\Models\DBModels\Model
 */
class MIcsWorks extends DBClass {


	const  FJ_AUTHOR       = 'author';
	const  FJ_CREATED_AT   = 'createdAt';
	const  FJ_DEADLINE     = 'deadline';
	const  FJ_DELETED_AT   = 'deletedAt';
	const  FJ_DESC         = 'desc';
	const  FJ_EXECUTOR     = 'executor';
	const  FJ_ICSPERIMETER = 'icsPerimeter';
	const  FJ_ID           = 'id';
	const  FJ_OBJECT       = 'object';
	const  FJ_PROCESS      = 'process';
	const  FJ_TYPE         = 'type';
	const  FJ_UPDATED_AT   = 'updatedAt';
	const  FR_AUTHOR       = 'relAuthor';
	const  FR_OBJECT       = 'relObject';
	const  FR_PROCESS      = 'relProcess';
	const  FR_TYPE         = 'relType';
	const  FT_AUTHOR       = 'ics_works.author';
	const  FT_CREATED_AT   = 'ics_works.created_at';
	const  FT_DEADLINE     = 'ics_works.deadline';
	const  FT_DELETED_AT   = 'ics_works.deleted_at';
	const  FT_DESC         = 'ics_works.desc';
	const  FT_EXECUTOR     = 'ics_works.executor';
	const  FT_ICSPERIMETER = 'ics_works.icsPerimeter';
	const  FT_ID           = 'ics_works.id';
	const  FT_OBJECT       = 'ics_works.object';
	const  FT_PROCESS      = 'ics_works.process';
	const  FT_TYPE         = 'ics_works.type';
	const  FT_UPDATED_AT   = 'ics_works.updated_at';
	const  F_AUTHOR        = 'author';
	const  F_CREATED_AT    = 'created_at';
	const  F_DEADLINE      = 'deadline';
	const  F_DELETED_AT    = 'deleted_at';
	const  F_DESC          = 'desc';
	const  F_EXECUTOR      = 'executor';
	const  F_ICSPERIMETER  = 'icsPerimeter';
	const  F_ID            = 'id';
	const  F_OBJECT        = 'object';
	const  F_PROCESS       = 'process';
	const  F_TYPE          = 'type';
	const  F_UPDATED_AT    = 'updated_at';

    protected $table = 'ics_works';

	public static array $jsonable = [
		MIcsWorks::FJ_ID           =>[ MIcsWorks::F_ID           ,null,'string',           ],
		MIcsWorks::FJ_TYPE         =>[ MIcsWorks::F_TYPE         ,null,'string',           ],
		MIcsWorks::FJ_OBJECT       =>[ MIcsWorks::F_OBJECT       ,null,'string',           ],
		MIcsWorks::FJ_PROCESS      =>[ MIcsWorks::F_PROCESS      ,null,'string',           ],
		MIcsWorks::FJ_DESC         =>[ MIcsWorks::F_DESC         ,null,'string',           ],
		MIcsWorks::FJ_EXECUTOR     =>[ MIcsWorks::F_EXECUTOR     ,null,'string',           ],
		MIcsWorks::FJ_DEADLINE     =>[ MIcsWorks::F_DEADLINE     ,null,'string(date)',     ],
		MIcsWorks::FJ_ICSPERIMETER =>[ MIcsWorks::F_ICSPERIMETER ,null,'boolean',          ],
		MIcsWorks::FJ_AUTHOR       =>[ MIcsWorks::F_AUTHOR       ,null,'string',           ],
		MIcsWorks::FJ_CREATED_AT   =>[ MIcsWorks::F_CREATED_AT   ,'jsonDateTime','string', ],
		MIcsWorks::FJ_UPDATED_AT   =>[ MIcsWorks::F_UPDATED_AT   ,'jsonDateTime','string', ],
		MIcsWorks::FJ_DELETED_AT   =>[ MIcsWorks::F_DELETED_AT   ,'jsonDateTime','string', ],
	];

		protected $visible = [
			self::F_ID,
			self::F_TYPE,
			self::F_OBJECT,
			self::F_PROCESS,
			self::F_DESC,
			self::F_EXECUTOR,
			self::F_DEADLINE,
			self::F_ICSPERIMETER,
			self::F_AUTHOR,
			self::F_CREATED_AT,
			self::F_UPDATED_AT,
			self::F_DELETED_AT,
		]; 

		protected $fillable = [
			 self::F_TYPE,
			 self::F_OBJECT,
			 self::F_PROCESS,
			 self::F_DESC,
			 self::F_EXECUTOR,
			 self::F_DEADLINE,
			 self::F_ICSPERIMETER,
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
            

        /**
         * @return DProcesses|BelongsTo
         */
        public function relProcess(){
            return $this->belongsTo(DProcesses::class,self::F_PROCESS, DProcesses::F_ID);
        }
            

        /**
         * @return DIcsWorksTypes|BelongsTo
         */
        public function relType(){
            return $this->belongsTo(DIcsWorksTypes::class,self::F_TYPE, DIcsWorksTypes::F_ID);
        }
            



}


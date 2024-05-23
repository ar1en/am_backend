<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DObjects;
use  App\Models\DBModels\Data\DProcesses;
use  App\Models\DBModels\DBClass;

/**
 * Class MIcsMatrices
 * Representation for db table ent_ics_matrices.

 * @property  string     id           [1]  type:uuid      !NULL PRIMARY 
 * @property  string     object       [2]  type:uuid      !NULL         
 * @property  string     process      [3]  type:uuid      !NULL         
 * @property  string     desc         [4]  type:text                    
 * @property  boolean    ics          [5]  type:bool      !NULL         
 * @property  boolean    impactOnRisk [6]  type:bool      !NULL         
 * @property  int        testingYear  [7]  type:int4                    
 * @property  int        updatingYear [8]  type:int4                    
 * @property  string     author       [9]  type:uuid      !NULL         
 * @property  \DateTime  created_at   [10] type:timestamp               
 * @property  \DateTime  updated_at   [11] type:timestamp               
 * @property  \DateTime  deleted_at   [12] type:timestamp               
 * @property  DUsers     relAuthor                                      
 * @property  DObjects   relObject                                      
 * @property  DProcesses relProcess                                     
 * @package App\Models\DBModels\Model
 */
class MIcsMatrices extends DBClass {


	const  FJ_AUTHOR       = 'author';
	const  FJ_CREATED_AT   = 'createdAt';
	const  FJ_DELETED_AT   = 'deletedAt';
	const  FJ_DESC         = 'desc';
	const  FJ_ICS          = 'ics';
	const  FJ_ID           = 'id';
	const  FJ_IMPACTONRISK = 'impactOnRisk';
	const  FJ_OBJECT       = 'object';
	const  FJ_PROCESS      = 'process';
	const  FJ_TESTINGYEAR  = 'testingYear';
	const  FJ_UPDATED_AT   = 'updatedAt';
	const  FJ_UPDATINGYEAR = 'updatingYear';
	const  FR_AUTHOR       = 'relAuthor';
	const  FR_OBJECT       = 'relObject';
	const  FR_PROCESS      = 'relProcess';
	const  FT_AUTHOR       = 'ics_matrices.author';
	const  FT_CREATED_AT   = 'ics_matrices.created_at';
	const  FT_DELETED_AT   = 'ics_matrices.deleted_at';
	const  FT_DESC         = 'ics_matrices.desc';
	const  FT_ICS          = 'ics_matrices.ics';
	const  FT_ID           = 'ics_matrices.id';
	const  FT_IMPACTONRISK = 'ics_matrices.impactOnRisk';
	const  FT_OBJECT       = 'ics_matrices.object';
	const  FT_PROCESS      = 'ics_matrices.process';
	const  FT_TESTINGYEAR  = 'ics_matrices.testingYear';
	const  FT_UPDATED_AT   = 'ics_matrices.updated_at';
	const  FT_UPDATINGYEAR = 'ics_matrices.updatingYear';
	const  F_AUTHOR        = 'author';
	const  F_CREATED_AT    = 'created_at';
	const  F_DELETED_AT    = 'deleted_at';
	const  F_DESC          = 'desc';
	const  F_ICS           = 'ics';
	const  F_ID            = 'id';
	const  F_IMPACTONRISK  = 'impactOnRisk';
	const  F_OBJECT        = 'object';
	const  F_PROCESS       = 'process';
	const  F_TESTINGYEAR   = 'testingYear';
	const  F_UPDATED_AT    = 'updated_at';
	const  F_UPDATINGYEAR  = 'updatingYear';

    protected $table = 'ics_matrices';

	public static array $jsonable = [
		MIcsMatrices::FJ_ID           =>[ MIcsMatrices::F_ID           ,null,'string',           ],
		MIcsMatrices::FJ_OBJECT       =>[ MIcsMatrices::F_OBJECT       ,null,'string',           ],
		MIcsMatrices::FJ_PROCESS      =>[ MIcsMatrices::F_PROCESS      ,null,'string',           ],
		MIcsMatrices::FJ_DESC         =>[ MIcsMatrices::F_DESC         ,null,'string',           ],
		MIcsMatrices::FJ_ICS          =>[ MIcsMatrices::F_ICS          ,null,'boolean',          ],
		MIcsMatrices::FJ_IMPACTONRISK =>[ MIcsMatrices::F_IMPACTONRISK ,null,'boolean',          ],
		MIcsMatrices::FJ_TESTINGYEAR  =>[ MIcsMatrices::F_TESTINGYEAR  ,null,'number',           ],
		MIcsMatrices::FJ_UPDATINGYEAR =>[ MIcsMatrices::F_UPDATINGYEAR ,null,'number',           ],
		MIcsMatrices::FJ_AUTHOR       =>[ MIcsMatrices::F_AUTHOR       ,null,'string',           ],
		MIcsMatrices::FJ_CREATED_AT   =>[ MIcsMatrices::F_CREATED_AT   ,'jsonDateTime','string', ],
		MIcsMatrices::FJ_UPDATED_AT   =>[ MIcsMatrices::F_UPDATED_AT   ,'jsonDateTime','string', ],
		MIcsMatrices::FJ_DELETED_AT   =>[ MIcsMatrices::F_DELETED_AT   ,'jsonDateTime','string', ],
	];

		protected $visible = [
			self::F_ID,
			self::F_OBJECT,
			self::F_PROCESS,
			self::F_DESC,
			self::F_ICS,
			self::F_IMPACTONRISK,
			self::F_TESTINGYEAR,
			self::F_UPDATINGYEAR,
			self::F_AUTHOR,
			self::F_CREATED_AT,
			self::F_UPDATED_AT,
			self::F_DELETED_AT,
		]; 

		protected $fillable = [
			 self::F_OBJECT,
			 self::F_PROCESS,
			 self::F_DESC,
			 self::F_ICS,
			 self::F_IMPACTONRISK,
			 self::F_TESTINGYEAR,
			 self::F_UPDATINGYEAR,
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
            



}


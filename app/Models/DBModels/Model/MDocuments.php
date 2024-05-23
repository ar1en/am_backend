<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DDocumentsTypes;
use  App\Models\DBModels\DBClass;

/**
 * Class MDocuments
 * Representation for db table ent_documents.

 * @property  string          id         [1] type:uuid      !NULL PRIMARY 
 * @property  string          name       [2] type:text      !NULL         
 * @property  string          type       [3] type:uuid      !NULL         
 * @property  \DateTime       validFrom  [4] type:timestamp !NULL         
 * @property  \DateTime       validUntil [5] type:timestamp               
 * @property  string          author     [6] type:uuid      !NULL         
 * @property  \DateTime       created_at [7] type:timestamp               
 * @property  \DateTime       updated_at [8] type:timestamp               
 * @property  \DateTime       deleted_at [9] type:timestamp               
 * @property  DUsers          relAuthor                                   
 * @property  DDocumentsTypes relType                                     
 * @package App\Models\DBModels\Model
 */
class MDocuments extends DBClass {


	const  FJ_AUTHOR     = 'author';
	const  FJ_CREATED_AT = 'createdAt';
	const  FJ_DELETED_AT = 'deletedAt';
	const  FJ_ID         = 'id';
	const  FJ_NAME       = 'name';
	const  FJ_TYPE       = 'type';
	const  FJ_UPDATED_AT = 'updatedAt';
	const  FJ_VALIDFROM  = 'validFrom';
	const  FJ_VALIDUNTIL = 'validUntil';
	const  FR_AUTHOR     = 'relAuthor';
	const  FR_TYPE       = 'relType';
	const  FT_AUTHOR     = 'documents.author';
	const  FT_CREATED_AT = 'documents.created_at';
	const  FT_DELETED_AT = 'documents.deleted_at';
	const  FT_ID         = 'documents.id';
	const  FT_NAME       = 'documents.name';
	const  FT_TYPE       = 'documents.type';
	const  FT_UPDATED_AT = 'documents.updated_at';
	const  FT_VALIDFROM  = 'documents.validFrom';
	const  FT_VALIDUNTIL = 'documents.validUntil';
	const  F_AUTHOR      = 'author';
	const  F_CREATED_AT  = 'created_at';
	const  F_DELETED_AT  = 'deleted_at';
	const  F_ID          = 'id';
	const  F_NAME        = 'name';
	const  F_TYPE        = 'type';
	const  F_UPDATED_AT  = 'updated_at';
	const  F_VALIDFROM   = 'validFrom';
	const  F_VALIDUNTIL  = 'validUntil';

    protected $table = 'documents';

	public static array $jsonable = [
		MDocuments::FJ_ID         =>[ MDocuments::F_ID         ,null,'string',           ],
		MDocuments::FJ_NAME       =>[ MDocuments::F_NAME       ,null,'string',           ],
		MDocuments::FJ_TYPE       =>[ MDocuments::F_TYPE       ,null,'string',           ],
		MDocuments::FJ_VALIDFROM  =>[ MDocuments::F_VALIDFROM  ,'jsonDateTime','string', ],
		MDocuments::FJ_VALIDUNTIL =>[ MDocuments::F_VALIDUNTIL ,'jsonDateTime','string', ],
		MDocuments::FJ_AUTHOR     =>[ MDocuments::F_AUTHOR     ,null,'string',           ],
		MDocuments::FJ_CREATED_AT =>[ MDocuments::F_CREATED_AT ,'jsonDateTime','string', ],
		MDocuments::FJ_UPDATED_AT =>[ MDocuments::F_UPDATED_AT ,'jsonDateTime','string', ],
		MDocuments::FJ_DELETED_AT =>[ MDocuments::F_DELETED_AT ,'jsonDateTime','string', ],
	];

		protected $visible = [
			self::F_ID,
			self::F_NAME,
			self::F_TYPE,
			self::F_VALIDFROM,
			self::F_VALIDUNTIL,
			self::F_AUTHOR,
			self::F_CREATED_AT,
			self::F_UPDATED_AT,
			self::F_DELETED_AT,
		]; 

		protected $fillable = [
			 self::F_NAME,
			 self::F_TYPE,
			 self::F_VALIDFROM,
			 self::F_VALIDUNTIL,
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
         * @return DDocumentsTypes|BelongsTo
         */
        public function relType(){
            return $this->belongsTo(DDocumentsTypes::class,self::F_TYPE, DDocumentsTypes::F_ID);
        }
            



}


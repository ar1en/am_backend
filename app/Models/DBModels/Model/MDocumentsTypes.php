<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DDocuments;
use  Illuminate\Database\Eloquent\Relations\HasMany;
use  App\Models\DBModels\DBClass;

/**
 * Class MDocumentsTypes
 * Representation for db table ent_documents_types.

 * @property  string       id                  [1] type:uuid      !NULL PRIMARY 
 * @property  string       name                [2] type:text      !NULL         
 * @property  string       author              [3] type:uuid      !NULL         
 * @property  \DateTime    created_at          [4] type:timestamp               
 * @property  \DateTime    updated_at          [5] type:timestamp               
 * @property  \DateTime    deleted_at          [6] type:timestamp               
 * @property  DUsers       relAuthor                                            
 * @property  DDocuments[] relsDocumentsByType                                  
 * @package App\Models\DBModels\Model
 */
class MDocumentsTypes extends DBClass {


	const  FJ_AUTHOR            = 'author';
	const  FJ_CREATED_AT        = 'createdAt';
	const  FJ_DELETED_AT        = 'deletedAt';
	const  FJ_ID                = 'id';
	const  FJ_NAME              = 'name';
	const  FJ_UPDATED_AT        = 'updatedAt';
	const  FR_AUTHOR            = 'relAuthor';
	const  FR_DOCUMENTS_BY_TYPE = 'relsDocumentsByType';
	const  FT_AUTHOR            = 'documents_types.author';
	const  FT_CREATED_AT        = 'documents_types.created_at';
	const  FT_DELETED_AT        = 'documents_types.deleted_at';
	const  FT_ID                = 'documents_types.id';
	const  FT_NAME              = 'documents_types.name';
	const  FT_UPDATED_AT        = 'documents_types.updated_at';
	const  F_AUTHOR             = 'author';
	const  F_CREATED_AT         = 'created_at';
	const  F_DELETED_AT         = 'deleted_at';
	const  F_ID                 = 'id';
	const  F_NAME               = 'name';
	const  F_UPDATED_AT         = 'updated_at';

    protected $table = 'documents_types';

	public static array $jsonable = [
		MDocumentsTypes::FJ_ID         =>[ MDocumentsTypes::F_ID         ,null,'string',           ],
		MDocumentsTypes::FJ_NAME       =>[ MDocumentsTypes::F_NAME       ,null,'string',           ],
		MDocumentsTypes::FJ_AUTHOR     =>[ MDocumentsTypes::F_AUTHOR     ,null,'string',           ],
		MDocumentsTypes::FJ_CREATED_AT =>[ MDocumentsTypes::F_CREATED_AT ,'jsonDateTime','string', ],
		MDocumentsTypes::FJ_UPDATED_AT =>[ MDocumentsTypes::F_UPDATED_AT ,'jsonDateTime','string', ],
		MDocumentsTypes::FJ_DELETED_AT =>[ MDocumentsTypes::F_DELETED_AT ,'jsonDateTime','string', ],
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
         * @return DDocuments[]|HasMany
         */
        public function relsDocumentsByType(){
            return $this->hasMany(DDocuments::class, DDocuments::F_TYPE, self::F_ID);
        }
            

}


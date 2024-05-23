<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models\DBModels\Model;


use  App\Models\DBModels\Data\DUsers;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use  App\Models\DBModels\Data\DAssuranceMaps;
use  Illuminate\Database\Eloquent\Relations\HasMany;
use  App\Models\DBModels\Data\DAudits;
use  App\Models\DBModels\Data\DAutomatedMonitoring;
use  App\Models\DBModels\Data\DCollegiateBodies;
use  App\Models\DBModels\Data\DCollegiateBodiesTypes;
use  App\Models\DBModels\Data\DDepartments;
use  App\Models\DBModels\Data\DDocuments;
use  App\Models\DBModels\Data\DDocumentsTypes;
use  App\Models\DBModels\Data\DExternalControllersTypes;
use  App\Models\DBModels\Data\DExternalInspections;
use  App\Models\DBModels\Data\DFines;
use  App\Models\DBModels\Data\DIcsMatrices;
use  App\Models\DBModels\Data\DIcsWorks;
use  App\Models\DBModels\Data\DIcsWorksTypes;
use  App\Models\DBModels\Data\DInternalInspections;
use  App\Models\DBModels\Data\DInternalInspectionsTypes;
use  App\Models\DBModels\Data\DIssues;
use  App\Models\DBModels\Data\DIssuesTypes;
use  App\Models\DBModels\Data\DLogins;
use  App\Models\DBModels\Data\DObjects;
use  App\Models\DBModels\Data\DPeriods;
use  App\Models\DBModels\Data\DPermissions;
use  App\Models\DBModels\Data\DProcesses;
use  App\Models\DBModels\Data\DProcessesTypes;
use  App\Models\DBModels\Data\DRisks;
use  App\Models\DBModels\Data\DRisksTypes;
use  App\Models\DBModels\Data\DRoles;
use  App\Models\DBModels\Data\DSystems;
use  App\Models\DBModels\Data\DVacancies;
use  App\Models\DBModels\Data\DVacanciesTypes;
use  App\Models\DBModels\DBClass;
use Illuminate\Support\Str;

/**
 * Class MUsers
 * Representation for db table ent_users.

 * @property  string                      id                                   [1] type:uuid         !NULL PRIMARY
 * @property  string                      name                                 [2] type:varchar(255) !NULL
 * @property  string                      author                               [3] type:uuid         !NULL
 * @property  \DateTime                   created_at                           [4] type:timestamp
 * @property  \DateTime                   updated_at                           [5] type:timestamp
 * @property  \DateTime                   deleted_at                           [6] type:timestamp
 * @property  DUsers                      relAuthor
 * @property  DAssuranceMaps[]            relsAssuranceMapsByAuthor
 * @property  DAudits[]                   relsAuditsByAuthor
 * @property  DAutomatedMonitoring[]      relsAutomatedMonitoringByAuthor
 * @property  DCollegiateBodies[]         relsCollegiateBodiesByAuthor
 * @property  DCollegiateBodiesTypes[]    relsCollegiateBodiesTypesByAuthor
 * @property  DDepartments[]              relsDepartmentsByAuthor
 * @property  DDocuments[]                relsDocumentsByAuthor
 * @property  DDocumentsTypes[]           relsDocumentsTypesByAuthor
 * @property  DExternalControllersTypes[] relsExternalControllersTypesByAuthor
 * @property  DExternalInspections[]      relsExternalInspectionsByAuthor
 * @property  DFines[]                    relsFinesByAuthor
 * @property  DIcsMatrices[]              relsIcsMatricesByAuthor
 * @property  DIcsWorks[]                 relsIcsWorksByAuthor
 * @property  DIcsWorksTypes[]            relsIcsWorksTypesByAuthor
 * @property  DInternalInspections[]      relsInternalInspectionsByAuthor
 * @property  DInternalInspectionsTypes[] relsInternalInspectionsTypesByAuthor
 * @property  DIssues[]                   relsIssuesByAuthor
 * @property  DIssuesTypes[]              relsIssuesTypesByAuthor
 * @property  DLogins[]                   relsLoginsByAuthor
 * @property  DLogins[]                   relsLoginsByUser
 * @property  DObjects[]                  relsObjectsByAuthor
 * @property  DObjects[]                  relsObjectsBySupervisor
 * @property  DPeriods[]                  relsPeriodsByAuthor
 * @property  DPermissions[]              relsPermissionsByAuthor
 * @property  DProcesses[]                relsProcessesByAuthor
 * @property  DProcessesTypes[]           relsProcessesTypesByAuthor
 * @property  DRisks[]                    relsRisksByAuthor
 * @property  DRisksTypes[]               relsRisksTypesByAuthor
 * @property  DRoles[]                    relsRolesByAuthor
 * @property  DSystems[]                  relsSystemsByAuthor
 * @property  DUsers[]                    relsUsersByAuthor
 * @property  DVacancies[]                relsVacanciesByAuthor
 * @property  DVacanciesTypes[]           relsVacanciesTypesByAuthor
 * @package App\Models\DBModels\Model
 */
class MUsers extends DBClass {


	const  FJ_AUTHOR                               = 'author';
	const  FJ_CREATED_AT                           = 'createdAt';
	const  FJ_DELETED_AT                           = 'deletedAt';
	const  FJ_ID                                   = 'id';
	const  FJ_NAME                                 = 'name';
	const  FJ_UPDATED_AT                           = 'updatedAt';
	const  FR_ASSURANCE_MAPS_BY_AUTHOR             = 'relsAssuranceMapsByAuthor';
	const  FR_AUDITS_BY_AUTHOR                     = 'relsAuditsByAuthor';
	const  FR_AUTHOR                               = 'relAuthor';
	const  FR_AUTOMATED_MONITORING_BY_AUTHOR       = 'relsAutomatedMonitoringByAuthor';
	const  FR_COLLEGIATE_BODIES_BY_AUTHOR          = 'relsCollegiateBodiesByAuthor';
	const  FR_COLLEGIATE_BODIES_TYPES_BY_AUTHOR    = 'relsCollegiateBodiesTypesByAuthor';
	const  FR_DEPARTMENTS_BY_AUTHOR                = 'relsDepartmentsByAuthor';
	const  FR_DOCUMENTS_BY_AUTHOR                  = 'relsDocumentsByAuthor';
	const  FR_DOCUMENTS_TYPES_BY_AUTHOR            = 'relsDocumentsTypesByAuthor';
	const  FR_EXTERNAL_CONTROLLERS_TYPES_BY_AUTHOR = 'relsExternalControllersTypesByAuthor';
	const  FR_EXTERNAL_INSPECTIONS_BY_AUTHOR       = 'relsExternalInspectionsByAuthor';
	const  FR_FINES_BY_AUTHOR                      = 'relsFinesByAuthor';
	const  FR_ICS_MATRICES_BY_AUTHOR               = 'relsIcsMatricesByAuthor';
	const  FR_ICS_WORKS_BY_AUTHOR                  = 'relsIcsWorksByAuthor';
	const  FR_ICS_WORKS_TYPES_BY_AUTHOR            = 'relsIcsWorksTypesByAuthor';
	const  FR_INTERNAL_INSPECTIONS_BY_AUTHOR       = 'relsInternalInspectionsByAuthor';
	const  FR_INTERNAL_INSPECTIONS_TYPES_BY_AUTHOR = 'relsInternalInspectionsTypesByAuthor';
	const  FR_ISSUES_BY_AUTHOR                     = 'relsIssuesByAuthor';
	const  FR_ISSUES_TYPES_BY_AUTHOR               = 'relsIssuesTypesByAuthor';
	const  FR_LOGINS_BY_AUTHOR                     = 'relsLoginsByAuthor';
	const  FR_LOGINS_BY_USER                       = 'relsLoginsByUser';
	const  FR_OBJECTS_BY_AUTHOR                    = 'relsObjectsByAuthor';
	const  FR_OBJECTS_BY_SUPERVISOR                = 'relsObjectsBySupervisor';
	const  FR_PERIODS_BY_AUTHOR                    = 'relsPeriodsByAuthor';
	const  FR_PERMISSIONS_BY_AUTHOR                = 'relsPermissionsByAuthor';
	const  FR_PROCESSES_BY_AUTHOR                  = 'relsProcessesByAuthor';
	const  FR_PROCESSES_TYPES_BY_AUTHOR            = 'relsProcessesTypesByAuthor';
	const  FR_RISKS_BY_AUTHOR                      = 'relsRisksByAuthor';
	const  FR_RISKS_TYPES_BY_AUTHOR                = 'relsRisksTypesByAuthor';
	const  FR_ROLES_BY_AUTHOR                      = 'relsRolesByAuthor';
	const  FR_SYSTEMS_BY_AUTHOR                    = 'relsSystemsByAuthor';
	const  FR_USERS_BY_AUTHOR                      = 'relsUsersByAuthor';
	const  FR_VACANCIES_BY_AUTHOR                  = 'relsVacanciesByAuthor';
	const  FR_VACANCIES_TYPES_BY_AUTHOR            = 'relsVacanciesTypesByAuthor';
	const  FT_AUTHOR                               = 'users.author';
	const  FT_CREATED_AT                           = 'users.created_at';
	const  FT_DELETED_AT                           = 'users.deleted_at';
	const  FT_ID                                   = 'users.id';
	const  FT_NAME                                 = 'users.name';
	const  FT_UPDATED_AT                           = 'users.updated_at';
	const  F_AUTHOR                                = 'author';
	const  F_CREATED_AT                            = 'created_at';
	const  F_DELETED_AT                            = 'deleted_at';
	const  F_ID                                    = 'id';
	const  F_NAME                                  = 'name';
	const  F_UPDATED_AT                            = 'updated_at';

    protected $table = 'ent_users';

	public static array $jsonable = [
		MUsers::FJ_ID         =>[ MUsers::F_ID         ,null,'string',           ],
		MUsers::FJ_NAME       =>[ MUsers::F_NAME       ,null,'string',           ],
		MUsers::FJ_AUTHOR     =>[ MUsers::F_AUTHOR     ,null,'string',           ],
		MUsers::FJ_CREATED_AT =>[ MUsers::F_CREATED_AT ,'jsonDateTime','string', ],
		MUsers::FJ_UPDATED_AT =>[ MUsers::F_UPDATED_AT ,'jsonDateTime','string', ],
		MUsers::FJ_DELETED_AT =>[ MUsers::F_DELETED_AT ,'jsonDateTime','string', ],
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
         * @return DAssuranceMaps[]|HasMany
         */
        public function relsAssuranceMapsByAuthor(){
            return $this->hasMany(DAssuranceMaps::class, DAssuranceMaps::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DAudits[]|HasMany
         */
        public function relsAuditsByAuthor(){
            return $this->hasMany(DAudits::class, DAudits::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DAutomatedMonitoring[]|HasMany
         */
        public function relsAutomatedMonitoringByAuthor(){
            return $this->hasMany(DAutomatedMonitoring::class, DAutomatedMonitoring::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DCollegiateBodies[]|HasMany
         */
        public function relsCollegiateBodiesByAuthor(){
            return $this->hasMany(DCollegiateBodies::class, DCollegiateBodies::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DCollegiateBodiesTypes[]|HasMany
         */
        public function relsCollegiateBodiesTypesByAuthor(){
            return $this->hasMany(DCollegiateBodiesTypes::class, DCollegiateBodiesTypes::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DDepartments[]|HasMany
         */
        public function relsDepartmentsByAuthor(){
            return $this->hasMany(DDepartments::class, DDepartments::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DDocuments[]|HasMany
         */
        public function relsDocumentsByAuthor(){
            return $this->hasMany(DDocuments::class, DDocuments::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DDocumentsTypes[]|HasMany
         */
        public function relsDocumentsTypesByAuthor(){
            return $this->hasMany(DDocumentsTypes::class, DDocumentsTypes::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DExternalControllersTypes[]|HasMany
         */
        public function relsExternalControllersTypesByAuthor(){
            return $this->hasMany(DExternalControllersTypes::class, DExternalControllersTypes::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DExternalInspections[]|HasMany
         */
        public function relsExternalInspectionsByAuthor(){
            return $this->hasMany(DExternalInspections::class, DExternalInspections::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DFines[]|HasMany
         */
        public function relsFinesByAuthor(){
            return $this->hasMany(DFines::class, DFines::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DIcsMatrices[]|HasMany
         */
        public function relsIcsMatricesByAuthor(){
            return $this->hasMany(DIcsMatrices::class, DIcsMatrices::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DIcsWorks[]|HasMany
         */
        public function relsIcsWorksByAuthor(){
            return $this->hasMany(DIcsWorks::class, DIcsWorks::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DIcsWorksTypes[]|HasMany
         */
        public function relsIcsWorksTypesByAuthor(){
            return $this->hasMany(DIcsWorksTypes::class, DIcsWorksTypes::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DInternalInspections[]|HasMany
         */
        public function relsInternalInspectionsByAuthor(){
            return $this->hasMany(DInternalInspections::class, DInternalInspections::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DInternalInspectionsTypes[]|HasMany
         */
        public function relsInternalInspectionsTypesByAuthor(){
            return $this->hasMany(DInternalInspectionsTypes::class, DInternalInspectionsTypes::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DIssues[]|HasMany
         */
        public function relsIssuesByAuthor(){
            return $this->hasMany(DIssues::class, DIssues::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DIssuesTypes[]|HasMany
         */
        public function relsIssuesTypesByAuthor(){
            return $this->hasMany(DIssuesTypes::class, DIssuesTypes::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DLogins[]|HasMany
         */
        public function relsLoginsByAuthor(){
            return $this->hasMany(DLogins::class, DLogins::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DLogins[]|HasMany
         */
        public function relsLoginsByUser(){
            return $this->hasMany(DLogins::class, DLogins::F_USER, self::F_ID);
        }


        /**
         * @return DObjects[]|HasMany
         */
        public function relsObjectsByAuthor(){
            return $this->hasMany(DObjects::class, DObjects::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DObjects[]|HasMany
         */
        public function relsObjectsBySupervisor(){
            return $this->hasMany(DObjects::class, DObjects::F_SUPERVISOR, self::F_ID);
        }


        /**
         * @return DPeriods[]|HasMany
         */
        public function relsPeriodsByAuthor(){
            return $this->hasMany(DPeriods::class, DPeriods::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DPermissions[]|HasMany
         */
        public function relsPermissionsByAuthor(){
            return $this->hasMany(DPermissions::class, DPermissions::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DProcesses[]|HasMany
         */
        public function relsProcessesByAuthor(){
            return $this->hasMany(DProcesses::class, DProcesses::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DProcessesTypes[]|HasMany
         */
        public function relsProcessesTypesByAuthor(){
            return $this->hasMany(DProcessesTypes::class, DProcessesTypes::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DRisks[]|HasMany
         */
        public function relsRisksByAuthor(){
            return $this->hasMany(DRisks::class, DRisks::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DRisksTypes[]|HasMany
         */
        public function relsRisksTypesByAuthor(){
            return $this->hasMany(DRisksTypes::class, DRisksTypes::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DRoles[]|HasMany
         */
        public function relsRolesByAuthor(){
            return $this->hasMany(DRoles::class, DRoles::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DSystems[]|HasMany
         */
        public function relsSystemsByAuthor(){
            return $this->hasMany(DSystems::class, DSystems::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DUsers[]|HasMany
         */
        public function relsUsersByAuthor(){
            return $this->hasMany(DUsers::class, DUsers::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DVacancies[]|HasMany
         */
        public function relsVacanciesByAuthor(){
            return $this->hasMany(DVacancies::class, DVacancies::F_AUTHOR, self::F_ID);
        }


        /**
         * @return DVacanciesTypes[]|HasMany
         */
        public function relsVacanciesTypesByAuthor(){
            return $this->hasMany(DVacanciesTypes::class, DVacanciesTypes::F_AUTHOR, self::F_ID);
        }


}


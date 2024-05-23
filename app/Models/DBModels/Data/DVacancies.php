<?php

namespace App\Models\DBModels\Data;

use App\Models\DBModels\Model\MVacancies;
use App\Models\VacancyType;

/**
 * Class DVacancies
 * Data class for db table ent_vacancies.
 * @package App\Models\DBModels\Data
 */
class DVacancies extends MVacancies {

    public function Departments(): object {
        return $this->belongsTo(DDepartments::class, 'department', 'id');
    }

    public function VacanciesTypes(): object {
        return $this->belongsTo(DVacanciesTypes::class, 'type','id');
    }
}

<?php

namespace App\Models\DBModels\Data;

use App\Models\DBModels\Model\MUsers;

/**
 * Class DUsers
 * Data class for db table ent_users.
 * @package App\Models\DBModels\Data
 */
class DUsers extends MUsers {

    public bool $validFromUntil = false;

    public function vacancies(): object {
        return $this->belongsToMany(DVacancies::class, 'rel_user_vacancy', 'user', 'vacancy')->withTimestamps();
    }

    public function roles(): object {
        return $this->belongsToMany(DRoles::class, 'rel_user_role', 'user', 'role')->withTimestamps();
    }
}

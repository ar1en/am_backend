<?php

namespace App\Models\DBModels\Data;

use App\Models\DBModels\Model\MRoles;
use App\Models\DBModels\Data\DUsers;

/**
 * Class DRoles
 * Data class for db table ent_roles.
 * @package App\Models\DBModels\Data
 */
class DRoles extends MRoles {

    public function relUsers(): object {
        return $this->belongsToMany(DUsers::class, 'rel_user_role', 'role', 'user')
            ->withTimestamps()
            ->wherePivotNull('deleted_at');
    }
}

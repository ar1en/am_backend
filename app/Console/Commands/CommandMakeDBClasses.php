<?php

namespace App\Console\Commands;

use App\Models\DBModels\DBClass;

class CommandMakeDBClasses extends \App\Utils\Helpers\DB\Model\Console\CommandMakeDBModels
{

    /**
     * @inheritDoc
     */
    public function getConnectionName(): string
    {
        return env('DB_CONNECTION');
    }

    /**
     * @inheritDoc
     */
    public function getDBModelBaseClassName(): string
    {
        return DBClass::class;
    }
}

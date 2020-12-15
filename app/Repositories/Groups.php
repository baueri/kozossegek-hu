<?php

namespace App\Repositories;

use App\Models\Group;
use Framework\Repository;

class Groups extends Repository
{
    public static function getModelClass(): string
    {
        return Group::class;
    }

    public static function getTable(): string
    {
        return 'groups';
    }
}

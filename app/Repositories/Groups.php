<?php

namespace App\Repositories;

use App\Models\Group;

class Groups extends \Framework\Repository
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

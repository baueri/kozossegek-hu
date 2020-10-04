<?php

namespace App\Repositories;

use App\Models\Group;
use Framework\Database\PaginatedResultSet;
use Framework\Model\Model;
use Framework\Model\ModelCollection;
use Framework\Model\PaginatedModelCollection;
use Framework\Repository;
use Framework\Support\Collection;

class GroupRepository extends Repository
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

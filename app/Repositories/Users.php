<?php

namespace App\Repositories;

use App\Models\User;
use App\QueryBuilders\Relations\HasManyChurchGroupViews;
use Framework\Model\EntityQueryBuilder;

class Users extends EntityQueryBuilder
{
    use HasManyChurchGroupViews;

    protected static function getModelClass(): string
    {
        return User::class;
    }
}

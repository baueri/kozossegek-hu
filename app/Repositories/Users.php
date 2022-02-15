<?php

namespace App\Repositories;

use App\Models\User;
use Framework\Model\EntityQueryBuilder;

class Users extends EntityQueryBuilder
{
    protected static function getModelClass(): string
    {
        return User::class;
    }
}

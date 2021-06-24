<?php

namespace App\Repositories;

use App\Models\Notification;
use Framework\Model\EntityQueryBuilder;

class Notifications extends EntityQueryBuilder
{
    protected static function getModelClass(): string
    {
        return Notification::class;
    }
}

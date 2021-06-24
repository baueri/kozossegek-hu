<?php

namespace App\Repositories;

use App\Auth\Auth;
use App\Models\UserNotification;
use Framework\Model\EntityQueryBuilder;

class UserNotifications extends EntityQueryBuilder
{
    protected static function getModelClass(): string
    {
        return UserNotification::class;
    }

    public function notification()
    {
        return $this->belongsTo(Notifications::class);
    }

    public function forCurrentUser()
    {
        return $this->where('user_id', Auth::user()->id);
    }
}

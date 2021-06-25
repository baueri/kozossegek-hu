<?php

namespace App\EntityQueryBuilders;

use App\Auth\Auth;
use App\Models\Notification;
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

    public function whereNotification(Notification $notification)
    {
        return $this->where('notification_id', $notification->getId());
    }
}

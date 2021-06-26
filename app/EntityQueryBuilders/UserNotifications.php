<?php

namespace App\EntityQueryBuilders;

use App\Auth\Auth;
use App\Models\Notification;
use App\Models\Traits\HasUserColumn;
use App\Models\UserNotification;
use Framework\Model\EntityQueryBuilder;

class UserNotifications extends EntityQueryBuilder
{
    use HasUserColumn;

    protected static function getModelClass(): string
    {
        return UserNotification::class;
    }

    public function notification()
    {
        return $this->belongsTo(Notifications::class, 'last_notification_id');
    }

    public function forCurrentUser()
    {
        return $this->where('user_id', Auth::user()->id);
    }

    /**
     * @param int|Notification $notification
     * @return \App\EntityQueryBuilders\UserNotifications
     */
    public function whereLastNotification($notification)
    {
        return $this->where('last_notification_id', is_numeric($notification) ? $notification : $notification->getId());
    }
}

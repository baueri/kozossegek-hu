<?php

namespace App\EntityQueryBuilders;

use App\Models\Notification;
use Framework\Model\EntityQueryBuilder;

class Notifications extends EntityQueryBuilder
{
    protected static function getModelClass(): string
    {
        return Notification::class;
    }

    public function user_notifications()
    {
        return $this->hasMany(UserNotifications::class, 'last_notification_id');
    }
}

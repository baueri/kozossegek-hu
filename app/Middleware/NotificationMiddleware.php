<?php

namespace App\Middleware;

use App\Auth\Auth;
use App\EntityQueryBuilders\Notifications;
use App\EntityQueryBuilders\UserNotifications;
use App\Models\Notification;
use Framework\Http\View\View;
use Framework\Middleware\Middleware;

class NotificationMiddleware implements Middleware
{
    public function handle()
    {
        $notification = $this->getLastNotification();

        if ($this->userHasUnacceptedNotification($notification)) {
            View::setVariable('user_notification', $notification);
        }
    }

    private function userHasUnacceptedNotification(?Notification $notification): bool
    {
        if (!$notification) {
            return false;
        }

        return !UserNotifications::init()
            ->forCurrentUser()
            ->whereLastNotification($notification)
            ->exists();
    }

    private function getLastNotification(): ?Notification
    {
        if (!($user = Auth::user())) {
            return null;
        }

        $displayFor = is_admin() ? 'ADMIN' : 'PORTAL';
        return Notifications::init()
            ->where('display_for', $displayFor)
            ->where('user_id', '<>', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();
    }
}

<?php

namespace App\Portal\Controllers\Api\V1;

use App\Auth\Auth;
use App\EntityQueryBuilders\UserNotifications;
use Framework\Http\Request;

class NotificationApiController
{
    public function approveNotification(Request $request)
    {
        $user = Auth::user();

        UserNotifications::init()->insert([
            'user_id' => $user->id,
            'notification_id' => $request['id']
        ]);

        return api()->ok();
    }
}

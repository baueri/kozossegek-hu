<?php

namespace App\Admin\Controllers;

use App\Repositories\UserNotifications;

class NotificationController extends AdminController
{
    public function list()
    {
        $userNotifications = UserNotifications::make()->with('notification')->get();
        dd($userNotifications->first()->notification->message);
        return view('admin.notification.list');
    }
}

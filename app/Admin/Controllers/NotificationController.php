<?php

namespace App\Admin\Controllers;

use App\Admin\Notification\NotificationAdminTable;
use App\Repositories\Notifications;
use Framework\Http\Message;

class NotificationController extends AdminController
{
    public function list()
    {
        $table = app(NotificationAdminTable::class);
        return view('admin.notification.list', compact('table'));
    }

    public function create()
    {
        return view('admin.notification.notification_create');
    }

    public function doCreate()
    {
        Notifications::make()->create(
            request()->only('title', 'message', 'display_for')
        );

        Message::success('Sikeres létrehozás');

        return redirect_route('admin.notification.list');
    }

    public function edit()
    {
        $notification = Notifications::make()->find(
            request()->getUriValue('id')
        );

        return view('admin.notification.notification_edit', compact('notification'));
    }
}

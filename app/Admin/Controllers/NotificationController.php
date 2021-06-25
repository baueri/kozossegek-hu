<?php

namespace App\Admin\Controllers;

use App\Admin\Notification\NotificationAdminTable;
use App\Auth\Auth;
use App\EntityQueryBuilders\Notifications;
use Framework\Http\Message;
use Framework\Http\Request;

class NotificationController extends AdminController
{
    public function list()
    {
        $table = app(NotificationAdminTable::class);
        return view('admin.notification.list', compact('table'));
    }

    public function create()
    {
        return view('admin.notification.notification_create', [
            'action' => route('admin.notification.doCreate')
        ]);
    }

    public function doCreate()
    {
        $data = request()->only('title', 'message', 'display_for');
        $data['user_id'] = Auth::user()->id;

        Notifications::init()->create(
            $data
        );

        Message::success('Sikeres létrehozás');

        redirect_route('admin.notification.list');
    }

    public function edit()
    {
        $notification = Notifications::init()->find(
            request()->getUriValue('id')
        );

        $action = route('admin.notification.update', $notification);

        return view('admin.notification.notification_edit', compact('notification', 'action'));
    }

    public function update()
    {
        Notifications::init()->where('id', $this->request['id'])->update(
            request()->only('title', 'message', 'display_for')
        );

        Message::success('Sikeres mentés');

        redirect_route('admin.notification.edit', ['id' => $this->request['id']]);
    }

    public function delete(Request $request)
    {
        Notifications::init()->where('id', $request['id'])->delete();

        Message::danger('Értesítés törölve');

        redirect_route('admin.notification.list');
    }
}

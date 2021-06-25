<?php

namespace App\Admin\Notification;

use App\Admin\Components\AdminTable\AdminTable;
use App\Admin\Components\AdminTable\Editable;
use App\Repositories\Notifications;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Support\StringHelper;

class NotificationAdminTable extends AdminTable implements Editable
{
    protected $columns = [
        'id' => '#',
        'title' => 'Cím',
        'message' => 'Üzenet',
        'user_notifications_count' => 'Elolvasva'
    ];

    public function getMessage($message)
    {
        return StringHelper::more($message, 30);
    }

    /**
     * @return \Framework\Database\PaginatedResultSetInterface
     */
    protected function getData(): PaginatedResultSetInterface
    {
        return Notifications::make()->with('user_notifications')->paginate();
    }

    /**
     * @param $model
     * @return string
     */
    public function getEditUrl($model): string
    {
        return route('admin.notification.edit', $model);
    }

    /**
     * @return string
     */
    public function getEditColumn(): string
    {
        return 'title';
    }
}

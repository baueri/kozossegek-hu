<?php

namespace App\Components\Widget;

use App\Admin\Components\AdminTable\AdminTable;
use App\Admin\Components\AdminTable\Deletable;
use App\Admin\Components\AdminTable\Editable;

class WidgetAdminTable extends AdminTable implements Deletable, Editable
{
    protected $columns = [
        'id' => '#',
        'name' => 'Név',
        'type' => 'Típus',
        'uniqid' => 'Kód',
    ];

    protected function getData(): \Framework\Database\PaginatedResultSetInterface
    {
        $rows =  builder('widgets')->paginate();

        return app()->make(\App\Repositories\Widgets::class)->getInstances($rows);
    }

    public function getDeleteUrl($model): string
    {
        return route('admin.widget.delete', $model);
    }

    public function getEditColumn(): string
    {
        return 'name';
    }

    public function getEditUrl($model): string
    {
        return route('admin.widget.edit', $model);
    }
}

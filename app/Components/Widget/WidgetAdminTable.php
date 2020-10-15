<?php

namespace App\Components\Widget;

use App\Admin\Components\AdminTable;
use App\Admin\Components\Deletable;
use App\Admin\Components\Editable;

class WidgetAdminTable extends AdminTable implements Deletable, Editable
{
    protected $columns = [
        ''
    ];
}

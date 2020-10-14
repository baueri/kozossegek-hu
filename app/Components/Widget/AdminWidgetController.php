<?php

namespace App\Components\Widget;

use App\Admin\Controllers\AdminController;
use App\Repositories\Widgets;

class AdminWidgetController extends AdminController
{
    public function list(Widgets $repo)
    {
        return view('admin.widget.list');
    }
}

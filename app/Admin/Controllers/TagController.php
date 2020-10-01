<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\AdminController;


class TagController extends AdminController
{
    public function tags()
    {
        return view('admin.tag.tags');
    }
}

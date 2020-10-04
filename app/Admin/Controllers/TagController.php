<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\AdminController;


class TagController extends AdminController
{
    public function tags()
    {
        $tags = builder('tags')->select('*')->get();

        return view('admin.tag.tags', compact('tags'));
    }
}

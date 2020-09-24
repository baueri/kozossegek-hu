<?php

namespace App\Admin\Page;

use App\Admin\Controllers\AdminController;
use Framework\Http\Request;

class PageController extends AdminController
{
    public function list(Request $request, PageTable $table)
    {
        return $this->view('admin.page.list', compact('table'));
    }
}
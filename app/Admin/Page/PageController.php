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
    
    public function create()
    {
        $action = route('admin.page.do_create');
        return $this->view('admin.page.create', compact('action'));
    }
    
    public function doCreate(Request $request, \App\Repositories\PageRepository $repository)
    {
        $repository->create($request->only('title', 'slug', 'content'));
        redirect('admin.page.list');
    }
    
    public function edit()
    {
        return 'Oldal szerkesztése form';
    }
    
    public function update()
    {
        return 'Oldal mentése adatbázisba';
    }
    
    public function delete()
    {
        return 'Oldal törlése';
    }
}
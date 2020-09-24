<?php

namespace App\Admin\User;

use App\Admin\Controllers\AdminController;
use Framework\Http\View\Section;

class UserController extends AdminController
{
    public function list()
    {
        Section::add('title', 'Felhasználók');

        return $this->view('admin');
    }
}
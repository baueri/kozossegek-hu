<?php


namespace App\Admin\Controllers;


use Framework\Http\Controller;

class DashboardController extends AdminController
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }
}

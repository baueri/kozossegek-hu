<?php


namespace App\Admin\Controllers;


use App\Admin\Dashboard\Dashboard;
use Framework\Http\Controller;

class DashboardController extends AdminController
{
    public function dashboard(Dashboard $dashboard)
    {
        return view('admin.dashboard');
    }
}

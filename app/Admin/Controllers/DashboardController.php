<?php


namespace App\Admin\Controllers;


use Framework\Http\Controller;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return $this->view('admin.dashboard');
    }
}
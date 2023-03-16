<?php

namespace App\Admin\Controllers;

use App\Admin\Dashboard\Dashboard;
use App\Auth\Auth;
use App\Enums\UserRight;

class DashboardController extends AdminController
{
    public function dashboard(Dashboard $dashboard): Dashboard
    {
        if (!Auth::can(UserRight::FULL_ACCESS)) {
            redirect_route('admin.group.list');
        }
        return $dashboard;
    }
}

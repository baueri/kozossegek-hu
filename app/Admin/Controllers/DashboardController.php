<?php

namespace App\Admin\Controllers;

use App\Admin\Dashboard\Dashboard;
use App\Enums\Permission;

class DashboardController extends AdminController
{
    public function dashboard(Dashboard $dashboard): Dashboard
    {
        if (!auth()->can(Permission::FULL_ACCESS)) {
            redirect_route('admin.group.list');
        }
        return $dashboard;
    }
}

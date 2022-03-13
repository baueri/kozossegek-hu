<?php

namespace App\Admin\Controllers;

use App\Admin\Dashboard\Dashboard;
use App\Auth\AuthUser;
use App\Enums\UserRight;

class DashboardController extends AdminController
{
    public function dashboard(Dashboard $dashboard, AuthUser $user): Dashboard
    {
        if (!$user->can(UserRight::FULL_ACCESS)) {
            redirect_route('admin.group.list');
        }
        return $dashboard;
    }
}

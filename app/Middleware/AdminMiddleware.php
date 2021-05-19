<?php

namespace App\Middleware;

use App\Auth\Auth;
use Framework\Middleware\Middleware;
use Framework\Http\Message;
use Framework\Http\Session;
use Framework\Maintenance;
use Framework\Exception\UnauthorizedException;
use App\Admin\Components\AdminMenu;
use Framework\Http\View\View;

class AdminMiddleware implements Middleware
{
    private Maintenance $maintenance;

    public function __construct(Maintenance $maintenance)
    {
        $this->maintenance = $maintenance;
    }


    /**
     * @throws \Framework\Exception\UnauthorizedException
     */
    final public function handle()
    {
        if (!Auth::loggedIn()) {
            Session::set('last_visited', $_SERVER['REQUEST_URI']);
            Message::danger('Nem vagy belépve!');
            redirect_route('login');
        }

        if (!Auth::user()->isAdmin()) {
            throw new UnauthorizedException();
        }

        $currentRoute = current_route();
        View::setVariable('current_route', $currentRoute);
        $admin_menu = AdminMenu::getMenu();
        View::setVariable('admin_menu', $admin_menu);
        View::setVariable('current_menu_item', $admin_menu->first('active'));

        if ($this->maintenance->isMaintenanceOn() && Auth::loggedIn()) {
            View::setVariable('is_maintenance_on', true);
        } else {
            View::setVariable('is_maintenance_on', false);
        }
    }
}

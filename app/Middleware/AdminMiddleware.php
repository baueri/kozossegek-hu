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
    private $maintenance;

    public function __construct(Maintenance $maintenance)
    {
        $this->maintenance = $maintenance;
    }


    public function handle()
    {
        if (!Auth::loggedIn()) {
            Session::set('last_visited', $_SERVER['REQUEST_URI']);
            Message::danger('Nem vagy belépve!');
            redirect_route('login');
        }
        
        if (!Auth::user()->hasUserGroup('SUPER_ADMIN')) {
            throw new UnauthorizedException();
        }

        $currentRoute = current_route();
        View::addVariable('current_route', $currentRoute);
        $admin_menu = AdminMenu::getMenu();
        View::addVariable('admin_menu', $admin_menu);
        View::addVariable('current_menu_item', $admin_menu->first('active'));

        if ($this->maintenance->isMaintenanceOn() && \App\Auth\Auth::loggedIn()) {
            View::addVariable('is_maintenance_on', true);
        } else {
            View::addVariable('is_maintenance_on', false);
        }
    }
}

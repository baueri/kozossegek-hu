<?php

namespace App\Middleware;

use App\Auth\Auth;
use App\Enums\UserRight;
use Framework\Http\Response;
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

    private AdminMenu $adminMenu;

    public function __construct(Maintenance $maintenance, AdminMenu $adminMenu)
    {
        $this->maintenance = $maintenance;
        $this->adminMenu = $adminMenu;
    }

    /**
     * @throws UnauthorizedException
     */
    final public function handle(): void
    {
        if (!Auth::loggedIn()) {
            if (Response::getHeader('Content-Type') === 'application/json') {
                throw new UnauthorizedException();
            }
            Session::set('last_visited', $_SERVER['REQUEST_URI']);
            Message::danger('Nem vagy belépve!');
            redirect_route('login');
        }
        $user = Auth::user();

        if (!$user->can(UserRight::ACCESS_BACKEND)) {
            throw new UnauthorizedException();
        }

        $currentRoute = current_route();
        View::setVariable('current_route', $currentRoute);

        $admin_menu = $this->adminMenu->getMenu();
        View::setVariable('admin_menu', $admin_menu);
        View::setVariable('current_menu_item', $admin_menu->first('active'));

        View::setVariable('is_maintenance_on', $this->maintenance->isMaintenanceOn() && Auth::loggedIn());
    }
}

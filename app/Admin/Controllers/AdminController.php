<?php


namespace App\Admin\Controllers;


use App\Admin\Components\AdminMenu;
use Framework\Http\Controller;
use Framework\Http\View\View;
use Framework\Http\View\ViewInterface;

class AdminController extends Controller
{
    public function __construct(ViewInterface $view)
    {
        parent::__construct($view);
        $currentRoute = current_route();
        View::addVariable('current_route', $currentRoute);
        $admin_menu = AdminMenu::getMenu();
        View::addVariable('admin_menu', $admin_menu);
        View::addVariable('current_menu_item', $admin_menu->first('active'));

    }
}
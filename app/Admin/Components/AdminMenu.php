<?php


namespace App\Admin\Components;


use Framework\Http\Route\RouteInterface;

class AdminMenu
{

    /**
     * @return string[][]
     */
    public static function getMenu()
    {
        $currentRoute = current_route();
        return array_map(function ($item) use ($currentRoute) {
            return static::parseMenuItem($item, $currentRoute);
        }, config('admin_menu'));
    }

    private static function parseMenuItem(array $menuItem, RouteInterface $currentRoute)
    {
        $menuItem['uri'] = route($menuItem['as']);
        $menuItem['active'] = static::isActive($menuItem, $currentRoute);

        if (isset($menuItem['submenu'])) {
            $menuItem['submenu'] = array_map(function($menuItem) use ($currentRoute) {
                return static::parseMenuItem($menuItem, $currentRoute);
            }, $menuItem['submenu']);
        }

        return $menuItem;
    }

    private static function isActive($menuItem, RouteInterface $currentRoute)
    {
        return $currentRoute->getAs() == $menuItem['as'] || (isset($menuItem['submenu']) && array_filter($menuItem['submenu'], function($subMenuItem) use($currentRoute){
            return static::isActive($subMenuItem, $currentRoute);
        }));
    }

}
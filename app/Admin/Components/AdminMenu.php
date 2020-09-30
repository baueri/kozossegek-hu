<?php


namespace App\Admin\Components;


use Framework\Http\Route\RouteInterface;
use Framework\Support\Collection;

class AdminMenu
{

    /**
     * @return string[][]|Collection
     */
    public static function getMenu()
    {
        $currentRoute = current_route();
        
        return collect(config('admin_menu'))->map(function($item) use($currentRoute){
            return static::parseMenuItem($item, $currentRoute);
        });
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
        if ($currentRoute->getAs() == $menuItem['as']) {
            return true;
        }
        
        if (isset($menuItem['similars']) && in_array($currentRoute->getAs(), $menuItem['similars'])) {
            return true;
        }
        
        
        return (isset($menuItem['submenu']) && array_filter($menuItem['submenu'], function($subMenuItem) use($currentRoute){
            return static::isActive($subMenuItem, $currentRoute);
        }));
    }

}
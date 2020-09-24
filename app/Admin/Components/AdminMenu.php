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
        }, static::getMenuItems());
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

    /**
     * @return string[][]
     */
    private static function getMenuItems()
    {
        return [
            [
                'title' => 'Vezérlőpult',
                'icon' => 'home',
                'as' => 'admin.dashboard'
            ],
            [
                'title' => 'Oldalak',
                'icon' => 'file',
                'as' => 'admin.page.list'
            ],
            [
                'title' => 'Közösségek',
                'icon' => 'church',
                'as' => 'admin.group.list',
                'submenu' => [
                    [
                        'title' => 'Közösségek',
                        'icon' => 'stream',
                        'as' => 'admin.group.list',
                    ],[
                        'title' => 'Új közösség',
                        'icon' => 'plus',
                        'as' => 'admin.group.create',
                    ],
                ]
            ],
            [
                'title' => 'Felhasználók',
                'icon' => 'users',
                'as' => 'admin.users'
            ],
            [
                'title' => 'Gépház',
                'icon' => 'cog',
                'as' => 'admin.settings'
            ],
            [
                'title' => 'Kilépés',
                'link_class' => 'text-danger',
                'icon' => 'sign-out-alt',
                'as' => 'admin.logout'
            ],
        ];
    }
}
<?php

namespace App\Admin\Components;

use App\Auth\AuthUser;
use App\Middleware\CheckRole;
use Framework\Http\Route\RouteInterface;
use Framework\Http\Route\RouterInterface;
use Framework\Support\Collection;
use Framework\Support\StringHelper;

class AdminMenu
{
    public function __construct(
        private RouterInterface $router,
        private ?AuthUser $user
    ) {
    }

    public function getMenu(): Collection
    {
        $currentRoute = current_route();

        return collect(config('admin_menu'))->map(
            fn ($item) => $this->parseMenuItem($item, $currentRoute)
        )->filter();
    }

    private function parseMenuItem(array $menuItem, RouteInterface $currentRoute): array
    {
        $menuItem['uri'] = route($menuItem['as']);
        if (!$this->canAccess($menuItem['uri'])) {
            return [];
        }

        $menuItem['active'] = $this->isActive($menuItem, $currentRoute);
        if (isset($menuItem['submenu'])) {
            $menuItem['submenu'] = collect($menuItem['submenu'])->map(function ($menuItem) use ($currentRoute) {
                return $this->parseMenuItem($menuItem, $currentRoute);
            })->filter()->all();
        }

        return $menuItem;
    }

    private function canAccess(string $uri): bool
    {
        $route = $this->router->find('GET', $uri);
        if (!$route) {
            return true;
        }

        foreach ($route->getMiddleware() as $middleware) {
            if (StringHelper::startsWith($middleware, CheckRole::class)) {
                preg_match('/roles:(.*)/', $middleware, $roles);
                $rolesArray = array_filter(explode(',', $roles[1] ?? ''));
                if ($rolesArray && !$this->user->can($rolesArray)) {
                    return false;
                }
            }
        }
        return true;
    }

    private function isActive($menuItem, RouteInterface $currentRoute): bool
    {
        if ($currentRoute->getAs() == $menuItem['as']) {
            return true;
        }
        if (isset($menuItem['similars']) && in_array($currentRoute->getAs(), $menuItem['similars'])) {
            return true;
        }
        return isset($menuItem['submenu']) &&
            array_filter(
                $menuItem['submenu'],
                fn ($subMenuItem) => $this->isActive($subMenuItem, $currentRoute)
            );
    }
}

<?php

namespace App\Admin\Components\DebugBar;

class FrameworkInfoTab extends DebugBarTab
{
    public function getTitle(): string
    {
        return 'Framework';
    }

    public function icon(): string
    {
        return 'fa fa-tachometer-alt';
    }

    public function render(): string
    {
        $route = current_route();
        $env = htmlspecialchars(app()->getEnvironment());
        $uriMask = htmlspecialchars($route->getUriMask());
        $controller = htmlspecialchars("{$route->getController()}@{$route->getUse()}");
        $alias = htmlspecialchars($route->getAs());
        $middleware = htmlspecialchars(implode(', ', $route->getMiddleware()) ?: '—');

        $envClass = match (strtolower($env)) {
            'production' => 'dbg-badge-danger',
            'staging'    => 'dbg-badge-warning',
            default      => 'dbg-badge-success',
        };

        return <<<HTML
            <dl class="dbg-kv">
                <div><dt>Environment</dt><dd><span class="dbg-badge {$envClass}">{$env}</span></dd></div>
                <div><dt>URI Mask</dt><dd>{$uriMask}</dd></div>
                <div><dt>Controller</dt><dd>{$controller}</dd></div>
                <div><dt>Route alias</dt><dd>{$alias}</dd></div>
                <div><dt>Middleware</dt><dd>{$middleware}</dd></div>
            </dl>
        HTML;
    }
}

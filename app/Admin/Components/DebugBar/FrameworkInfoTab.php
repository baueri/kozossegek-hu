<?php

namespace App\Admin\Components\DebugBar;

class FrameworkInfoTab extends DebugBarTab
{
    public function getTitle(): string
    {
        return 'keretrendszer';
    }

    public function icon(): string
    {
        return 'fa fa-tachometer-alt';
    }

    public function render(): string
    {
        $route = current_route();
        $uriMask = $route->getUriMask();
        $controller = "{$route->getController()}@{$route->getUse()}";
        $alias = $route->getAs();
        $middleware = implode(', ', $route->getMiddleware());
        $env = app()->getEnvironment();

        return <<<EOT
            <code>
                <b>Env:</b> $env<br/>
                <b>URI mask:</b> $uriMask<br/>
                <b>Controller:</b> $controller<br/>
                <b>Alias:</b> $alias<br/>
                <b>Middleware</b> $middleware<br/>
            </code>
        EOT;
    }
}

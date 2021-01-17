<?php


namespace App\Admin\Components\DebugBar;


class FrameworkInfoTab extends DebugBarTab
{

    public function getName()
    {
        return 'keretrendszer';
    }

    public function render()
    {
        $route = current_route();
        $uriMask = $route->getUriMask();
        $controller = "{$route->getController()}@{$route->getUse()}";
        $alias = $route->getAs();
        $middleware = implode(', ', $route->getMiddleware());
        $env = app()->getEnvironment();

        return <<<EOT
            <div class="p-3">
                <b>Környezet:</b> $env<br/>
                <b>URI maszk:</b> $uriMask<br/>
                <b>Controller:</b> $controller<br/>
                <b>Alias:</b> $alias<br/>
                <b>Middleware</b> $middleware<br/>
            </div>
        EOT;
    }
}

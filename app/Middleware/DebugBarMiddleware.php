<?php


namespace App\Middleware;


use Framework\Http\View\View;
use Framework\Middleware\Middleware;

class DebugBarMiddleware implements Middleware
{

    public function handle()
    {
        View::setVariable('show_debugbar', config('app.environment') !== 'production' && config('app.debug'));
    }
}

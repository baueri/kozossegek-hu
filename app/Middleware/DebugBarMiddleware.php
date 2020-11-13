<?php


namespace App\Middleware;


use Framework\Http\View\View;
use Framework\Middleware\Middleware;

class DebugBarMiddleware implements Middleware
{

    public function handle()
    {
//        View::addVariable('show_debugbar', config('app.environment') !== 'production');
        View::addVariable('show_debugbar', false);
    }
}
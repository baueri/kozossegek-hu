<?php

namespace App\Middleware;

use App\EventListeners\LoadViewToDebugBar;
use Framework\Http\View\View;
use Framework\Http\View\ViewLoaded;
use Framework\Middleware\Middleware;

class DebugBarMiddleware implements Middleware
{
    public function handle(): void
    {
        View::setVariable('show_debugbar',  app()->debug());
        ViewLoaded::listen(LoadViewToDebugBar::class);
    }
}

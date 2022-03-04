<?php

namespace App\Middleware;

use Framework\Http\View\View;
use Framework\Middleware\Middleware;

class DebugBarMiddleware implements Middleware
{
    public function handle(): void
    {
        View::setVariable('show_debugbar',  app()->debug());
    }
}

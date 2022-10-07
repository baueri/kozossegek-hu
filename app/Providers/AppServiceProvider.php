<?php

namespace App\Providers;

use Framework\Http\View\View;
use Framework\Middleware\Middleware;

class AppServiceProvider implements Middleware
{
    public function handle(): void
    {
        View::setVariable('is_home', is_home());
        View::setVariable('is_prod', is_prod());
        View::setVariable('header_background', '');
    }
}

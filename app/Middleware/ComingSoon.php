<?php

namespace App\Middleware;

use Framework\Middleware\Middleware;
use App\Auth\Auth;

class ComingSoon implements Middleware
{
    public function handle()
    {
        if (config('app.coming_soon') && !Auth::loggedIn()) {
            print view('portal.coming_soon');
            exit;
        }
    }
}

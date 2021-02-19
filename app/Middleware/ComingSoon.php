<?php

namespace App\Middleware;

use Framework\Http\Session;
use Framework\Middleware\Middleware;
use App\Auth\Auth;

class ComingSoon implements Middleware
{
    public function handle()
    {
        if (array_key_exists('29Y1L', $_REQUEST)) {
            Session::set('test_mode', true);
        }

        if (config('app.coming_soon') && !Auth::loggedIn() && !Session::get('test_mode')) {
            print view('portal.coming_soon');
            exit;
        }
    }
}

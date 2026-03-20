<?php

namespace App\Middleware;

use App\Auth\Auth;
use Framework\Http\Message;
use Framework\Http\Session;
use Framework\Middleware\Before;

class LoggedInMiddleware implements Before
{
    public function before(): void
    {
        if (!Auth::loggedIn()) {
            Session::set('last_visited', $_SERVER['REQUEST_URI']);
            Message::danger('Nem vagy belépve!');
            redirect_route('login');
        }
    }
}

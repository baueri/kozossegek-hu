<?php

namespace App\Middleware;

use App\Auth\Auth;
use Framework\Middleware\Middleware;
use Framework\Http\Message;

class AdminMiddleware implements Middleware
{
    public function handle()
    {

        if (!Auth::loggedIn()) {
            Message::danger('Nem vagy belépve!');
            redirect('login');
        }
    }
}
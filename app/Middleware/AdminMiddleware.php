<?php

namespace App\Middleware;

use App\Auth\Auth;
use App\Enums\UserRight;
use Framework\Http\Response;
use Framework\Middleware\Middleware;
use Framework\Http\Message;
use Framework\Http\Session;
use Framework\Exception\UnauthorizedException;

class AdminMiddleware implements Middleware
{
    /**
     * @throws UnauthorizedException
     */
    final public function handle(): void
    {
        $user = Auth::user();

        if (!$user) {
            if (Response::getHeader('Content-Type') === 'application/json') {
                throw new UnauthorizedException();
            }
            Session::set('last_visited', $_SERVER['REQUEST_URI']);
            Message::danger('Nem vagy belépve!');
            redirect_route('login');
        }

        if (!$user->can(UserRight::ACCESS_BACKEND)) {
            throw new UnauthorizedException();
        }
    }
}

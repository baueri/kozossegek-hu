<?php

namespace Framework\Auth;

use Framework\Exception\UnauthorizedException;

class BaseAuth
{
    /**
     * @throws UnauthorizedException
     */
    public function authenticate(string $realm, ?string $user, ?string $password): void
    {
        $validated = $user == $_SERVER['PHP_AUTH_USER'] && $password === $_SERVER['PHP_AUTH_PW'];

        if (!$validated) {
            header('WWW-Authenticate: Basic realm="' . $realm . '"');
            header('HTTP/1.0 401 Unauthorized');
            throw new UnauthorizedException('Not authorized.');
        }
    }
}

<?php

namespace Framework\Auth;

use Framework\Exception\UnauthorizedException;

class BaseAuth
{

    /**
     * @param $realm
     * @param $user
     * @param $password
     * @return bool
     * @throws UnauthorizedException
     */
    public function authenticate($realm, $user, $password)
    {
        $validated = $user == $_SERVER['PHP_AUTH_USER'] && $password === $_SERVER['PHP_AUTH_PW'];

        if (!$validated) {
            header('WWW-Authenticate: Basic realm="' . $realm . '"');
            header('HTTP/1.0 401 Unauthorized');
            throw new UnauthorizedException('Not authorized.');
        }

        return true;
    }
}

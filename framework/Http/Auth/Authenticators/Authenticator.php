<?php


namespace Framework\Http\Auth\Authenticators;


use App\Models\User;

interface Authenticator
{
    /**
     * @param mixed $credentials
     * @return User|null
     */
    public function authenticate($credentials);
}
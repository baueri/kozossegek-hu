<?php


namespace Framework\Middleware;


use Framework\Http\Auth\Auth;

class AuthMiddleware implements Middleware
{

    public function handle()
    {
        Auth::authenticate();
    }
}
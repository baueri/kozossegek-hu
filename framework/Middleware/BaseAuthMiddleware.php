<?php


namespace Framework\Middleware;


use Framework\Application;
use Framework\Auth\BaseAuth;

class BaseAuthMiddleware implements Middleware
{
    /**
     * @var Application
     */
    private $app;
    /**
     * @var BaseAuth
     */
    private $auth;

    /**
     * BaseAuthMiddleware constructor.
     * @param Application $app
     * @param BaseAuth $auth
     */
    public function __construct(Application $app, BaseAuth $auth)
    {
        $this->app = $app;
        $this->auth = $auth;
    }

    public function handle()
    {
        if (!app()->config('app.base_auth')) {
            return true;
        }

        $this->auth->authenticate('kozossegek.hu Basic Authentication', app()->config('app.base_auth.user'), app()->config('app.base_auth.password'));
    }
}
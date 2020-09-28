<?php

namespace App\Middleware;

use Framework\Auth\BaseAuth;
use Framework\Middleware\Middleware;

class AdminMiddleware implements Middleware
{

    /**
     * @var BaseAuth
     */
    private $auth;

    /**
     * AdminMiddleware constructor.
     * @param BaseAuth $auth
     */
    public function __construct(\Framework\Http\Request $request,   BaseAuth $auth)
    {
        $this->auth = $auth;
    }

    public function handle()
    {
        $this->auth->authenticate('admin', 'admin', 'jelszo');
    }
}
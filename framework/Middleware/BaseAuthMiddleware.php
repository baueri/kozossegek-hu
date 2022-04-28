<?php

namespace Framework\Middleware;

use Framework\Auth\BaseAuth;

class BaseAuthMiddleware implements Middleware
{
    private BaseAuth $auth;

    public function __construct(BaseAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @throws \Framework\Exception\UnauthorizedException
     */
    public function handle(): void
    {
        if (!config('app.base_auth')) {
            return;
        }

        $this->auth->authenticate(
            'kozossegek.hu Basic Authentication',
            config('app.base_auth.user'),
            config('app.base_auth.password')
        );
    }
}

<?php

namespace Framework\Middleware;

use App\Auth\Authenticate;

class AuthMiddleware implements Middleware
{
    /**
     * @var Authenticate
     */
    private Authenticate $service;

    /**
     * @param Authenticate $service
     */
    public function __construct(Authenticate $service)
    {
        $this->service = $service;
    }

    public function handle()
    {
        $this->service->authenticateBySession();
    }
}

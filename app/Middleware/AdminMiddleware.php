<?php

namespace App\Middleware;

use App\Auth\Auth;
use App\Auth\Authenticate;
use Framework\Middleware\Middleware;

class AdminMiddleware implements Middleware
{
    /**
     * @var Authenticate
     */
    private $service;

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

        if (!Auth::loggedIn()) {
            redirect('login');
        }
    }
}
<?php


namespace Framework\Middleware;


use App\Auth\Authenticate;
use Framework\Http\View\View;

class AuthMiddleware implements Middleware
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
    }
}
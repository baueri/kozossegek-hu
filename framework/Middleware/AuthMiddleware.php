<?php

namespace Framework\Middleware;

use App\Auth\Authenticate;
use App\Services\MileStone;

class AuthMiddleware implements Before
{
    private Authenticate $service;

    public function __construct(Authenticate $service)
    {
        $this->service = $service;
    }

    public function before(): void
    {
        MileStone::measure('auth', 'Authenticate');
        $this->service->authenticateBySession();
        MileStone::endMeasure('auth');
    }
}

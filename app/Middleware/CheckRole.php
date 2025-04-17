<?php

namespace App\Middleware;

use App\Auth\Auth;
use Framework\Exception\UnauthorizedException;
use Framework\Middleware\Before;

class CheckRole implements Before
{
    private array $roles;

    public function __construct(string $roles)
    {
        $this->roles = explode(',', $roles);
    }

    /**
     * @throws UnauthorizedException
     */
    public function before(): void
    {
        if ($this->roles && (!Auth::loggedIn() || !Auth::user()->can($this->roles))) {
            throw new UnauthorizedException();
        }
    }
}

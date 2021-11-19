<?php

namespace App\Middleware;

use App\Auth\Auth;
use Framework\Exception\UnauthorizedException;
use Framework\Middleware\Middleware;

class CheckRole implements Middleware
{
    private array $roles;

    public function __construct(string $roles)
    {
        $this->roles = explode(',', $roles);
    }

    /**
     * @throws UnauthorizedException
     */
    public function handle(): void
    {
        if ($this->roles && (!Auth::loggedIn() || !Auth::user()->can($this->roles))) {
            throw new UnauthorizedException();
        }
    }
}

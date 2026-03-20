<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Auth\Auth;
use App\Enums\Permission;
use Framework\Exception\UnauthorizedException;
use Framework\Middleware\Before;

class CheckRole implements Before
{
    private Permission $permission;

    public function __construct(string $role)
    {
        $this->permission = Permission::from($role);
    }

    /**
     * @throws UnauthorizedException
     */
    public function before(): void
    {
        if (!Auth::loggedIn() || !Auth::user()->can($this->permission)) {
            throw new UnauthorizedException();
        }
    }
}

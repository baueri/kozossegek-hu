<?php

declare(strict_types=1);

namespace Framework\Middleware;

use Framework\Http\Request;

readonly class ReplayAttackProtection implements Before, After
{
    public function __construct(
        protected Request $request
    ) {
    }

    public function before(): void
    {

    }

    public function after(): void
    {
    }
}

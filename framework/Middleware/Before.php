<?php

declare(strict_types=1);

namespace Framework\Middleware;

interface Before
{
    public function before(): void;
}

<?php

declare(strict_types=1);

namespace Framework\Middleware;

interface After
{
    public function after(): void;
}

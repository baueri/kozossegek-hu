<?php

namespace Framework\Middleware;

interface Middleware
{
    public function handle(): void;
}

<?php

namespace Framework\Http;

use Framework\Middleware\Middleware;

class Controller
{
    protected array $middleware = [];

    public function __construct(protected Request $request)
    {
        foreach ($this->middleware as $middleware) {
            $this->middleware($middleware);
        }
    }

    public function middleware(string|Middleware $middleware): void
    {
        if ($middleware instanceof Middleware) {
            $middleware->handle();
            return;
        }

        app()->make($middleware)->handle();
    }
}

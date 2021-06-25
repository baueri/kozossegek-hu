<?php

namespace Framework\Http;

use Framework\Middleware\Middleware;

class Controller
{
    protected array $middleware = [];

    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        foreach ($this->middleware as $middleware) {
            $this->middleware($middleware);
        }

        $this->boot();
    }

    public function boot()
    {

    }

    /**
     * @param string|Middleware $middleware
     * @psalm-template T of Middleware
     * @psalm-param class-string<T>
     */
    public function middleware(string $middleware): void
    {
        if (is_string($middleware)) {
            $middleware = (array) $middleware;
        }

        foreach ($middleware as $handler) {
            app($handler)->handle();
        }
    }
}

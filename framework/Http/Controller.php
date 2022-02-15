<?php

namespace Framework\Http;

class Controller
{
    protected array $middleware = [];

    public function __construct(protected Request $request)
    {
        foreach ($this->middleware as $middleware) {
            $this->middleware($middleware);
        }
    }

    public function middleware(string $middleware): void
    {
        app()->make($middleware)->handle();
    }
}

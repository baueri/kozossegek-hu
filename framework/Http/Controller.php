<?php

namespace Framework\Http;

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
    }

    public function middleware(string $middleware): void
    {
        app()->make($middleware)->handle();
    }
}

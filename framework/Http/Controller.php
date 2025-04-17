<?php

namespace Framework\Http;

use Framework\Middleware\Before;
use Framework\Traits\BootsClass;

class Controller
{
    use BootsClass;

    protected array $middleware = [];

    public function __construct(protected Request $request)
    {
        $this->bootClass();
        foreach ($this->middleware as $middleware) {
            $this->middleware($middleware);
        }
    }

    /**
     * @template T of Before
     * @param class-string<T>|Before $middleware
     * @return void
     */
    public function middleware(string|Before $middleware): void
    {
        if ($middleware instanceof Before) {
            $middleware->before();
            return;
        }

        app()->make($middleware)->before();
    }
}

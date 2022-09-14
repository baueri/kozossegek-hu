<?php

namespace Framework\Middleware;

use Framework\Http\Route\RouterInterface;

class TranslationRoute implements Middleware
{
    public function __construct(private readonly RouterInterface $router)
    {
    }

    public function handle(): void
    {
        $this->router->addGlobalArg('lang', getLang());
    }
}

<?php

namespace Framework\Middleware;

use Framework\Http\Request;
use Framework\Http\Route\RouterInterface;

class TranslationRoute implements Middleware
{
    private Request $request;

    private RouterInterface $router;

    public function __construct(Request $request, RouterInterface $router)
    {
        $this->request = $request;
        $this->router = $router;
    }

    public function handle(): void
    {
//        $this->router->addGlobalArg('lang', getLang());
    }
}

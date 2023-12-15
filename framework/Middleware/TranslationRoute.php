<?php

declare(strict_types=1);

namespace Framework\Middleware;

use Framework\Dispatcher\HttpDispatcher;

class TranslationRoute implements Middleware
{
    public function __construct(
        private readonly HttpDispatcher $dispatcher,
    ) {
    }

    public function handle(): void
    {
        request()->getUriValues();
//        dd(request()->values());
//        dd();
//        if ($this->dispatcher->getCurrentRoute()) {
//
//        }
//        $this->router->addGlobalArg('lang', getLang());
    }
}

<?php

namespace Framework\Middleware;

use Framework\Application;
use Framework\Http\Route\RouterInterface;

class TranslationRoute implements Middleware
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly Application $app
    ) {
    }

    public function handle(): void
    {
        if ($lang = request()->getUriValue('lang')) {
            $this->app->setLocale($lang);
            $this->router->addGlobalArg('lang', $lang);
        }
    }
}

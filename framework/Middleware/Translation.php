<?php

namespace Framework\Middleware;

use App\EventListeners\MissingTranslationListener;
use Framework\Application;
use Framework\Http\Route\RouterInterface;
use Framework\Translation\TranslationMissing;

class Translation implements Middleware
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly Application $app
    ) {
    }

    public function handle(): void
    {
        TranslationMissing::listen(MissingTranslationListener::class);

        if ($lang = request()->getUriValue('lang')) {
            $this->app->setLocale($lang);
            $this->router->addGlobalArg('lang', $lang);
        }
    }
}

<?php

declare(strict_types=1);

namespace Framework\Middleware;

use App\EventListeners\MissingTranslationListener;
use Framework\Application;
use Framework\Http\Route\RouterInterface;
use Framework\Translation\TranslationMissing;

readonly class Translation implements Before
{
    public function __construct(
        private RouterInterface $router,
        private Application     $app
    ) {
    }

    public function before(): void
    {
        TranslationMissing::listen(MissingTranslationListener::class);

        $lang = request()->getUriValue('lang');

        if ($lang) {
            $this->app->setLocale($lang);
            $this->router->addGlobalArg('lang', $lang);
        }
    }
}

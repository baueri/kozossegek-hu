<?php

namespace App;

use App\Middleware\DebugBarMiddleware;
use App\Middleware\ListenViewLoading;
use App\Providers\AppServiceProvider;
use Framework\Middleware\AuthMiddleware;
use Framework\Middleware\BaseAuthMiddleware;
use Framework\Middleware\TranslationRoute;

class HttpKernel extends \Framework\Http\HttpKernel
{
    protected array $middleware = [
        BaseAuthMiddleware::class,
        DebugBarMiddleware::class,
        ListenViewLoading::class,
        TranslationRoute::class,
        AuthMiddleware::class,
        AppServiceProvider::class
    ];

    public function handleMaintenance(): void
    {
        echo view('maintenance');
    }

    public function handleError($exception): void
    {
        if ($exception->getCode() != '404' && !env('DEBUG')) {
            report($exception);
        }

        parent::handleError($exception);
    }
}

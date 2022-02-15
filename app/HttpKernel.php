<?php

namespace App;

use App\Middleware\DebugBarMiddleware;
use App\Middleware\ListenViewLoading;
use Framework\Middleware\BaseAuthMiddleware;
use Framework\Middleware\TranslationRoute;
use Framework\Middleware\AuthMiddleware;
use App\Components\Widget\AppServiceProvider;
use Framework\Middleware\CheckMaintenance;

class HttpKernel extends \Framework\Http\HttpKernel
{
    protected array $middleware = [
        BaseAuthMiddleware::class,
        DebugBarMiddleware::class,
        ListenViewLoading::class,
        TranslationRoute::class,
        CheckMaintenance::class,
        AuthMiddleware::class,
        AppServiceProvider::class,
    ];

    public function handleMaintenance()
    {
        echo view('maintenance');
    }

    public function handleError($exception)
    {
        if ($exception->getCode() != '404' && !_env('DEBUG')) {
            report($exception);
        }

        parent::handleError($exception);
    }
}

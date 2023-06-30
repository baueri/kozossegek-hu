<?php

namespace App;

use App\Middleware\AdminMiddleware;
use App\Middleware\DebugBarMiddleware;
use App\Middleware\ListenViewLoading;
use App\Middleware\LoggedInMiddleware;
use App\Providers\AppServiceProvider;
use Framework\Http\Exception\HttpException;
use Framework\Middleware\AuthMiddleware;
use Framework\Middleware\BaseAuthMiddleware;
use Framework\Middleware\CheckMaintenance;
use Framework\Middleware\JsonApi;
use Framework\Middleware\TranslationRoute;
use Framework\Middleware\VerifyCsrfToken;

class HttpKernel extends \Framework\Http\HttpKernel
{
    protected array $middleware = [
        BaseAuthMiddleware::class,
        DebugBarMiddleware::class,
        ListenViewLoading::class,
        TranslationRoute::class,
        CheckMaintenance::class,
        AuthMiddleware::class,
        AppServiceProvider::class
    ];

    protected array $dontReport = [
        HttpException::class
    ];

    public function handleMaintenance()
    {
        echo view('maintenance');
    }

    public function handleError($error)
    {
        if (!in_array($error::class, $this->dontReport) && !env('DEBUG')) {
            report($error);
        }

        parent::handleError($error);
    }
}

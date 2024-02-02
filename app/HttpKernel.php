<?php

namespace App;

use App\Exception\HoneypotException;
use App\Middleware\DebugBarMiddleware;
use App\Middleware\ListenViewLoading;
use App\Providers\AppServiceProvider;
use Framework\Http\Exception\TokenMismatchException;
use Framework\Middleware\AuthMiddleware;
use Framework\Middleware\BaseAuthMiddleware;
use Framework\Middleware\CheckMaintenance;
use Framework\Middleware\Translation;

class HttpKernel extends \Framework\Http\HttpKernel
{
    protected array $middleware = [
        BaseAuthMiddleware::class,
        DebugBarMiddleware::class,
        ListenViewLoading::class,
        Translation::class,
        CheckMaintenance::class,
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
            if ($exception instanceof TokenMismatchException) {
                log_event('csrf_fail', ['request' => request()->all()]);
            } elseif ($exception instanceof HoneypotException) {
                log_event('honeypot_fail', ['request' => request()->all()]);
            } else {
                report($exception);
            }
        }

        parent::handleError($exception);
    }
}

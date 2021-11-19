<?php

namespace App;

use App\Middleware\DebugBarMiddleware;
use App\Middleware\ListenViewLoading;
use App\Providers\AdminServiceProvider;
use Framework\Middleware\BaseAuthMiddleware;
use Framework\Middleware\TranslationRoute;
use Framework\Middleware\AuthMiddleware;
use Framework\Mail\Mailer;
use App\Mailable\CriticalErrorEmail;
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
        AdminServiceProvider::class
    ];

    public function handleMaintenance()
    {
        echo view('maintenance');
    }

    public function handleError($error)
    {
        if ($error->getCode() != '404' && !_env('DEBUG')) {
            $mail = (new CriticalErrorEmail($error));

            $mail->build();

            $mailer = new Mailer();

            $mailer->to(config('app.error_email'))->send($mail);
        }

        parent::handleError($error);
    }
}

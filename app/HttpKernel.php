<?php


namespace App;


use App\Admin\Components\DebugBar\ErrorTab;
use App\Middleware\DebugBarMiddleware;
use Framework\Middleware\BaseAuthMiddleware;
use Framework\Middleware\TranslationRoute;
use Framework\Middleware\AuthMiddleware;
use Framework\Mail\Mailer;
use App\Mailable\CriticalErrorEmail;
use App\Components\Widget\WidgetServiceProvider;
use Framework\Middleware\CheckMaintenance;

class HttpKernel extends \Framework\Http\HttpKernel
{
    protected $middleware = [
        BaseAuthMiddleware::class,
        DebugBarMiddleware::class,
        TranslationRoute::class,
        CheckMaintenance::class,
        AuthMiddleware::class,
        WidgetServiceProvider::class
    ];

    public function handleMaintenance()
    {
        echo view('maintenance');
    }

    public function handleError($error)
    {
        if($error->getCode() != '404' && is_prod()) {

            $mail = (new CriticalErrorEmail($error));

            $mail->build();

            $mailer = new Mailer();

            $mailer->to(config('app.error_email'))->send($mail);
        }

        $content = "<pre style='white-space:pre-line'><h3>Unexpected error (" . get_class($error) . ")</h3> {$error->getMessage()} in <b>{$error->getFile()}</b> on line <b>{$error->getLine()}</b> \n\n {$error->getTraceAsString()}</pre>";
        debugbar()->tab(ErrorTab::class)->pushError($content);

        parent::handleError($error);
    }
}

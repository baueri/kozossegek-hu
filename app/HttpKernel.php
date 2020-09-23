<?php


namespace App;


use Framework\Middleware\BaseAuthMiddleware;
use Framework\Middleware\TranslationRoute;

class HttpKernel extends \Framework\Http\HttpKernel
{
    protected $middleware = [
        BaseAuthMiddleware::class,
        TranslationRoute::class
    ];

    public function handleMaintenance()
    {
        echo view('maintenance');
    }
}
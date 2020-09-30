<?php


namespace App;


use Framework\Middleware\BaseAuthMiddleware;
use Framework\Middleware\TranslationRoute;
use Framework\Middleware\AuthMiddleware;

class HttpKernel extends \Framework\Http\HttpKernel
{
    protected $middleware = [
        BaseAuthMiddleware::class,
        TranslationRoute::class,
        AuthMiddleware::class
    ];

    public function handleMaintenance()
    {
        echo view('maintenance');
    }
}
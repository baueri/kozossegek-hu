<?php


namespace App;


use Framework\Middleware\TranslationRoute;

class HttpKernel extends \Framework\Http\HttpKernel
{
    protected $middleware = [
        TranslationRoute::class
    ];

}
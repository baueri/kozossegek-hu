<?php


namespace Framework\Http;


use Framework\Middleware\Middleware;

class HttpKernel
{
    /**
     * @var string[]|Middleware[]
     */
    protected $middleware = [];

    public function getMiddleware()
    {
        return $this->middleware;
    }

    public function handleMaintenance()
    {
        echo '<h1>Website under maintenance</h1>';
    }
}
<?php


namespace Framework\Http\Route;


use Framework\Middleware\Middleware;

interface RouteInterface
{
    public function __construct(string $method, string $uriMask, string $as, string $controller, string $use, array $middleware, string $view);

    public function getRequestMethod();

    public function requestMethodIs($method);

    public function getUriMask();

    public function getAs();

    public function getController();

    public function getUse();

    public function getMiddleware(): array;

    public function getView();
}

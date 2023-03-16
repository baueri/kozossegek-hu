<?php

declare(strict_types=1);

namespace Framework\Http\Route;

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

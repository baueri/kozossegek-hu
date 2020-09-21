<?php


namespace Framework\Http\Route;


interface RouteInterface
{
    public function __construct($method, $uriMask, $as, $controller, $use, $middleware, $view);

    public function getRequestMethod();

    public function requestMethodIs($method);

    public function getUriMask();

    public function getAs();

    public function getController();

    public function getUse();

    public function getMiddleware();

    public function getView();
}
<?php

namespace Framework\Middleware;

final class MiddlewareResolver
{
    public function resolve(string $middleware): Middleware
    {
        $parts = explode('@', $middleware);
        $class = $parts[0];
        $params = $parts[1] ?? null;
        $args = [];
        if ($params) {
            foreach (explode(';', $params) as $param) {
                [$paramName, $paramValue] = explode(':', $param);
                $args[$paramName] = $paramValue;
            }
        }
        return app()->make($class, $args);
    }
}

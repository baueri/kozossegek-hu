<?php

namespace Framework\Middleware;

use Closure;

/**
 * @template T of Middleware
 */
final class MiddlewareResolver
{
    public function resolve(string|Closure $middleware): void
    {
        if ($middleware instanceof Closure) {
            app()->resolve($middleware)(...app()->getDependencies($middleware));
            return;
        }
        $parts = explode('@', $middleware);
        /** @var class-string<T> $class */
        $class = $parts[0];
        $params = $parts[1] ?? null;
        $args = [];
        if ($params) {
            foreach (explode(';', $params) as $param) {
                [$paramName, $paramValue] = explode(':', $param);
                $args[$paramName] = $paramValue;
            }
        }
        app()->make($class, $args)->handle();
    }
}

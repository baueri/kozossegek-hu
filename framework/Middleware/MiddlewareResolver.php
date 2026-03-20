<?php

namespace Framework\Middleware;

use Closure;

/**
 * @template T of Before
 */
final class MiddlewareResolver
{
    public function resolve(string|Closure $middleware): Closure|Before|After
    {
        if ($middleware instanceof Closure) {
            return new readonly class ($middleware) implements Before {
                public function __construct(
                    private Closure $middleware
                ) {
                }

                public function before(): void
                {
                    $callable = $this->middleware;
                    $callable(...app()->getDependencies($callable));
                }
            };
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

        return app()->make($class, $args);
    }
}

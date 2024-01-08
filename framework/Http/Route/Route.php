<?php

namespace Framework\Http\Route;

use Framework\Support\StringHelper;

class Route implements RouteInterface
{
    protected string $method;

    protected string $uriMask;

    protected string $as;

    protected string $controller;

    protected string $use;

    protected string $view;

    protected array $middleware = [];

    public function __construct(string $method, string $uriMask, string $as, string $controller, string $use, array $middleware, string $view)
    {
        $this->method = $method;
        $this->uriMask = $uriMask;
        $this->as = $as;
        $this->controller = trim($controller, '\\');
        $this->use = $use;
        $this->middleware = array_map(fn ($m) => config('app.named_middleware')[$m] ?? $m, $middleware);
        $this->view = $view;
    }

    /**
     * @return string
     */
    public function getRequestMethod(): string
    {
        return $this->method;
    }

    public function getUriMask(): string
    {
        return $this->uriMask;
    }

    public function getAs(): string
    {
        return $this->as;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getUse(): string
    {
        return $this->use;
    }

    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    public function getWithArgs(mixed $args, array $globalArgs): string
    {
        $uri = $this->uriMask;
        array_walk($globalArgs, function($value, $key) use (&$uri) {
            $uri = preg_replace('/({' . $key . '(#)?[^}]+})/', $value, $uri);
        });

        if ($args && is_array($args)) {
            foreach ($args as $key => $arg) {
                if (preg_match('/{' . $key . '(#)?([^}]+)?}/', $uri)) {
                    $uri = preg_replace('/({' . $key . '([^}]+)?})/', $arg, $uri);
                    unset($args[$key]);
                }
            }
        } elseif (is_string($args)) {
           $uri = preg_replace('/({[^}]+})/', $args, $uri, 1);
           $args = [];
        }
        
        $uri = preg_replace('/({[^}]+})/', '', $uri);

        if (!empty($args)) {
            $uri .= '?' . http_build_query($args);
        }

        return $uri;
    }

    public function matches(string $uri): bool
    {
        $pattern = $this->getUriForPregReplace();
        return $this->uriMask == $uri || preg_match_all($pattern, '/' . ltrim($uri, '/'));
    }

    public function getUriForPregReplace(): ?string
    {
        $pattern =  preg_replace_callback('/{([^}]+)}/', function ($m) {
            preg_match('/(^[a-zA-Z_]+(?:#)?)?(.*)$/', $m[1], $parts);
            [, $name, $pattern] = $parts;
            $name = str_replace('#', '', $name);
            $pattern = $pattern ?: '[a-zA-Z_\-0-9]+';
            return "(?<{$name}>{$pattern})";
        }, $this->uriMask);

        return '/^\/?' . preg_replace([
            '/\//'
        ], [
            '\/'
        ], trim($pattern, '/')) . '$/';
    }

    public function getView(): string
    {
        return $this->view;
    }

    public function requestMethodIs($method): bool
    {
        if (str_contains($this->method, '|')) {
            return str_contains($this->method, $method);
        }

        return $this->method == $method || $this->method == 'ALL';
    }
}

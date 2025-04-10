<?php

declare(strict_types=1);

namespace Framework\Http\Route;

use Framework\Http\RequestMethod;
use Framework\Support\Arr;
use InvalidArgumentException;

class Route
{
    public readonly RequestMethod $method;

    public readonly string $uriMask;

    public readonly string $as;

    public readonly string $controller;

    public readonly string $use;

    public readonly string $view;

    public readonly array $middleware;

    public function __construct(string $method, string $uriMask, string $as, string $controller, string $use, array $middleware, string $view)
    {
        $this->method = RequestMethod::from($method);
        $this->uriMask = $uriMask;
        $this->as = $as;
        $this->controller = trim($controller, '\\');
        $this->use = $use;

        $namedMiddleware = config('app.named_middleware');

        $this->middleware = array_map(function ($m) use ($namedMiddleware) {
            if (class_exists($m)) {
                return $m;
            }
            [$name, $params] = explode('@', $m) + [null, ''];
            if (class_exists($name)) {
                return $m;
            } elseif (isset($namedMiddleware[$name])) {
                return trim($namedMiddleware[$name] . '@' . $params, '@');
            }

            throw new InvalidArgumentException("invalid middleware: {$m}");
        }, $middleware);
        $this->view = $view;
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
                    $uri = preg_replace('/({' . $key . '([^}]+)?})/', (string) $arg, $uri);
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

        return trim($uri, '?');
    }

    public function matches(string $uri): bool
    {
        $pattern = $this->getUriForPregReplace();
        return $this->uriMask == $uri || preg_match_all($pattern, '/' . ltrim($uri, '/'));
    }

    public function getUriForPregReplace(): ?string
    {
        $pattern =  preg_replace_callback('/{([^}]+)}/', function ($m) {
            preg_match('/(^[a-zA-Z_]+#?)?(.*)$/', $m[1], $parts);
            [, $name, $pattern] = $parts;
            $name = str_replace('#', '', $name);
            $pattern = $pattern ?: '[a-zA-Z_\-0-9\.]+';
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

    public function requestMethodIs(?RequestMethod $method): bool
    {
        if (is_null($method)) {
            return true;
        }

        if ($this->method->is(RequestMethod::ALL)) {
            return true;
        }

        return $this->method->is($method);
    }

    public function toArray(): array
    {
        return [
            'method' => $this->method->value(),
            'uriMask' => $this->uriMask,
            'as' => $this->as,
            'controller' => $this->controller,
            'use' => $this->use,
            'view' => $this->view,
            'middleware' => $this->middleware,
        ];
    }
}

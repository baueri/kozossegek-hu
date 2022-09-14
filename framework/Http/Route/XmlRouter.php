<?php

namespace Framework\Http\Route;

use Framework\Application;
use Framework\Misc\XmlObject;
use Framework\Model\Entity;
use Framework\Support\Collection;
use Framework\Model\Model;
use Framework\Http\Exception\RouteNotFoundException;

class XmlRouter implements RouterInterface
{

    /**
     * @var Collection|Route[]
     */
    protected $routes;

    protected static array $globalArgs = [];

    public function __construct(private Application $application)
    {
        $this->load();
    }

    private function load()
    {
        $this->routes = new Collection();

        $sources = $this->application->config('route_sources');
        foreach ($sources as $sourceFile) {
            $source = new XmlObject(file_get_contents($sourceFile . '.xml'));
            foreach ($source as $element) {
                $this->parseRoutes($element);
            }
        }
    }

    private function parseRoutes($elements)
    {
        if ($elements->getName() === 'route') {
            $route = $this->buildRoute($elements);

            $this->routes->push($route);
        } elseif ($elements->getName() === 'route-group') {
            foreach ($elements as $element) {
                $this->parseRoutes($element);
            }
        }
    }

    private function buildRoute($elements): RouteInterface
    {
        $builder = new XmlRouteBuilder($elements);
        return $builder->build();
    }

    public function get($uri, array $options): RouteInterface
    {
        return $this->add('get', $uri, $options);
    }

    public function add(string $method, string $uri, array $options): RouteInterface
    {
        /* @var $route RouteInterface */
        $route = app()->make(RouteInterface::class, array_merge([
            'method' => $method,
            'uriMask' => $uri,
        ], $options));
        $this->routes->push($route);

        return $route;
    }

    public function post($uri, array $options): RouteInterface
    {
        return $this->add('post', $uri, $options);
    }

    public function getRoutes(): Collection
    {
        return $this->routes;
    }

    public function find(string $method, string $uri): ?RouteInterface
    {
        if ($method === 'HEAD') {
            $method = 'GET';
        }

        $hasStrictMatch = false;
        foreach ($this->routes as $route) {
            $trimmed = trim($uri, '/');
            if ($route->getUriMask() == $trimmed) {
                $hasStrictMatch = true;

                if ($method == $route->requestMethodIs($method)) {
                    return $route;
                }
            }
            if ($route->matches($trimmed) && !$hasStrictMatch && $route->requestMethodIs($method)) {
                return $route;
            }
        }
        return null;
    }

    /**
     * @param string $name
     * @param array|string|Model|Entity $args
     * @return string
     * @throws RouteNotFoundException
     */
    public function route(string $name, mixed $args = null): string
    {
        if ($args instanceof Model || $args instanceof Entity) {
            $args = ['id' => $args->getId()];
        }

//        if (is_array($args)) {
//            $args = array_merge(static::$globalArgs, $args);
//        }

        foreach ($this->routes as $route) {
            if ($route->getAs() == $name) {
                return get_site_url() . '/' . ltrim($route->getWithArgs($args, static::$globalArgs), '/');
            }
        }

        throw new RouteNotFoundException($name);
    }

    public function addGlobalArg($name, $value)
    {
        static::$globalArgs[$name] = $value;
    }
}

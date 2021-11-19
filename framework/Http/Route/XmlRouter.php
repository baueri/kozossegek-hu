<?php

namespace Framework\Http\Route;

use Framework\Application;
use Framework\Http\Request;
use Framework\Misc\XmlObject;
use Framework\Model\Entity;
use Framework\Support\Collection;
use Framework\Model\Model;
use Framework\Http\Exception\RouteNotFoundException;

/**
 * Class XmlRouter
 * @package Framework\Http\Route
 */
class XmlRouter implements RouterInterface
{

    /**
     * @var Collection|Route[]
     */
    protected $routes;

    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var Application
     */
    private Application $application;

    /**
     * @var array
     */
    protected static array $globalArgs = [];

    /**
     * XmlRouter constructor.
     * @param Request $request
     * @param Application $application
     */
    public function __construct(Request $request, Application $application)
    {
        $this->request = $request;
        $this->application = $application;

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

    /**
     * @param $elements
     * @return RouteInterface
     */
    private function buildRoute($elements)
    {
        $builder = new XmlRouteBuilder($elements);
        return $builder->build();
    }

    /**
     * @param $uri
     * @param array $options
     * @return RouteInterface
     */
    public function get($uri, array $options)
    {
        return $this->add('get', $uri, $options);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return RouteInterface
     */
    public function add(string $method, string $uri, array $options)
    {
        /* @var $route RouteInterface */
        $route = app()->make(RouteInterface::class, array_merge([
            'method' => $method,
            'uriMask' => $uri,
        ], $options));
        $this->routes->push($route);

        return $route;
    }

    /**
     * @param $uri
     * @param array $options
     * @return RouteInterface
     */
    public function post($uri, array $options)
    {
        return $this->add('post', $uri, $options);
    }

    /**
     * @return RouteInterface[]|Collection
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param string $method
     * @param string $uri
     * @return Route|RouteInterface|mixed|null
     */
    public function find(string $method, string $uri)
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
    }

    /**
     * @param string $name
     * @param array|string|Model|\Framework\Model\Entity $args
     * @return string
     * @throws RouteNotFoundException
     */
    public function route(string $name, $args = [])
    {
        if ($args instanceof Model || $args instanceof Entity) {
            $args = ['id' => $args->getId()];
        }

        if (is_array($args)) {
            $args = array_merge(static::$globalArgs, $args);
        }

        foreach ($this->routes as $route) {
            if ($route->getAs() == $name) {
                return $route->getWithArgs($args);
            }
        }

        throw new RouteNotFoundException($name);
    }

    public function addGlobalArg($name, $value)
    {
        static::$globalArgs[$name] = $value;
    }
}

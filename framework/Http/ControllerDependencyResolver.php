<?php

declare(strict_types=1);

namespace Framework\Http;

use Framework\Dispatcher\HttpDispatcher;
use Framework\Http\Exception\PageNotFoundException;
use Framework\Http\Route\Route;
use Framework\Model\Entity;
use ReflectionException;

class ControllerDependencyResolver
{
    public function __construct(
        private readonly HttpDispatcher $dispatcher
    ) {
    }

    /**
     * @throws ReflectionException|PageNotFoundException
     */
    public function resolve(Route $route)
    {
        $controller = $this->dispatcher->app->make($route->getController());
        $method = $this->getMethod($controller, $route->getUse());
        $dependencies = $this->dispatcher->app->getDependencies($controller, $method);

        foreach ($dependencies as $i => $dependency) {
            if ($dependency instanceof Entity) {
                $dependencies[$i] = $dependency::query()->find($this->dispatcher->request->getUriValue($i));
            }
        }

        return $this->dispatcher->app->resolve($controller, $method, $dependencies);
    }

    /**
     * @throws PageNotFoundException
     */
    private function getMethod($controller, ?string $method): ?string
    {
        if (method_exists($controller, $method)) {
            return $method;
        }

        if (is_callable($controller)) {
            return '__invoke';
        }

        throw new PageNotFoundException($this->dispatcher->request->uri);
    }
}

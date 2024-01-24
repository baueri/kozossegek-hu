<?php

namespace Framework\Dispatcher;

use App\Services\MileStone;
use Exception;
use Framework\Application;
use Framework\Http\ControllerDependencyResolver;
use Framework\Http\Cookie;
use Framework\Http\Exception\RouteNotFoundException;
use Framework\Http\HttpKernel;
use Framework\Http\Request;
use Framework\Http\Route\RouteInterface;
use Framework\Http\Route\RouterInterface;
use Framework\Middleware\MiddlewareResolver;
use Framework\Http\Exception\PageNotFoundException;

class HttpDispatcher implements Dispatcher
{
    public function __construct(public readonly Application $app, public readonly Request $request, private RouterInterface $router, private HttpKernel $kernel)
    {
        Cookie::setTestCookie();
    }

    /**
     * @return void
     * @throws PageNotFoundException
     * @throws RouteNotFoundException
     */
    public function dispatch(): void
    {
        MileStone::measure('dispatch', 'Dispatching');

        $route = $this->getCurrentRoute();

        if (!$route->getView() && $route->getController() && !class_exists($route->getController())) {
            throw new PageNotFoundException("controller " . $route->getController() . " not found");
        }

        $this->request->route = $route;

        $middlewareResolver = new MiddlewareResolver();
        $middleware = array_merge($this->kernel->getMiddleware(), $route->getMiddleware());
        array_walk($middleware, fn($item) => $middlewareResolver->resolve($item)->handle());

        $response = $this->resolveRoute($route);

        MileStone::endMeasure('dispatch');

        if (is_array($response) || is_object($response)) {
            if (is_object($response) && method_exists($response, '__toString')) {
                echo $response;
            } else {
                echo json_encode(is_object($response) && method_exists($response, 'valuesToArray') ? $response->valuesToArray() : $response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            }
        } else {
            echo $response;
        }
    }

    /**
     * @throws PageNotFoundException
     */
    private function resolveRoute(RouteInterface $route)
    {
        if ($route->getView()) {
            $return = $this->resolveView();
        } elseif (!$route->getController() && $callback = $route->getUse()) {
            $return = call_user_func($callback, $this->request);
        } else {
            $return = $this->resolveController($route);
        }

        return $return;
    }

    /**
     * @throws PageNotFoundException|\ReflectionException
     */
    private function resolveController(RouteInterface $route)
    {
        $resolver = new ControllerDependencyResolver($this);

        return $resolver->resolve($route);
    }

    private function resolveView(): string
    {
        return view($this->request->route->getView(), $this->request->getUriValues());
    }

    /**
     * @throws RouteNotFoundException
     */
    public function getCurrentRoute(): ?RouteInterface
    {
        $route = $this->router->find($this->request->requestMethod, $this->request->uri);
        if ($route) {
            return $route;
        }
        throw new RouteNotFoundException($this->request->uri);
    }

    /**
     * @throws Exception|\Throwable
     */
    public function handleError($e): void
    {
        $this->kernel->handleError($e);
    }
}

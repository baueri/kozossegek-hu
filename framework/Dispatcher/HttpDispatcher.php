<?php

namespace Framework\Dispatcher;

use Exception;
use Framework\Application;
use Framework\Http\Controller;
use Framework\Http\Cookie;
use Framework\Http\Exception\RouteNotFoundException;
use Framework\Http\HttpKernel;
use Framework\Http\Request;
use Framework\Http\Route\RouteInterface;
use Framework\Http\Route\RouterInterface;
use Framework\Middleware\MiddlewareResolver;
use Framework\Support\StringHelper;
use Framework\Http\Exception\PageNotFoundException;

class HttpDispatcher implements Dispatcher
{
    private Application $app;

    private RouterInterface $router;

    private Request $request;

    private HttpKernel $kernel;

    /**
     * @param Application $app
     * @param Request $request
     * @param RouterInterface $router
     * @param HttpKernel $kernel
     */
    public function __construct(Application $app, Request $request, RouterInterface $router, HttpKernel $kernel)
    {
        $this->app = $app;
        $this->router = $router;
        $this->request = $request;
        $this->kernel = $kernel;

        Cookie::setTestCookie();
    }

    /**
     * @throws PageNotFoundException
     */
    public function dispatch(): void
    {
        $route = $this->getCurrentRoute();

        if (!$route->getView() && $route->getController() && !class_exists($route->getController())) {
            throw new PageNotFoundException("controller " . $route->getController() . " not found");
        }

        $this->request->route = $route;

        $middlewareResolver = new MiddlewareResolver();
        $middleware = array_merge($this->kernel->getMiddleware(), $route->getMiddleware());
        array_walk($middleware, fn($item) => $middlewareResolver->resolve($item)->handle());

        $response = $this->resolveRoute($route);

        if (is_array($response) || is_object($response)) {
            if (is_object($response) && method_exists($response, '__toString')) {
                echo $response;
            } else {
                echo json_encode(is_object($response) && method_exists($response, 'valuesToArray') ? $response->valuesToArray() : $response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            }
        } else {
            echo config('app.sanitize') ? StringHelper::sanitize($response) : $response;
        }
    }

    /**
     * @param RouteInterface $route
     * @return mixed
     * @throws PageNotFoundException
     */
    private function resolveRoute(RouteInterface $route)
    {
        if ($route->getView()) {
            return $this->resolveView();
        }
        if (!$route->getController() && $callback = $route->getUse()) {
            return call_user_func($callback, $this->request);
        }

        return $this->resolveController($route);
    }

    /**
     * @param RouteInterface $route
     * @return mixed
     * @throws PageNotFoundException
     */
    private function resolveController(RouteInterface $route)
    {
        $controller = $this->app->make($route->getController());

        if (!method_exists($controller, $route->getUse())) {
            throw new PageNotFoundException($this->request->uri);
        }

        return $this->app->resolve($controller, $route->getUse());
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

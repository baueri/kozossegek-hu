<?php

namespace Framework\Dispatcher;

use Exception;
use Framework\Application;
use Framework\Http\HttpKernel;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Route\RouteInterface;
use Framework\Http\Route\RouterInterface;
use Framework\Http\View\View;
use Framework\Support\StringHelper;
use Framework\Http\Exception\PageNotFoundException;

class HttpDispatcher implements Dispatcher
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var HttpKernel
     */
    private $kernel;

    /**
     * HttpDispatcher constructor.
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
    }

    /**
     * @throws PageNotFoundException
     */
    public function dispatch(): void
    {

        header('Accept-Encoding: gzip, deflate');

        $route = $this->getCurrentRoute();

        if (!$route->getView() && $route->getController() && !class_exists($route->getController())) {
            throw new PageNotFoundException("controller " . $route->getController() . " not found");
        }

        $this->request->route = $route;

        $middleware = array_unique(array_merge($this->kernel->getMiddleware(), $route->getMiddleware()));

        foreach ($middleware as $item) {
            $this->app->resolve($this->app->get($item), 'handle');
        }

        $response = $this->resolveRoute($route);

        if (is_array($response) || is_object($response)) {
            \Framework\Http\Response::asJson();
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
     * @return mixed|string
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

        return $this->app->resolve($controller, $route->getUse(), $this->request->getUriValues());
    }

    /**
     * @return string
     */
    private function resolveView()
    {
        return view($this->request->route->getView(), $this->request->getUriValues());
    }

    /**
     * @return RouteInterface|null
     */
    public function getCurrentRoute()
    {
        return $this->router->find($this->request->requestMethod, $this->request->uri);
    }

    /**
     *
     * @param Exception $e
     * @throws Exception
     */
    public function handleError($e)
    {
        $this->kernel->handleError($e);
    }
}

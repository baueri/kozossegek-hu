<?php

namespace Framework\Dispatcher;

use Framework\Application;
use Framework\Http\HttpKernel;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Route\Route;
use Framework\Http\Route\RouteInterface;
use Framework\Http\Route\RouteNotFoundException;
use Framework\Http\Route\RouterInterface;
use Framework\Http\View\View;

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
     * @throws RouteNotFoundException
     */
    public function dispatch(): void
    {
        $route = $this->getCurrentRoute();

        if ($route->getController() && !class_exists($route->getController())) {
            throw new RouteNotFoundException($route->getController());
        }

        $this->request->route = $route;


        $middleware = array_unique(array_merge($this->kernel->getMiddleware(), $route->getMiddleware()));

        foreach ($middleware as $item) {
            $this->app->resolve($this->app->get($item), 'handle');
        }

        $response = $this->resolveRoute($route);

        if (is_array($response) || is_object($response)) {
            echo json_encode(is_object($response) && method_exists($response, 'valuesToArray') ? $response->valuesToArray() : $response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } else {
            echo $response;
        }
    }

    private function resolveRoute(RouteInterface $route)
    {
        if ($route->getView()) {
            return $this->resolveView();
        }

        return $this->resolveController($route);
    }

    private function resolveController(RouteInterface $route)
    {
        $controller = $this->app->make($route->getController());
        
        if (!method_exists($controller, $route->getUse())) {
            throw new RouteNotFoundException();
        }
        
        return $this->app->resolve($controller, $route->getUse(), $this->request->getUriValues());
    }

    private function resolveView()
    {
        $request = $this->request;
        return $this->app->make(View::class)->view($request->route->getView(), $request->getUriValues());
    }

    public function getCurrentRoute()
    {
        return $this->router->find($this->request->requestMethod, $this->request->uri);
    }

    /**
     *
     * @param \Exception $e
     * @throws \Exception
     */
    public function handleError($e)
    {
        if (Response::contentTypeIsJson()) {
            print json_encode([
                'success' => false,
                'server_error' => 'internal_server_error'
            ]);
            throw $e;
        }
        

        if (DEBUG) {
            echo "<pre style='white-space:pre-line'><h3>Váratlan hiba történt (" . get_class($e) . ")</h3>";
            echo $e->getMessage() . "\n\n";
            echo $e->getTraceAsString();
            echo "</pre>";
        }

        throw $e;
    }
}
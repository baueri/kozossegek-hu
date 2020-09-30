<?php

namespace Framework\Dispatcher;

use Exception;
use Framework\Application;
use Framework\Http\HttpKernel;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Route\RouteInterface;
use Framework\Http\Route\RouteNotFoundException;
use Framework\Http\Route\RouterInterface;
use Framework\Http\View\View;
use Framework\Model\ModelNotFoundException;
use Framework\Support\StringHelper;

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

        header('Accept-Encoding: gzip, deflate');

        $middleware = array_unique(array_merge($this->kernel->getMiddleware(), $route->getMiddleware()));

        foreach ($middleware as $item) {
            $this->app->resolve($this->app->get($item), 'handle');
        }

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
     * @return mixed|string
     * @throws RouteNotFoundException
     */
    private function resolveRoute(RouteInterface $route)
    {
        if ($route->getView()) {
            return $this->resolveView();
        }

        return $this->resolveController($route);
    }

    /**
     * @param RouteInterface $route
     * @return mixed
     * @throws RouteNotFoundException
     */
    private function resolveController(RouteInterface $route)
    {
        $controller = $this->app->make($route->getController());
        
        if (!method_exists($controller, $route->getUse())) {
            throw new RouteNotFoundException($this->request->uri);
        }
        
        return $this->app->resolve($controller, $route->getUse(), $this->request->getUriValues());
    }

    /**
     * @return string
     */
    private function resolveView()
    {
        return $this->app->make(View::class)->view($this->request->route->getView(), $this->request->getUriValues());
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
        http_response_code($e->getCode());

        if (Response::contentTypeIsJson()) {
            print json_encode([
                'success' => false,
                'error_code' => $e->getCode()
            ]);
            throw $e;
        }

        if (config('app.debug')) {
            echo "<pre style='white-space:pre-line'><h3>Váratlan hiba történt (" . get_class($e) . ")</h3>";
            echo $e->getMessage() . "\n\n";
            echo $e->getTraceAsString();
            echo "</pre>";
            exit;
        }

        try {
            throw $e;
        } catch (ModelNotFoundException|RouteNotFoundException $e) {
            return print(view('portal.error', [
                'code' => $e->getCode(),
                'message' => 'A keresett oldal nem található',
                'message2' => 'Az oldal, amit keresel lehet, hogy törölve lett vagy ideiglenesen nem elérhető.']));
        } catch (Exception $e) {
            return print(view('portal.error', [
                'code' => 500,
                'message' => 'Váratlan hiba történt',
                'message2' => 'Az oldal üzemeltetői értesítve lettek a hibáról'
            ]));
        }
    }
}
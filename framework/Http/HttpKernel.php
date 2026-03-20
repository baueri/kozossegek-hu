<?php

namespace Framework\Http;

use App\Services\MileStone;
use Closure;
use Exception;
use Framework\Application;
use Framework\Http\Exception\PageNotFoundException;
use Framework\Http\Exception\RouteNotFoundException;
use Framework\Http\Route\Route;
use Framework\Http\Route\RouterInterface;
use Framework\Middleware\Before;
use Framework\Middleware\MiddlewareResolver;
use Framework\Middleware\After;

class HttpKernel
{
    /**
     * @var class-string<Before>[]
     */
    protected array $middleware = [];

    public function __construct(
        public readonly Application $app,
        public readonly Request $request,
        public readonly RouterInterface $router
    ) {
        Cookie::setTestCookie();
    }

    /**
     * @template T
     * @param class-string<T>|Closure $middleware
     * @return $this
     */
    public function middleware(string|Closure $middleware): static
    {
        $this->middleware[] = $middleware;

        return $this;
    }

    /**
     * @throws PageNotFoundException
     * @throws RouteNotFoundException
     * @throws Exception
     */
    public function handle(): void
    {
        MileStone::measure('http_dispatch', 'Dispatching http request');

        $middlewareResolver = new MiddlewareResolver();
        $kernelMiddleware = array_map(fn ($item) => $middlewareResolver->resolve($item), $this->getMiddleware());

        array_walk($kernelMiddleware, function ($item) {
            if ($item instanceof Before) {
                $item->before();
            }
        });

        $route = $this->request->route;

        if (!$route) {
            throw new RouteNotFoundException("route `{$this->request->uri}` not found");
        }

        if (!$route->view && $route->controller && !class_exists($route->controller)) {
            throw new PageNotFoundException("controller `" . $route->controller . "` not found");
        }

        $routeMiddleware = array_map(fn ($middleware) => $middlewareResolver->resolve($middleware), $route->getMiddleware());

        array_walk($routeMiddleware, fn(Before $item) => $item->before());

        $response = $this->resolveRoute($route);

        MileStone::endMeasure('http_dispatch');

        if (is_array($response) || is_object($response)) {
            if (is_object($response) && method_exists($response, '__toString')) {
                echo $response;
            } else {
                echo json_encode(is_object($response) && method_exists($response, 'valuesToArray') ? $response->valuesToArray() : $response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            }
        } else {
            echo $response;
        }

        array_map(function ($middleware) use ($middlewareResolver) {
            if($middleware instanceof After) {
                $middleware->after();
            }
        }, array_merge($kernelMiddleware, $routeMiddleware));
    }


    /**
     * @throws PageNotFoundException
     */
    private function resolveRoute(Route $route)
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
     * @throws PageNotFoundException
     */
    private function resolveController(Route $route)
    {
        $controller = $this->app->make($route->getController());

        if (method_exists($controller, $route->getUse())) {
            return $this->app->resolve($controller, $route->getUse());
        }

        if (is_callable($controller)) {
            return $this->app->resolve($controller, '__invoke');
        }

        throw new PageNotFoundException($this->request->uri);
    }

    private function resolveView(): string
    {
        return view($this->request->route->getView(), $this->request->uriValues);
    }

    public function getMiddleware(): array
    {
        return $this->middleware;
    }
}

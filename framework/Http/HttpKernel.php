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
use Framework\Middleware\Middleware;
use Framework\Middleware\MiddlewareResolver;

class HttpKernel
{
    /**
     * @var class-string<Middleware>[]
     */
    protected array $middleware = [];

    public function __construct(
        private readonly Application $app,
        private readonly Request $request,
        public readonly RouterInterface $router
    ) {
        Cookie::setTestCookie();
    }

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
        $kernelMiddleware = $this->getMiddleware();

        array_walk($kernelMiddleware, fn($item) => $middlewareResolver->resolve($item));

        $route = $this->request->route;

        if (!$route) {
            throw new RouteNotFoundException("route `{$this->request->uri}` not found");
        }

        if (!$route->view && $route->controller && !class_exists($route->controller)) {
            throw new PageNotFoundException("controller `" . $route->controller . "` not found");
        }

        $middleware = $route->getMiddleware();
        array_walk($middleware, fn($item) => $middlewareResolver->resolve($item));

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

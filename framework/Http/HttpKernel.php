<?php

namespace Framework\Http;

use App\Services\MileStone;
use Closure;
use Error;
use Exception;
use Framework\Application;
use Framework\Exception\UnauthorizedException;
use Framework\Http\Exception\PageNotFoundException;
use Framework\Http\Exception\RouteNotFoundException;
use Framework\Http\Route\RouteInterface;
use Framework\Http\Route\RouterInterface;
use Framework\Kernel;
use Framework\Middleware\Middleware;
use Framework\Middleware\MiddlewareResolver;
use Framework\Model\Exceptions\ModelNotFoundException;
use Throwable;

class HttpKernel implements Kernel
{
    /**
     * @var class-string<Middleware>[]
     */
    protected array $middleware = [];

    public function __construct(private Application $app, private Request $request, private RouterInterface $router)
    {
        Cookie::setTestCookie();
    }

    public function middleware(string|Closure $middleware): static
    {
        $this->middleware[] = $middleware;

        return $this;
    }

    public function handle()
    {
        MileStone::measure('http_dispatch', 'Dispatching http request');
        $middlewareResolver = new MiddlewareResolver();

        // todo a Translation middleware-ben hamarabb nyulunk a route-hoz, minthogy az beallitasra kerulne!
        dd('lsd fenti comment');
        $kernelMiddleware = $this->getMiddleware();

        array_walk($kernelMiddleware, fn($item) => $middlewareResolver->resolve($item));

        $route = $this->router->getCurrentRoute();

        if (!$route) {
            throw new RouteNotFoundException($this->request->uri);
        }

        if (!$route->getView() && $route->getController() && !class_exists($route->getController())) {
            throw new PageNotFoundException("controller " . $route->getController() . " not found");
        }

        $this->request->route = $route;

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
     * @throws PageNotFoundException
     */
    private function resolveController(RouteInterface $route)
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
        return view($this->request->route->getView(), $this->request->getUriValues());
    }

    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    public function handleMaintenance()
    {
        echo '<h1>Website under maintenance</h1>';
    }

    /**
     * @throws \Throwable
     * @var Error|Throwable|Exception $exception
     */
    public function handleError($exception)
    {
        Response::setStatusCode($exception->getCode() ?: 500);

        if (Response::contentTypeIsJson()) {
            print json_encode([
                'success' => false,
                'error_code' => $exception->getCode()
            ]);
            throw $exception;
        }

        if (config('app.debug') && $exception->getCode() != '401') {
            echo "<pre style='white-space:pre-line'><h3>Unexpected error (" . get_class($exception) . ")</h3>";
            echo "{$exception->getMessage()} in <b>{$exception->getFile()}</b> on line <b>{$exception->getLine()}</b> \n\n";
            echo $exception->getTraceAsString();
            echo "</pre>";
            exit;
        }

        try {
            throw $exception;
        } catch (PageNotFoundException | ModelNotFoundException | RouteNotFoundException $exception) {
            return print(view('portal.error', [
                'code' => $exception->getCode(),
                'message' => 'A keresett oldal nem található',
                'message2' => 'Az oldal, amit keresel lehet, hogy törölve lett vagy ideiglenesen nem elérhető.']));
        } catch (UnauthorizedException $exception) {
            return print(view('portal.error', [
                'code' => $exception->getCode(),
                'message2' => 'Nincs jogosultsága az oldal megtekintéséhez']));
        } catch (Error | Exception $exception) {
            error_log($exception);

            return print(view('portal.error', [
                'code' => 500,
                'message' => 'Váratlan hiba történt',
                'message2' => 'Az oldal üzemeltetői értesítve lettek a hibáról'
            ]));
        }
    }
}

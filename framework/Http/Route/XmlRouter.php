<?php

declare(strict_types=1);

namespace Framework\Http\Route;

use Exception;
use Framework\Http\RequestMethod;
use Framework\Misc\XmlObject;
use Framework\Model\Entity;
use Framework\Support\Collection;
use Framework\Model\Model;
use Framework\Http\Exception\RouteNotFoundException;
use Framework\Http\Request;

class XmlRouter implements RouterInterface
{
    /**
     * @phpstan-var Collection<Route>
     */
    protected Collection $routes;

    protected static array $globalArgs = [];

    /**
     * @throws Exception
     */
    public function __construct(
        protected readonly Request $request,
        protected readonly array $routeSources
    ) {
        $this->load();
    }

    public function getCurrentRoute(): ?Route
    {
        return $this->find($this->request->uri, $this->request->requestMethod);
    }

    /**
     * @throws Exception
     */
    private function load(): void
    {
        $this->routes = new Collection();

        $sources = $this->routeSources;

        $maxMtime = max(...array_map(fn ($file) => filemtime($file), $sources));

        $cachedFile = CACHE . "route.php";

        if (env('ROUTE_CACHE_ENABLED') && file_exists($cachedFile) && filemtime($cachedFile) >= $maxMtime) {
            $cachedRoutes = require_once $cachedFile;
            foreach ($cachedRoutes as $route) {
                $this->addRoute(new Route($route['method'],
                    $route['uriMask'],
                    $route['as'],
                    (string) ($route['controller'][0] ?? ''),
                    (string) ($route['controller'][1] ?? ''),
                    $route['middleware'],
                    $route['view'])
                );
            }
            return;
        }

        foreach ($sources as $sourceFile) {
            $source = new XmlObject(file_get_contents($sourceFile));
            foreach ($source as $element) {
                $this->parseRoutes($element);
            }
        }

        $this->saveToCache($cachedFile);
    }

    public function addRoute(Route $route): static
    {
        $this->routes[] = $route;

        return $this;
    }

    private function parseRoutes($elements): void
    {
        if ($elements->getName() === 'route') {
            if ($scope = (string) ($elements['resource'] ?? null)) {
                $this->routes->push(
                    ...$this->buildResources($elements, $scope)
                );
            } else {
                $this->routes->push(
                    $this->buildRoute($elements)
                );
            }
        } elseif ($elements->getName() === 'route-group') {
            foreach ($elements as $element) {
                $this->parseRoutes($element);
            }
        }
    }

    private function buildRoute($elements): Route
    {
        $builder = new XmlRouteBuilder($elements);
        return $builder->build();
    }

    private function buildResources($elements, string $scope): array
    {
        $routes = [];
        foreach (CrudResource::getResources($scope) as $resource) {
            $elements['method'] = mb_strtolower($resource->requestMethod()->name);
            $elements['use'] = $resource->value();
            $elements['uri'] = $resource->uri((string) $elements['prefix']);
            $routes[] = $this->buildRoute($elements);
        }

        return $routes;
    }

    public function get($uri, array $options): Route
    {
        return $this->add('get', $uri, $options);
    }

    public function add(string $method, string $uri, array $options): Route
    {
        $route = app()->make(Route::class, array_merge([
            'method' => $method,
            'uriMask' => $uri,
        ], $options));
        $this->routes->push($route);

        return $route;
    }

    public function post($uri, array $options): Route
    {
        return $this->add('post', $uri, $options);
    }

    /**
     * @return Collection<Route>
     */
    public function getRoutes(): Collection
    {
        return $this->routes;
    }

    public function find(string $uri, null|RequestMethod $method = null): ?Route
    {
        if ($method === RequestMethod::ALL->name) {
            $method = RequestMethod::ALL;
        }

        if ($method === RequestMethod::HEAD) {
            $method = RequestMethod::GET;
        }

        $hasStrictMatch = false;
        foreach ($this->routes as $route) {
            $trimmed = trim($uri, '/');
            if ($route->getUriMask() == $trimmed) {
                $hasStrictMatch = true;

                if ($route->requestMethodIs($method)) {
                    return $route;
                }
            }
            if ($route->matches($trimmed) && !$hasStrictMatch && $route->requestMethodIs($method)) {
                return $route;
            }
        }
        return null;
    }

    /**
     * @param string $name
     * @param array|string|Model|Entity $args
     * @param bool $withHost
     * @return string
     * @throws RouteNotFoundException
     */
    public function route(string $name, mixed $args = null, bool $withHost = true): string
    {
        foreach ($this->routes as $route) {
            if ($route->getAs() == $name) {
                if ($args instanceof Model || $args instanceof Entity) {
                    $args = ['id' => (string) $args->getId()];
                }
                return ($withHost ? get_site_url() : '') . '/' . ltrim($route->getWithArgs($args, static::$globalArgs), '/');
            }
        }

        throw new RouteNotFoundException($name);
    }

    public function addGlobalArg($name, $value): void
    {
        static::$globalArgs[$name] = $value;
    }

    private function saveToCache(string $cachedFile): void
    {
        $output = "<?php \nreturn [\n";
        foreach ($this->routes as $route) {
            $output .= "\t[\n";
            $output .= "\t\t'method' => '{$route->method->value()}',\n";
            $output .= "\t\t'uriMask' => '{$route->uriMask}',\n";
            $output .= "\t\t'as' => '{$route->as}',\n";
            if (!$route->view) {
                $use = $route->use ?: '__invoke';
                $output .= "\t\t'controller' => ['{$route->controller}', '{$use}'],\n";
                $output .= "\t\t'view' => '',\n";
            } else {
                $output .= "\t\t'controller' => [],\n";
                $output .= "\t\t'view' => '{$route->view}',\n";
            }
            if ($route->middleware) {
                $output .= "\t\t'middleware' => ['" . implode("','", $route->middleware) . "']";
            } else {
                $output .= "\t\t'middleware' => []";
            }
            $output .= "\n\t],\n";
        }
        $output .= "];\n";

        if (!is_dir($dir = dirname($cachedFile))) {
            mkdir($dir);
        }

        file_put_contents($cachedFile, $output);
    }
}

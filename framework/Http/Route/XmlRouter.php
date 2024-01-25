<?php

declare(strict_types=1);

namespace Framework\Http\Route;

use Exception;
use Framework\Application;
use Framework\Misc\XmlObject;
use Framework\Model\Entity;
use Framework\Support\Collection;
use Framework\Model\Model;
use Framework\Http\Exception\RouteNotFoundException;

class XmlRouter implements RouterInterface
{
    /**
     * @var Collection<Route>
     * @phpstan-var Route[]|Collection
     */
    protected Collection $routes;

    protected static array $globalArgs = [];

    /**
     * @throws Exception
     */
    public function __construct(private readonly Application $application)
    {
        $this->load();
    }

    /**
     * @throws Exception
     */
    private function load(): void
    {
        $this->routes = new Collection();

        $sources = $this->application->config('route_sources');

        $maxMtime = max(...array_map(fn ($file) => filemtime($file), $sources));

        $cachedFile = CACHE . "route.php";

        if (env('ROUTE_CACHE_ENABLED') && file_exists($cachedFile) && filemtime($cachedFile) >= $maxMtime) {
            $cachedRoutes = require_once $cachedFile;
            foreach ($cachedRoutes as $route) {
                $this->routes[] = app()->make(RouteInterface::class, [
                    $route['method'],
                    $route['uriMask'],
                    $route['as'],
                    (string) ($route['controller'][0] ?? ''),
                    (string) ($route['controller'][1] ?? ''),
                    $route['middleware'],
                    $route['view']
                ]);
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

    private function parseRoutes($elements)
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

    private function buildRoute($elements): RouteInterface
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

    public function get($uri, array $options): RouteInterface
    {
        return $this->add('get', $uri, $options);
    }

    public function add(string $method, string $uri, array $options): RouteInterface
    {
        $route = app()->make(RouteInterface::class, array_merge([
            'method' => $method,
            'uriMask' => $uri,
        ], $options));
        $this->routes->push($route);

        return $route;
    }

    public function post($uri, array $options): RouteInterface
    {
        return $this->add('post', $uri, $options);
    }

    public function getRoutes(): Collection
    {
        return $this->routes;
    }

    public function find(string $method, string $uri): ?RouteInterface
    {
        if ($method === 'HEAD') {
            $method = 'GET';
        }

        $hasStrictMatch = false;
        foreach ($this->routes as $route) {
            $trimmed = trim($uri, '/');
            if ($route->getUriMask() == $trimmed) {
                $hasStrictMatch = true;

                if ($method == $route->requestMethodIs($method)) {
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
        if ($args instanceof Model || $args instanceof Entity) {
            $args = ['id' => (string) $args->getId()];
        }

//        if (is_array($args)) {
//            $args = array_merge(static::$globalArgs, $args);
//        }

        foreach ($this->routes as $route) {
            if ($route->getAs() == $name) {
                return ($withHost ? get_site_url() : '') . '/' . ltrim($route->getWithArgs($args, static::$globalArgs), '/');
            }
        }

        throw new RouteNotFoundException($name);
    }

    public function addGlobalArg($name, $value)
    {
        static::$globalArgs[$name] = $value;
    }

    private function saveToCache(string $cachedFile)
    {
        $output = "<?php \nreturn [\n";
        foreach ($this->routes as $route) {
            $output .= "\t[\n";
            $output .= "\t\t'method' => '{$route->method}',\n";
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

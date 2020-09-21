<?php


namespace Framework\Http\Route;

use Framework\Support\Collection;

interface RouterInterface
{
    /**
     * @return Collection|RouteInterface[]
     */
    public function getRoutes();

    /**
     * @param string $method
     * @param string $uri
     * @return RouteInterface|null
     */
    public function find(string $method, string $uri);

    /**
     * @param string $name
     * @param array $args
     * @return string
     */
    public function route(string $name, array $args = []);

    /**
     * @param $name
     * @param $value
     */
    public function addGlobalArg($name, $value);

}
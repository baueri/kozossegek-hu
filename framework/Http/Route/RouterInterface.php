<?php

namespace Framework\Http\Route;

use Framework\Support\Collection;

interface RouterInterface
{
    /**
     * @return Collection<RouteInterface>
     */
    public function getRoutes(): Collection;

    public function find(string $method, string $uri): ?string;

    /**
     * @param string $name
     * @param array $args
     * @return string
     */
    public function route(string $name, mixed $args = null): string;

    public function addGlobalArg($name, $value);
}
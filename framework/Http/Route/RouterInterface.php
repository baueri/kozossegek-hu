<?php

namespace Framework\Http\Route;

use Framework\Http\RequestMethod;
use Framework\Support\Collection;

interface RouterInterface
{
    /**
     * @return Collection<Route>
     */
    public function getRoutes(): Collection;

    public function find(string $uri, null|RequestMethod $method = null): ?Route;

    public function getCurrentRoute(): ?Route;

    /**
     * @param string $name
     * @param array $args
     * @param bool $withHost
     * @return string
     */
    public function route(string $name, mixed $args = null, bool $withHost = true): string;

    public function addGlobalArg($name, $value);

    public function addRoute(Route $route): static;
}

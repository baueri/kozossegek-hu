<?php

namespace Framework\Container;

use Closure;
use Framework\Container\Exceptions\AbstractionAlreadySharedException;
use Framework\Container\Exceptions\AlreadyBoundException;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionParameter;
use UnitEnum;

class Container implements ContainerInterface
{
    protected array $bindings = [];

    protected array $singletons = [];

    protected array $shared = [];

    /**
     * @throws AbstractionAlreadySharedException
     * @throws AlreadyBoundException
     */
    public function singleton(array|string $abstraction, Closure|string|null $concrete = null): void
    {
        if (is_array($abstraction)) {
            array_walk($abstraction, fn ($concrete, $abstraction) => $this->singleton($abstraction, $concrete));
            return;
        }

        if ($this->isSingletonRegistered($abstraction)) {
            throw new AbstractionAlreadySharedException("$abstraction is already registered");
        }

        if (is_null($concrete)) {
            $concrete = $abstraction;
        }

        $this->bind($abstraction, $concrete);

        $this->singletons[] = $abstraction;
    }

    private function isSingletonRegistered($abstraction): bool
    {
        return in_array($abstraction, $this->singletons);
    }

    /**
     * @throws AlreadyBoundException
     */
    public function bind(string|array $abstraction, $concrete = null, bool $override = false): void
    {
        if (is_array($abstraction)) {
            array_walk($abstraction, fn ($concrete, $abstraction) => $this->bind($abstraction, $concrete, $override));
            return;
        }

        if (!$abstraction) {
            throw new InvalidArgumentException('abstraction name must not be empty');
        }

        if ($this->isBindingRegistered($abstraction) && !$override) {
            throw new AlreadyBoundException("$abstraction already has a binding");
        }

        $this->bindings[$abstraction] = $concrete;
    }

    private function isBindingRegistered($abstraction): bool
    {
        return isset($this->bindings[$abstraction]);
    }

    /**
     * @template T
     * @param class-string<T> $id
     * @return T
     */
    public function get($id)
    {
        if ($this->has($id) || $this->isSingletonRegistered($id)) {
            return $this->getShared($id);
        }

        return $this->make($id, func_get_args()[1] ?? []);
    }

    public function put($key, $value)
    {
        if (!$this->has($key)) {
            $this->share($key, [$value]);
        }

        $this->shared[$key][] = $value;
    }

    public function has($id): bool
    {
        return isset($this->shared[$id]);
    }

    private function getShared($id)
    {
        if (!$this->has($id)) {
            $this->shared[$id] = $this->make($id);
        }

        return $this->shared[$id];
    }

    /**
     * @phpstan-template T
     * @phpstan-param class-string<T> $abstraction
     * @phpstan-return T
     */
    public function make(string $abstraction, ?array $args = [])
    {
        $binding = $this->getBinding($abstraction);

        if (is_callable($binding)) {
            return $binding(...$this->getDependencies($binding, resolvedDependencies: $args));
        }

        if (interface_exists($binding)) {
            throw new InvalidArgumentException('Cannot instantiate interface ' . $binding . ' without a binding registered to it');
        }

        $args = $this->getDependencies($binding, '__construct', $args);

        return new $binding(...$args);
    }

    protected function getBinding($binding)
    {
        if ($this->isBindingRegistered($binding)) {
            return $this->bindings[$binding];
        }

        if ($fallback = $this->getFallback($binding)) {
            return $fallback;
        }

        return $binding;
    }

    private function getFallback($class)
    {
        $parent = get_parent_class($class);

        if (!$parent) {
            return null;
        }

        if ($this->isBindingRegistered($parent)) {
            return $this->getBinding($parent);
        }

        return $this->getFallback($parent);
    }

    public function getDependencies($class, string $method = '__construct', ?array $resolvedDependencies = []): array
    {
        if (!method_exists($class, $method) && !is_callable($class) && !$class instanceof Closure) {
            return [];
        }

        $dependencies = [];
        $reflectionMethod = $this->getReflectionMethod($class, $method);
        $parameters = $reflectionMethod->getParameters();

        foreach ($parameters as $i => $parameter) {
            if (array_key_exists($i, $resolvedDependencies) && key($resolvedDependencies) === 0) {
                $dependencies[] = $resolvedDependencies[$i];
            } elseif (array_key_exists($parameter->name, $resolvedDependencies)) {
                $dependencies[] = $resolvedDependencies[$parameter->name];
            } elseif ($this->isResolvable($parameter)) {
                $dependencies[] = $this->getDependencyResourceValue($parameter);
            }
        }

        return $dependencies;
    }

    private function getReflectionMethod($abstract, string $method = '__construct'): ?ReflectionFunctionAbstract
    {
        if ($abstract instanceof Closure || (is_string($abstract) && is_callable($abstract))) {
            return new ReflectionFunction($abstract);
        }

        if (!method_exists($abstract, $method)) {
            return null;
        }

        return new ReflectionMethod($abstract, $method);
    }

    private function getDependencyResourceValue(ReflectionParameter $resource)
    {
        $value = null;
        if ($resource->hasType()) {
            $resourceType = $resource->getType()->getName();

            if ($resource->isDefaultValueAvailable()) {
                $value = $resource->getDefaultValue();
            } elseif (is_subclass_of($resourceType, UnitEnum::class) && $requestValue = request()->get($resource->name) ?? request()->getUriValue($resource->name)) {
                $value = constant("{$resourceType}::{$requestValue}");
            } else {
                $value = $this->get($resourceType);
            }
        } elseif ($resource->isDefaultValueAvailable()) {
            $value = $resource->getDefaultValue();
        }
        return $value;
    }

    public function isResolvable(ReflectionParameter $parameter): bool
    {
        $type = $parameter->getType();
        if (!$type) {
            return false;
        }
        $name = $type->getName();
        return interface_exists($name) || class_exists($name);
    }

    public function share(string $key, $value): void
    {
        $this->shared[$key] = $value;
    }

    public function getBindings(): array
    {
        return $this->bindings;
    }

    public function resolve($concrete, ?string $method = null)
    {
        if (is_callable($concrete)) {
            return call_user_func_array($concrete, $this->getDependencies($concrete, '__invoke'));
        }

        $method ??= '__construct';

        return call_user_func_array([$concrete, $method], $this->getDependencies($concrete, $method));
    }
}

<?php

namespace Framework\Container;

use Framework\Container\Exceptions\AbstractionAlreadySharedException;
use Framework\Container\Exceptions\AlreadyBoundException;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use ReflectionException;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionParameter;

class Container implements ContainerInterface
{
    protected $bindings = [];

    protected $singletons = [];

    protected $shared = [];

    /**
     * @param string $abstraction
     * @param mixed $concrete
     * @throws AbstractionAlreadySharedException
     * @throws AlreadyBoundException
     */
    public function singleton($abstraction, $concrete = null): void
    {
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

    public function bind($abstraction, $concrete): void
    {
        if (!$abstraction) {
            throw new InvalidArgumentException('abstraction name must not be empty');
        }

        if ($this->isBindingRegistered($abstraction)) {
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

        return $this->make($id);
    }

    public function put($key, $value)
    {
        if (!$this->has($key)) {
            $this->share($key, [$value]);
        }

        $this->shared[$key][] = $value;
    }

    public function has($id)
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
     * @template T
     * @param string|T $abstraction
     * @return T
     */
    public function make(string $abstraction, ...$args)
    {
        $binding = $this->getBinding($abstraction);

        if (is_callable($binding)) {
            return $binding(...$this->getDependencies($binding));
        }

        if (interface_exists($binding)) {
            throw new InvalidArgumentException('Cannot instantiate interface ' . $binding . ' without a binding registered to it');
        }

        if (empty($args)) {
            $args = $this->getDependencies($binding);
        }

        return new $binding(...$args);
    }

    /**
     * @param $binding
     * @return mixed
     */
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

    /**
     * @throws \ReflectionException
     */
    private function getDependencies($class, string $method = '__construct'): array
    {
        if (!method_exists($class, $method) && !function_exists($class)) {
            return [];
        }

        $dependencies = [];
        $reflectionMethod = $this->getReflectionMethod($class, $method);
        $parameters = $reflectionMethod->getParameters();
        foreach ($parameters as $parameter) {
            $dependencies[] = $this->getDependencyResourceValue($parameter);
        }

        return $dependencies;
    }

    private function getReflectionMethod($class, string $method = '__construct'): ?ReflectionFunctionAbstract
    {
        if (is_callable($class)) {
            return new ReflectionFunction($class);
        }

        if (!method_exists($class, $method)) {
            return null;
        }

        return new ReflectionMethod($class, $method);
    }

    /**
     * @param ReflectionParameter $resource
     * @return mixed
     */
    private function getDependencyResourceValue(ReflectionParameter $resource)
    {
        $value = null;

        if ($resource->hasType()) {
            $resourceType = $resource->getType()->getName();
            if ($resource->isDefaultValueAvailable()) {
                $value = $resource->getDefaultValue();
            } else {
                $value = $this->get($resourceType);
            }
        } elseif ($resource->isDefaultValueAvailable()) {
            $value = $resource->getDefaultValue();
        }

        return $value;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function share(string $key, $value): void
    {
        $this->shared[$key] = $value;
    }

    public function getBindings(): array
    {
        return $this->bindings;
    }

    /**
     * @param mixed $concrete
     * @return mixed
     * @throws \ReflectionException
     */
    public function resolve($concrete, string $method = '__construct')
    {
        return call_user_func_array([$concrete, $method], $this->getDependencies($concrete, $method));
    }
}

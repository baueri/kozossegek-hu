<?php


namespace Framework\Container;


use ReflectionException;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionParameter;

class DependencyInjectionResolver
{

    /**
     * @param $class
     * @param string $method
     * @param array|null $params
     * @return mixed
     * @throws ReflectionException
     */
    public function resolve($class, $method, array $params = null)
    {
        return call_user_func_array([$class, $method], $params ?: $this->getDependencies($class, $method));
    }

    /**
     * @param $class
     * @param string $method
     * @return array
     * @throws ReflectionException
     */
    private function getDependencies($class, $method = '__construct')
    {
        if (!method_exists($class, $method) && !function_exists($class)) {
            return [];
        }

        $dependencies = [];
        $reflectionMethod = $this->getReflectionMethod($class, $method);
        $parameters = $reflectionMethod->getParameters();

        foreach ($parameters as $key => $parameter) {
            if (!is_callable($class) || $key != 0) {
                $dependencies[] = $this->getDependencyResourceValue($parameter);
            }
        }

        return $dependencies;
    }

    /**
     * @param $class
     * @param string $method
     * @return ReflectionFunctionAbstract
     * @throws ReflectionException
     */
    private function getReflectionMethod($class, $method = '__construct')
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
     * @throws ReflectionException
     */
    private function getDependencyResourceValue(ReflectionParameter $resource)
    {
        $value = null;

        if ($resource->hasType()) {

            $resourceType = $resource->getType()->getName();

            if ($resource->isDefaultValueAvailable()) {
                $value = $resource->getDefaultValue();
            } else {
                $value = $this->make($resourceType);
            }

        } elseif ($resource->isDefaultValueAvailable()) {

            $value = $resource->getDefaultValue();
        }

        return $value;
    }

}
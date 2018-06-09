<?php

namespace HotRodCli;

class AppContainer
{
    /**
     * All registered.
     * ----------------------------------------
     * @var array
     */
    protected $registry = [];

    public function resolve(string $class, $args = null)
    {
        if (array_key_exists($class, $this->registry)) {
            return $this->registry[$class];
        }

        $reflector = new \ReflectionClass($class);

        if (!$reflector->isInstantiable()) {
            throw new \Exception("[$class] is not instantiable");
        }

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return $this->resolveWithoutConstructor($class);
        }

        if (!is_null($args)) {
            return $this->resolveWithSetParams($class, $args);
        }

        $parameters = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters);

        $result = $reflector->newInstanceArgs($dependencies);
        $this->bind($class, $result);

        return $result;
    }

    protected function resolveWithoutConstructor(string $class)
    {
        $result = new $class;
        $this->bind($class, $result);

        return $result;
    }

    protected function resolveWithSetParams(string $class, $args)
    {
        $reflector = new \ReflectionClass($class);
        $result = $reflector->newInstance($args);
        $this->bind($class, $result);

        return $result;
    }

    /**
     * @param $parameters
     * @return array
     */
    public function getDependencies($parameters)
    {
        $dependencies = [];

        /** @var \ReflectionParameter $parameter */
        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();

            if (is_null($dependency)) {
                $dependencies[] = $this->resolveNonClass($parameter);
            } else {
                $dependencies[] = $this->resolve($dependency->name);
            }
        }

        return $dependencies;
    }

    /**
     * @param $key
     * @param $value
     * @throws \Exception
     */
    public function bind($key, $value)
    {
        if (! array_key_exists($key, $this->registry)) {
            $this->registry[$key] = $value;
        } else {
            throw new \Exception("{$key} is already bound in the container.");
        }
    }

    /**
     * @param $key
     * @return mixed
     * @throws \Exception
     */
    public function get($key)
    {
        if (! array_key_exists($key, $this->registry)) {
            throw new \Exception("No {$key} is bound in the container.");
        }

        return $this->registry[$key];
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return mixed
     * @throws \Exception
     */
    public function resolveNonClass(\ReflectionParameter $parameter)
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        throw new \Exception("Cannot resolve the unknown");
    }
}

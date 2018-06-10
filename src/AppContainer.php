<?php

namespace HotRodCli;

use HotRodCli\Checkers\BaseStatus;
use HotRodCli\Checkers\Container\HasArguments;
use HotRodCli\Checkers\Container\HasConstructor;
use HotRodCli\Checkers\Container\IsAlreadyExists;
use HotRodCli\Checkers\Container\IsInstantiable;

class AppContainer
{
    /**
     * All registered.
     * ----------------------------------------
     * @var array
     */
    protected $registry = [];

    protected $checkers = [
        IsAlreadyExists::class => 'isAlreadyExists',
        IsInstantiable::class => 'canNotInstantiate',
        HasConstructor::class => 'hasConstructor',
        HasArguments::class => 'hasArguments'
    ];

    public function resolve(string $class, $args = null)
    {
        $status = $this->runCheckers($class, $args);

        if (!$status->getStatus()) {
            return $this->{$this->checkers[$status->getFailedWith()]}($class, $args);
        }

        $reflector = new \ReflectionClass($class);
        $constructor = $reflector->getConstructor();
        $parameters = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters);
        $result = $reflector->newInstanceArgs($dependencies);
        $this->bind($class, $result);

        return $result;
    }

    protected function runCheckers($class, $args)
    {
        $exists = new IsAlreadyExists($this->registry, $class);
        $isInstantiable = new IsInstantiable($class);
        $hasConstructor = new HasConstructor($class);
        $hasArguments = new HasArguments($args);

        $exists->succeedWIth($isInstantiable);
        $isInstantiable->succeedWIth($hasConstructor);
        $hasConstructor->succeedWIth($hasArguments);

        return $exists->check(new BaseStatus());
    }

    protected function hasArguments($class, $args)
    {
        return $this->resolveWithSetParams($class, $args);
    }

    protected function hasConstructor($class)
    {
        return $this->resolveWithoutConstructor($class);
    }

    protected function isAlreadyExists($class)
    {
        return $this->registry[$class];
    }

    protected function canNotInstantiate($class)
    {
        throw new \Exception("[$class] is not instantiable");
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

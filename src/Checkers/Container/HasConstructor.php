<?php

namespace HotRodCli\Checkers\Container;

use HotRodCli\Checkers\BaseChecker;
use HotRodCli\Checkers\BaseStatus;

class HasConstructor extends BaseChecker
{
    protected $class;

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function check(BaseStatus $status)
    {
        $reflector = new \ReflectionClass($this->class);
        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            $status->setFailedWith(HasConstructor::class);
            $status->setStatus(false);

            return $status;
        }

        return $this->next($status);
    }
}

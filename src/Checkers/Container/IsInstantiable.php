<?php

namespace HotRodCli\Checkers\Container;

use HotRodCli\Checkers\BaseChecker;
use HotRodCli\Checkers\BaseStatus;

class IsInstantiable extends BaseChecker
{
    protected $class;

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function check(BaseStatus $status)
    {
        $reflector = new \ReflectionClass($this->class);

        if (!$reflector->isInstantiable()) {
            $status->setFailedWith(IsInstantiable::class);
            $status->setStatus(false);

            return $status;
        }

        return $this->next($status);
    }
}

<?php

namespace HotRodCli\Checkers\Container;

use HotRodCli\Checkers\BaseChecker;
use HotRodCli\Checkers\BaseStatus;

class IsAlreadyExists extends BaseChecker
{
    protected $registry;

    protected $class;

    public function __construct(array $registry, string $class)
    {
        $this->registry = $registry;
        $this->class = $class;
    }

    public function check(BaseStatus $status)
    {
        if (array_key_exists($this->class, $this->registry)) {
            $status->setFailedWith(IsAlreadyExists::class);
            $status->setStatus(false);

            return $status;
        }

        return $this->next($status);
    }
}

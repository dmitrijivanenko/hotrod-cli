<?php

namespace HotRodCli\Checkers\Container;

use HotRodCli\Checkers\BaseChecker;
use HotRodCli\Checkers\BaseStatus;

class HasArguments extends BaseChecker
{
    protected $args;

    public function __construct($args)
    {
        $this->args = $args;
    }

    public function check(BaseStatus $status)
    {
        if (!is_null($this->args)) {
            $status->setFailedWith(HasArguments::class);
            $status->setStatus(false);

            return $status;
        }

        return $this->next($status);
    }
}

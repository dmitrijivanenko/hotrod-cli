<?php

namespace HotRodCli\Checkers;

abstract class BaseChecker
{
    /** @var  BaseChecker */
    protected $successor;

    abstract public function check(BaseStatus $status);

    public function succeedWIth(BaseChecker $successor)
    {
        $this->successor = $successor;
    }

    public function next(BaseStatus $status)
    {
        if ($this->successor) {
            return $this->successor->check($status);
        } else {
            return $status;
        }
    }
}

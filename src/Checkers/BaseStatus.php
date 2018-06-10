<?php

namespace HotRodCli\Checkers;

class BaseStatus
{
    protected $failedWith = null;

    protected $status = true;

    public function setFailedWith(string $className)
    {
        $this->failedWith = $className;

        return $this;
    }

    public function getFailedWith()
    {
        return $this->failedWith;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }
}

<?php

namespace HotRodCli\Api;

use HotRodCli\AppContainer;

class GetCommands
{
    protected $container;

    public function __construct(AppContainer $appContainer)
    {
        $this->container = $appContainer;
    }

    public function __invoke()
    {
        return json_encode(array_keys($this->container->get('commands')));
    }
}

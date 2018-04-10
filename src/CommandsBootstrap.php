<?php

namespace HotRodCli;

use Symfony\Component\Console\Application;

class CommandsBootstrap
{
    protected $commands;

    protected $container;

    public function __construct(array $commands, AppContainer $container)
    {
        $this->commands = $commands;
        $this->container = $container;
    }

    public function register(Application $app)
    {
        foreach ($this->commands as $command) {
            $app->add($this->container->resolve($command));
        }
    }
}

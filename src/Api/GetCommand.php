<?php

namespace HotRodCli\Api;

use HotRodCli\AppContainer;
use HotRodCli\Commands\BaseCommand;

class GetCommand extends GetCommands
{
    protected $commands;

    public function __construct(AppContainer $appContainer)
    {
        parent::__construct($appContainer);
        $this->commands = $this->container->resolve('commands');
    }

    public function __invoke($args = [])
    {
        /** @var BaseCommand $command */
        $command = $this->container->resolve($this->commands[$args['command']]);

        return json_encode([
            'name' => $command->getName(),
            'description' => $command->getDescription(),
            'help' => $command->getHelp(),
            'arguments' => $this->constructArguments($command),
            'options' => $this->constructOptions($command)
        ]);
    }
}

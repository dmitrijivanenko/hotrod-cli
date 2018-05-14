<?php

namespace HotRodCli\Api;

use HotRodCli\AppContainer;
use HotRodCli\Commands\BaseCommand;

class GetCommand
{
    protected $container;

    protected $commands;

    public function __construct(AppContainer $appContainer)
    {
        $this->container = $appContainer;
        $this->commands = $this->container->resolve('commands');
    }

    public function __invoke($args)
    {
        /** @var BaseCommand $command */
        $command = $this->container->resolve($this->commands[$args['command']]);

        return json_encode([
            'arguments' => $this->constructArguments($command),
            'options' => $this->constructOptions($command)
        ]);
    }

    public function constructArguments(BaseCommand $command)
    {
        $result = [];

        foreach ($command->getDefinition()->getArguments() as $argument) {
            $result[] = [
                'name' => $argument->getName(),
                'description' => $argument->getDescription()
            ];
        }

        return $result;
    }

    public function constructOptions(BaseCommand $command)
    {
        $result = [];

        foreach ($command->getDefinition()->getOptions() as $option) {
            $result[] = [
                'name' => $option->getName(),
                'description' => $option->getDescription()
            ];
        }

        return $result;
    }
}

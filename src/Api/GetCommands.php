<?php

namespace HotRodCli\Api;

use HotRodCli\AppContainer;
use HotRodCli\Commands\BaseCommand;

class GetCommands
{
    protected $container;

    public function __construct(AppContainer $appContainer)
    {
        $this->container = $appContainer;
    }

    public function __invoke($args = [])
    {
        $result = [];

        foreach ($this->container->get('commands') as $code => $className) {
            $command = $this->container->resolve($className);

            $result[$code] = [
                'name' => $command->getName(),
                'description' => $command->getDescription(),
                'help' => $command->getHelp(),
                'arguments' => $this->constructArguments($command),
                'options' => $this->constructOptions($command)
            ];
        }

        return json_encode($result);
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

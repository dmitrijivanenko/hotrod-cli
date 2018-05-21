<?php

namespace HotRodCli\Api;

use HotRodCli\AppContainer;
use HotRodCli\Commands\Classes\CreateControllerCommand;
use PHPUnit\Framework\TestCase;

class GetCommandTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_commands_arguments_and_options()
    {
        $contains = '"name":"create:controller","description":"Creates a new controller"';
        $container = new AppContainer();

        $commands = [
            'create:controller' => CreateControllerCommand::class
        ];

        $container->bind('commands', $commands);

        $args = ['command' => 'create:controller'];

        $getCommand = new GetCommand($container);

        $this->assertContains($contains, $getCommand($args));
    }
}

<?php

namespace HotRodCli\Api;

use HotRodCli\AppContainer;
use PHPUnit\Framework\TestCase;
use HotRodCli\Commands\Classes\CreateControllerCommand;

class GetCommandsTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_all_registered_commands()
    {
        $commands = [
            'create:controller' => CreateControllerCommand::class
        ];

        $container = new AppContainer();
        $container->bind('commands', $commands);

        $api = new GetCommands($container);

        $this->assertContains('create:controller', $api());
    }
}

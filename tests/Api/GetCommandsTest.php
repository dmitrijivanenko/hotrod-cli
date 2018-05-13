<?php

namespace HotRodCli\Api;

use HotRodCli\AppContainer;
use PHPUnit\Framework\TestCase;

class GetCommandsTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_all_registered_commands()
    {
        $commands = [
            'test-command' => null
        ];

        $container = new AppContainer();
        $container->bind('commands', $commands);

        $api = new GetCommands($container);

        $this->assertEquals('["test-command"]', $api());
    }
}

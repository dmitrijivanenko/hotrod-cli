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
        $result = '{"arguments":[{"name":"namespace","description":"What is the namespace on the module"},{"name":"route","description":"route pattern is \"route_name\/controller\/action\""}],"options":[{"name":"no-block","description":"Do you need a block?"},{"name":"no-layout","description":"Do you need a layout file?"},{"name":"no-routes","description":"Do you need a routes file?"},{"name":"no-template","description":"Do you need a template file?"},{"name":"admin","description":"Is this template for admin part?"}]}';
        $container = new AppContainer();

        $commands = [
            'create:controller' => CreateControllerCommand::class
        ];

        $container->bind('commands', $commands);

        $args = ['command' => 'create:controller'];

        $getCommand = new GetCommand($container);

        $this->assertEquals($result, $getCommand($args));
    }
}

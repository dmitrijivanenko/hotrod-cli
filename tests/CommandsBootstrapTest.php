<?php

namespace HotRodCli;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

class CommandsBootstrapTest extends TestCase
{
    /**
     * @test
     */
    public function it_registers_all_commands_to_the_application()
    {
        $command = $this->prophesize(Command::class)->reveal();
        $appContainer = $this->prophesize(AppContainer::class);

        $bootstrap = new CommandsBootstrap(['test'], $appContainer->reveal());

        $app = $this->prophesize(Application::class);
        $appContainer->resolve()->shouldBeCalled()->withArguments(['test'])->willReturn($command);

        $app->add()->shouldBeCalled()->withArguments([$command]);

        $bootstrap->register($app->reveal());
    }
}

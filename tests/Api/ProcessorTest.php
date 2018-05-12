<?php

namespace HotRodCli\Api;

use HotRodCli\Commands\Module\CreateCommand;
use PHPUnit\Framework\TestCase;
use HotRodCli\AppContainer;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class ProcessorTest extends TestCase
{
    /**
     * @test
     */
    public function it_runs_the_command_and_returns_the_output()
    {
        $container = $this->prophesize(AppContainer::class);
        $output = $this->prophesize(BufferedOutput::class);
        $container->resolve()->shouldBeCalled()->withArguments(['commands'])->willReturn(['module:create' => CreateCommand::class]);
        $container->resolve()->shouldBeCalled()->withArguments([BufferedOutput::class])->willReturn($output->reveal());
        $processor = new Processor($container->reveal());

        $this->assertInstanceOf(Processor::class, $processor);

        $request = $this->prophesize(ServerRequestInterface::class);
        $request->getParsedBody()->shouldBeCalled()->willReturn([
            'arguments' => [
                "namespace" => "Testing_Test"
            ]
        ]);

        $consoleApp = $this->prophesize(Application::class);
        $container->resolve()->shouldBeCalled()->withArguments([Application::class])->willReturn($consoleApp->reveal());

        $command = $this->prophesize(CreateCommand::class);
        $consoleApp->find()->shouldBeCalled()->withArguments(['module:create'])->willReturn($command->reveal());

        $arrayInput = $this->prophesize(ArrayInput::class);
        $container->resolve()->shouldBeCalled()->withArguments([ArrayInput::class, ["namespace" => "Testing_Test"]])->willReturn($arrayInput->reveal());

        $command->run()->shouldBeCalled()->withArguments([$arrayInput->reveal(), $output->reveal()])->willReturn(0);

        $output->fetch()->shouldBeCalled()->willReturn('test');

        $this->assertEquals('{"success":true,"output":"test"}', $processor($request->reveal(), ['command' => 'module:create']));

        $this->assertEquals(null, $processor->getCommand('create:test'));
    }
}

<?php

namespace HotRodCli\Processors;

use HotRodCli\AppContainer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class ProcessTemplateFileTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_call_the_create_command()
    {
        $appContainer = $this->prophesize(AppContainer::class);

        $input = $this->prophesize(InputInterface::class);
        $output = $this->prophesize(OutputInterface::class);
        $application = $this->prophesize(Application::class);
        $command = $this->prophesize(Command::class);

        $input->getOption()->shouldBeCalled()->withArguments(['no-template'])->willReturn(false);
        $input->getOption()->shouldBeCalled()->withArguments(['admin'])->willReturn(true);
        $input->getArgument()->shouldBeCalled()->withArguments(['route'])->willReturn('test/test/test');
        $input->getArgument()->shouldBeCalled()->withArguments(['namespace'])->willReturn('Test_Test');
        $appContainer->resolve()->shouldBeCalled()->withArguments([Application::class])->willReturn($application->reveal());
        $application->find('create:template')->shouldBeCalled()->willReturn($command->reveal());

        $processor = new ProcessTemplateFile($appContainer->reveal());

        $inputs = array(
            'namespace' => 'Test_Test',
            'template-name' => 'test',
            '--admin' => true
        );

        $greetInput = new ArrayInput($inputs);

        $command->run()->shouldBeCalled()->withArguments([$greetInput, $output->reveal()])->willReturn('test');

        $processor($input->reveal(), $output->reveal());
    }
}

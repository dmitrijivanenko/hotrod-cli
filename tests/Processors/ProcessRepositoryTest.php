<?php

namespace HotRodCli\Processors;

use HotRodCli\AppContainer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class ProcessRepositoryTest extends TestCase
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

        $input->getArgument()->shouldBeCalled()->withArguments(['name'])->willReturn('Test');
        $input->getArgument()->shouldBeCalled()->withArguments(['namespace'])->willReturn('Test_Test');
        $appContainer->resolve()->shouldBeCalled()->withArguments([Application::class])->willReturn($application->reveal());
        $application->find('create:repository')->shouldBeCalled()->willReturn($command->reveal());

        $processor = new ProcessRepository($appContainer->reveal());

        $inputs = array(
            'namespace' => 'Test_Test',
            'interface-name' => 'TestRepositoryInterface',
            'model-class' => 'Test\\Test\\Model\\Test'
        );

        $greetInput = new ArrayInput($inputs);

        $command->run()->shouldBeCalled()->withArguments([$greetInput, $output->reveal()])->willReturn('test');

        $processor($input->reveal(), $output->reveal());
    }
}

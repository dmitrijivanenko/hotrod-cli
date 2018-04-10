<?php

namespace HotRodCli\Commands;

use PHPUnit\Framework\TestCase;
use HotRodCli\AppContainer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BaseCommandTest extends TestCase
{
    protected $command;

    public function setUp()
    {
        $appContainer = $this->prophesize(AppContainer::class);
        $this->command = new BaseCommand($appContainer->reveal());
    }

    /**
     * @test
     */
    public function it_can_run_processors()
    {
        $input = $this->prophesize(InputInterface::class);
        $output = $this->prophesize(OutputInterface::class);
        $result = $this->command->runProcessors($input->reveal(), $output->reveal());
        $this->assertInstanceOf(BaseCommand::class, $result);
    }
}
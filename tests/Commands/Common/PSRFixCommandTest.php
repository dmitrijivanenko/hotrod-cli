<?php

namespace HotRodCli\Commands\Common;

use HotRodCli\AppContainer;
use HotRodCli\Container;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

function exec()
{
    return 'test';
}

class PSRFixCommandTest extends TestCase
{
    protected $command;

    public function setUp()
    {
        $appContainer = new AppContainer();
        $appContainer->bind('app_dir', __DIR__ . '/../../');
        $this->command = new PSRFixCommand($appContainer);
    }

    /**
     * @test
     */
    public function it_configures_right()
    {
        $this->assertEquals('psr:fix', $this->command->getName());
    }

    /**
     * @test
     */
    public function it_runs_exec()
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([]);

        $this->assertContains('PSR2', $commandTester->getDisplay());
    }
}

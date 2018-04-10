<?php

namespace HotRodCli\Jobs\Module;

use HotRodCli\Commands\Module\CreateCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Output\Output;
use HotRodCli\AppContainer;
use Symfony\Component\Console\Application;

class IsModuleExistsTest extends TestCase
{
    /**
     * @test
     */
    public function it_runs_the_create_module_comannd_if_module_doesn_ixest()
    {
        $finder = $this->prophesize(Finder::class);
        $filesystem = $this->prophesize(Filesystem::class);
        $appContainer = $this->prophesize(AppContainer::class);
        $output = $this->prophesize(Output::class);
        $consoleApp = $this->prophesize(Application::class);
        $createModule = $this->prophesize(CreateCommand::class);

        $job = new IsModuleExists($filesystem->reveal(), $appContainer->reveal());

        $filesystem->exists()->shouldBeCalled()->withArguments(['/app/code/test/test'])->willReturn(false);
        $appContainer->get()->shouldBeCalled()->withArguments(['app_dir'])->willReturn('');
        $appContainer->resolve()->shouldBeCalled()->withArguments([Application::class])->willReturn($consoleApp->reveal());
        $consoleApp->find()->shouldBeCalled()->withArguments(['module:create'])->willReturn($createModule->reveal());

        $job->handle('test_test', $output->reveal());
    }
}
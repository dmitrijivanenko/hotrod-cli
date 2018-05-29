<?php

namespace HotRodCli\Jobs\Js;

use HotRodCli\AppContainer;
use \PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class AddMageInitTest extends TestCase
{
    /**
     * @test
     */
    public function it_adds_mage_init()
    {
        $appContainer = $this->prophesize(AppContainer::class);
        $fileSystem = $this->prophesize(Filesystem::class);
        $appContainer->resolve()->shouldBeCalled()->withArguments([Filesystem::class])->willReturn($fileSystem->reveal());


        $job = new AddMageInit($appContainer->reveal());

        $fileSystem->exists()->withArguments(['filename'])->shouldBeCalled()->willReturn(true);
        $job->handle('filename', [
            'name' => 'filename',
            'bind' => '*'
        ]);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_file_not_exist()
    {
        $appContainer = $this->prophesize(AppContainer::class);
        $fileSystem = $this->prophesize(Filesystem::class);
        $appContainer->resolve()->shouldBeCalled()->withArguments([Filesystem::class])->willReturn($fileSystem->reveal());


        $job = new AddMageInit($appContainer->reveal());

        $fileSystem->exists()->withArguments(['filename'])->shouldBeCalled()->willReturn(false);
        $this->expectExceptionMessage('no such file');
        $job->handle('filename', [
            'name' => 'filename',
            'bind' => '*'
        ]);
    }
}

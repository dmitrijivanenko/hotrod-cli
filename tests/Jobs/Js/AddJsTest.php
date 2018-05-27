<?php

namespace HotRodCli\Jobs\Js;

use function foo\func;
use HotRodCli\AppContainer;
use \PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

function file_get_contents($file)
{
    return 'var config = {
            "*": {}
        };';
}

function file_put_contents($file, $content)
{
    return true;
}

class AddJsTest extends TestCase
{
    /**
     * @test
     */
    public function it_adds_js_map_to_requirejs_config_file()
    {
        $appContainer = $this->prophesize(AppContainer::class);
        $fileSystem = $this->prophesize(Filesystem::class);
        $appContainer->resolve()->shouldBeCalled()->withArguments([Filesystem::class])->willReturn($fileSystem->reveal());


        $job = new AddJs($appContainer->reveal());

        $fileSystem->exists()->withArguments(['filename'])->shouldBeCalled()->willReturn(true);
        $job->handle('filename', 'jsname', 'jsfile');
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_file_not_exist()
    {
        $appContainer = $this->prophesize(AppContainer::class);
        $fileSystem = $this->prophesize(Filesystem::class);
        $appContainer->resolve()->shouldBeCalled()->withArguments([Filesystem::class])->willReturn($fileSystem->reveal());


        $job = new AddJs($appContainer->reveal());

        $fileSystem->exists()->withArguments(['filename'])->shouldBeCalled()->willReturn(false);
        $this->expectExceptionMessage('no such file');
        $job->handle('filename', 'jsname', 'jsfile');
    }
}

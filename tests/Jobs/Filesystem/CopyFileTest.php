<?php

namespace HotRodCli\Jobs\Filesystem;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

class CopyFileTest extends TestCase
{
    /**
     * @test
     */
    public function it_throws_the_exception_when_file_already_exists()
    {
        $finder = $this->prophesize(Finder::class);
        $filesystem = $this->prophesize(Filesystem::class);

        $job = new CopyFile($finder->reveal(), $filesystem->reveal());

        $filesystem->exists()->shouldBeCalled()->withArguments(['test'])->willReturn(true);

        $this->expectExceptionMessage('Such file already exists');

        $job->handle('test', 'test');
    }

    /**
     * @test
     */
    public function it_throws_the_exception_when_filesystem_cant_proccess_copy_file()
    {
        $finder = $this->prophesize(Finder::class);
        $filesystem = $this->prophesize(Filesystem::class);

        $job = new CopyFile($finder->reveal(), $filesystem->reveal());

        $filesystem->exists()->shouldBeCalled()->withArguments(['test'])->willReturn(false);
        $filesystem->copy()->shouldBeCalled()->withArguments(['test', 'test'])->willThrow(new IOException('ERROR'));

        $this->expectExceptionMessage('ERROR');

        $job->handle('test', 'test');
    }
}

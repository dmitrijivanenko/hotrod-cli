<?php

namespace HotRodCli\Jobs\Filesystem;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

class CopyFilesTest extends TestCase
{
    /**
     * @test
     */
    public function it_throws_an_exception_when_filesystem_can_not_copy_files()
    {
        $finder = $this->prophesize(Finder::class);
        $filesystem = $this->prophesize(Filesystem::class);

        $job = new CopyFiles($finder->reveal(), $filesystem->reveal());

        $filesystem->exists()
            ->shouldBeCalled()
            ->withArguments(['sometest'])
            ->willReturn(false);

        $filesystem->mirror()
            ->shouldBeCalled()
            ->withArguments(['sometest', 'sometest'])
            ->willThrow(new IOException('ERROR'));

        $this->expectExceptionMessage('ERROR');

        $job->handle('sometest', 'sometest');
    }
}

<?php

namespace HotRodCli\Jobs\Module;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Filesystem\Exception\IOException;

class ReplaceTextTest extends TestCase
{
    /**
     * @test
     */
    public function it_throws_an_exception_when_there_is_no_directory()
    {
        $finder = $this->prophesize(Finder::class);
        $filesystem = $this->prophesize(Filesystem::class);

        $job = new ReplaceText($finder->reveal(), $filesystem->reveal());

        $filesystem->exists()
            ->shouldBeCalled()
            ->withArguments(['./somedir'])
            ->willReturn(false);


        $this->expectExceptionMessage('There is no such directory');

        $job->handle('test', 'test', './somedir');
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_file_system_fails()
    {
        $finder = $this->prophesize(Finder::class);
        $filesystem = $this->prophesize(Filesystem::class);
        $file = $this->prophesize(SplFileInfo::class);

        $job = new ReplaceText($finder->reveal(), $filesystem->reveal());

        $filesystem->exists()
            ->shouldBeCalled()
            ->withArguments(['./somedir'])
            ->willReturn(true);

        $finder->files()->shouldBeCalled()->willReturn($finder->reveal());

        $finder->in()->shouldBeCalled()->withArguments(['./somedir'])->willReturn($finder->reveal());

        $finder->contains()->shouldBeCalled()->withArguments(['test'])->willReturn([$file]);

        $file->getContents()->shouldBeCalled()->willReturn('tes test');

        $file->getRealPath()->shouldBeCalled()->willReturn('/test/test');

        $filesystem->dumpFile()->shouldBeCalled()->withArguments(['/test/test', 'tes test'])->willThrow(new IOException('ERROR'));

        $this->expectExceptionMessage('ERROR');

        $job->handle('test', 'test', './somedir');
    }
}

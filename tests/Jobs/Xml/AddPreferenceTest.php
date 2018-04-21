<?php

namespace HotRodCli\Jobs\Xml;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class AddPreferenceTest extends TestCase
{
    /**
     * @test
     */
    public function it_throws_an_exception_if_file_doesnt_exist()
    {
        $fileSystem = $this->prophesize(Filesystem::class);
        $job = new AddPreference($fileSystem->reveal());

        $fileSystem->exists()->shouldBeCalled()->withArguments(['test'])->willReturn(false);

        $this->expectExceptionMessage('no such file');

        $job->handle('test', []);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_xml_can_not_be_parsed()
    {
        $fileSystem = $this->prophesize(Filesystem::class);
        $job = new AddPreference($fileSystem->reveal());

        $fileSystem->exists()->shouldBeCalled()->withArguments(['test'])->willReturn(true);

        $this->expectExceptionMessage('String could not be parsed as XML');

        $job->handle('test', []);
    }
}

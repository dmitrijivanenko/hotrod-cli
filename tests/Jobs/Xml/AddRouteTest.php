<?php

namespace HotRodCli\Jobs\Xml;

use HotRodCli\Jobs\Xml\AddRoute;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

function file_get_contents($test)
{
    if ($test == 'test') {
        return 'test-issue';
    } else {
        return '<?xml version="1.0" ?>
                    <config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:App/etc/routes.xsd">
                        <router id="standard">
                            <route frontName="test2" id="test2">
                                <module name="Testing_Test"/>
                            </route>
                        </router>
                    </config>';
    }
}

class AddRouteTest extends TestCase
{
    /**
     * @test
     */
    public function it_throws_an_exception_if_file_doesnt_exist()
    {
        $fileSystem = $this->prophesize(Filesystem::class);
        $job = new AddRoute($fileSystem->reveal());

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
        $job = new AddRoute($fileSystem->reveal());

        $fileSystem->exists()->shouldBeCalled()->withArguments(['test'])->willReturn(true);

        $this->expectExceptionMessage('String could not be parsed as XML');

        $job->handle('test', []);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_such_route_exists()
    {
        $fileSystem = $this->prophesize(Filesystem::class);
        $job = new AddRoute($fileSystem->reveal());

        $fileSystem->exists()->shouldBeCalled()->withArguments(['test2'])->willReturn(true);

        $this->expectExceptionMessage('route already exists');

        $job->handle('test2', ['frontName' => 'test2']);
    }
}

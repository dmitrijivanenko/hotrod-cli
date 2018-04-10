<?php

namespace HotRodCli\Commands\Xml;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use HotRodCli\AppContainer;
use Symfony\Component\Console\Tester\CommandTester;

class CreateRouteCommandTest extends TestCase
{
    protected $command;

    protected $appContainer;

    public function setUp()
    {
        $filesystem = new Filesystem();

        $appContainer = new AppContainer();
        $appContainer->bind(Filesystem::class, $filesystem);
        $appContainer->bind(Finder::class, new Finder());
        $appContainer->bind('test_dir', __DIR__ . '/../../app/code');
        $appContainer->bind('app_dir', __DIR__ . '/../..');
        $appContainer->bind('resource_dir', __DIR__ . '/../../../resources');
        $appContainer->bind(AppContainer::class, $appContainer);

        $this->appContainer = $appContainer;

        $this->command = $appContainer->resolve(CreateRouteCommand::class);

        if ($filesystem->exists(__DIR__ . '/../../app/code')) {
            $filesystem->remove([__DIR__ . '/../../app']);
        }
    }

    /**
     * @test
     */
    public function it_configured_right()
    {
        $this->assertEquals('create:route', $this->command->getName());
    }

    /**
     * @test
     */
    public function it_creates_or_adds_a_route()
    {
        $filesystem = $this->appContainer->resolve(Filesystem::class);

        if (!$filesystem->exists($this->appContainer->get('test_dir') . '/Testing/Test')) {
            $filesystem->mkdir($this->appContainer->get('test_dir') . '/Testing/Test');
        }

        $tester = new CommandTester($this->command);

        $tester->execute([
            'namespace' => 'Testing_Test',
            'route-name' => 'test-test'
        ]);

        $files = Finder::create()->files()->in(__DIR__ . '/../../app')
            ->contains('test-test');

        $this->assertEquals(1, count($files));

        $tester->execute([
            'namespace' => 'Testing_Test',
            'route-name' => 'test2-test2'
        ]);

        $files = Finder::create()->files()->in(__DIR__ . '/../../app')
            ->contains('test2-test2');

        $this->assertEquals(1, count($files));

        $filesystem->remove([__DIR__ . '/../../app']);
    }
}
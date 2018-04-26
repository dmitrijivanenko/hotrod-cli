<?php

namespace HotRodCli\Commands\Classes;

use HotRodCli\AppContainer;
use Symfony\Component\Finder\Finder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

class CreateObserverCommandTest extends TestCase
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

        $this->command = $appContainer->resolve(CreateObserverCommand::class);

        if ($filesystem->exists(__DIR__ . '/../../app/code')) {
            $filesystem->remove([__DIR__ . '/../../app']);
        }
    }

    /**
     * @test
     */
    public function it_configured_right()
    {
        $this->assertEquals('create:observer', $this->command->getName());
    }

    /**
     * @test
     */
    public function it_creates_a_new_resource_model_class()
    {
        $filesystem = $this->appContainer->resolve(Filesystem::class);

        if (!$filesystem->exists($this->appContainer->get('test_dir') . '/Testing/Test')) {
            $filesystem->mkdir($this->appContainer->get('test_dir') . '/Testing/Test');
        }

        $tester = new CommandTester($this->command);

        $tester->execute([
            'namespace' => 'Testing_Test',
            'event' => 'test_event',
            'observer' => 'TestObserver'
        ]);

        $files = Finder::create()->files()->in(__DIR__ . '/../../app')
            ->contains('TestObserver');

        $this->assertEquals(2, count($files));

        $tester->execute([
            'namespace' => 'Testing_Test',
            'event' => 'test_event',
            'observer' => 'TestObserver'
        ]);

        $this->assertContains('Such file already exists', $tester->getDisplay());
    }
}
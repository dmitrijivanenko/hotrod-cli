<?php

namespace HotRodCli\Commands\Classes;

use PHPUnit\Framework\TestCase;
use HotRodCli\AppContainer;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Tester\CommandTester;

class CreateBlockCommandTest extends TestCase
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

        $this->command = $appContainer->resolve(CreateBlockCommand::class);

        if ($filesystem->exists(__DIR__ . '/../../app/code')) {
            $filesystem->remove([__DIR__ . '/../../app']);
        }
    }

    /**
     * @test
     */
    public function it_configured_right()
    {
        $this->assertEquals('create:block', $this->command->getName());
    }

    /**
     * @test
     */
    public function it_should_create_a_block()
    {
        $filesystem = $this->appContainer->resolve(Filesystem::class);

        if (!$filesystem->exists($this->appContainer->get('test_dir') . '/Testing/Test')) {
            $filesystem->mkdir($this->appContainer->get('test_dir') . '/Testing/Test');
        }

        $tester = new CommandTester($this->command);

        $tester->execute([
            'namespace' => 'Testing_Test',
            'blockname' => 'TestBlock'
        ]);

        $files = Finder::create()->files()->in(__DIR__ . '/../../app')
            ->contains('TestBlock');

        $this->assertEquals(1, count($files));

        $tester->execute([
            'namespace' => 'Testing_Tests',
            'blockname' => 'TestHelper'
        ]);

        $this->assertContains('no commands', $tester->getDisplay());
    }
}
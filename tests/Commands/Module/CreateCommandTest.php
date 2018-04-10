<?php

namespace HotRodCli\Commands\Module;

use HotRodCli\AppContainer;
use Symfony\Component\Finder\Finder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

class CreateCommandTest extends TestCase
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

        $this->command = $appContainer->resolve(CreateCommand::class);

        if ($filesystem->exists(__DIR__ . '/../../app/code')) {
            $filesystem->remove([__DIR__ . '/../../app']);
        }
    }

    /**
     * @test
     */
    public function it_configures_right()
    {
        $this->assertEquals('module:create', $this->command->getName());
    }

    /**
     * @test
     */
    public function it_creates_a_new_module_with_replaced_namespace()
    {
        $filesystem = $this->appContainer->resolve(Filesystem::class);

        if (!$filesystem->exists($this->appContainer->get('test_dir'))) {
            $filesystem->mkdir($this->appContainer->get('test_dir'));
        }

        $tester = new CommandTester($this->command);

        $tester->execute([
            'namespace' => 'Testing_Test'
        ]);

        $files = Finder::create()->files()->in(__DIR__ . '/../../app')
            ->name('*.php')
            ->contains('Testing_Test');

        $this->assertEquals(1, count($files));

        $files = Finder::create()->files()->in(__DIR__ . '/../../app')
            ->name('*.xml')
            ->contains('Testing_Test');

        $this->assertEquals(1, count($files));

        $tester->execute([
            'namespace' => 'Testing_Test'
        ]);

        $this->assertContains('exists', $tester->getDisplay());

        $filesystem->remove([__DIR__ . '/../../app']);
    }
}

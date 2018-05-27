<?php

namespace HotRodCli\Commands\Frontend;

use HotRodCli\Commands\BaseCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Filesystem\Filesystem;
use HotRodCli\AppContainer;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Tester\CommandTester;

class CreateScriptCommandTest extends TestCase
{
    /** @var  BaseCommand */
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

        $application = new Application();

        $application->add($appContainer->resolve(CreateRequireJsCommand::class));

        $appContainer->bind(Application::class, $application);

        $this->appContainer = $appContainer;

        $this->command = $appContainer->resolve(CreateScriptCommand::class);

        if ($filesystem->exists(__DIR__ . '/../../app/code')) {
            $filesystem->remove([__DIR__ . '/../../app']);
        }
    }

    /**
     * @test
     */
    public function it_configured_right()
    {
        $this->assertEquals('create:js-script', $this->command->getName());
    }

    /**
     * @test
     */
    public function it_creates_a_new_script_file_and_adds_it_to_requirejs_config()
    {
        $filesystem = $this->appContainer->resolve(Filesystem::class);

        if (!$filesystem->exists($this->appContainer->get('test_dir') . '/Testing/Test')) {
            $filesystem->mkdir($this->appContainer->get('test_dir') . '/Testing/Test');
        }

        $tester = new CommandTester($this->command);

        $tester->execute([
            'namespace' => 'Testing_Test',
            'script-name' => 'test-script'
        ]);

        $tester->execute([
            'namespace' => 'Testing_Test',
            'script-name' => 'test-script'
        ]);

        $this->assertContains('Such file already exists', $tester->getDisplay());
    }
}

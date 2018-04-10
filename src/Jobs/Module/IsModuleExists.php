<?php

namespace HotRodCli\Jobs\Module;

use Symfony\Component\Console\Application;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Input\ArrayInput;
use HotRodCli\AppContainer;
use Symfony\Component\Console\Output\OutputInterface;

class IsModuleExists
{
    protected $filesystem;

    protected $appContainer;

    public function __construct(Filesystem $filesystem, AppContainer $appContainer)
    {
        $this->filesystem = $filesystem;
        $this->appContainer = $appContainer;
    }

    public function handle(string $namespace, OutputInterface $output, $createNew = true)
    {
        $namespaceExp = explode('_', $namespace);

        $isExists = $this->filesystem->exists($this->appContainer->get('app_dir') . '/app/code/' . $namespaceExp[0] . '/' . $namespaceExp[1]);

        if (!$isExists && $createNew) {
            /** @var Application $application */
            $application = $this->appContainer->resolve(Application::class);
            $command = $application->find('module:create');

            $inputs = array(
                'namespace' => $namespace
            );

            $greetInput = new ArrayInput($inputs);
            $command->run($greetInput, $output);

            return true;
        }

        return $isExists;
    }
}

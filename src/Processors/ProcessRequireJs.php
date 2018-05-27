<?php

namespace HotRodCli\Processors;

use HotRodCli\AppContainer;
use HotRodCli\Jobs\Filesystem\CopyFile;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessRequireJs
{
    protected $appContainer;

    public function __construct(AppContainer $appContainer)
    {
        $this->appContainer = $appContainer;
    }

    public function __invoke(InputInterface $input, OutputInterface $output)
    {
        $app = $this->appContainer;

        /** @var Application $application */
        $application = $app->resolve(Application::class);
        $command = $application->find('create:requirejs-config');

        $inputs = [
            'namespace' => $input->getArgument('namespace')
        ];

        if ($input->getOption('admin')) {
            $inputs['--admin'] = 'true';
        }

        $greetInput = new ArrayInput($inputs);
        $command->run($greetInput, $output);
    }
}

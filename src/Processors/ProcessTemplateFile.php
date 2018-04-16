<?php

namespace HotRodCli\Processors;

use HotRodCli\AppContainer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Application;

class ProcessTemplateFile
{
    protected $appContainer;

    public function __construct(AppContainer $appContainer)
    {
        $this->appContainer = $appContainer;
    }

    public function __invoke(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('no-template')) {
            $controller = explode('/', $input->getArgument('route'));
            $app = $this->appContainer;

            /** @var Application $application */
            $application = $app->resolve(Application::class);
            $command = $application->find('create:template');

            $inputs = array(
                'namespace' => $input->getArgument('namespace'),
                'template-name' => $controller[2]
            );

            if ($input->getOption('admin')) {
                $inputs['--admin'] = true;
            }

            $greetInput = new ArrayInput($inputs);
            $command->run($greetInput, $output);
        }
    }
}

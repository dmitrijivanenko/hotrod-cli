<?php

namespace HotRodCli\Processors;

use HotRodCli\AppContainer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Application;

class ProcessLayoutFile
{
    protected $appContainer;

    public function __construct(AppContainer $appContainer)
    {
        $this->appContainer = $appContainer;
    }

    public function __invoke(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('no-layout')) {
            $namespace = explode('_', $input->getArgument('namespace'));
            $controller = explode('/', $input->getArgument('route'));
            $app = $this->appContainer;

            /** @var Application $application */
            $application = $app->resolve(Application::class);
            $command = $application->find('create:layout');

            $inputs = array(
                'namespace' => $input->getArgument('namespace'),
                'layout-name' => strtolower($controller[0]) . '_' . strtolower($controller[1]) . '_' . strtolower($controller[2]),
                'layout-file-name' => strtolower($controller[0]) . '_' . strtolower($controller[1]) . '_' . strtolower($controller[2]),
                'block-class' => $namespace[0] . '\\' . $namespace[1] . '\\Block\\' . ucwords($controller[1]),
                'template' => $controller[2]
            );

            if ($input->getOption('admin')) {
                $inputs['--admin'] = true;
            }

            $greetInput = new ArrayInput($inputs);
            $command->run($greetInput, $output);
        }
    }
}

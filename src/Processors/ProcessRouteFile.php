<?php

namespace HotRodCli\Processors;

use HotRodCli\AppContainer;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class ProcessRouteFile
{
    protected $appContainer;

    public function __construct(AppContainer $appContainer)
    {
        $this->appContainer = $appContainer;
    }

    public function __invoke(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('no-routes')) {
            $controller = explode('/', $input->getArgument('route'));
            $app = $this->appContainer;

            /** @var Application $application */
            $application = $app->resolve(Application::class);
            $command = $application->find('create:route');

            $inputs = array(
                'namespace' => $input->getArgument('namespace'),
                'route-name' => strtolower($controller[0]),
            );

            if ($input->getOption('admin')) {
                $inputs['--admin'] = true;
            }

            $greetInput = new ArrayInput($inputs);
            $command->run($greetInput, $output);
        }
    }
}

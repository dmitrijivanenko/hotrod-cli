<?php

namespace HotRodCli\Processors;

use HotRodCli\AppContainer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class ProcessBlockFile
{
    protected $appContainer;

    public function __construct(AppContainer $appContainer)
    {
        $this->appContainer = $appContainer;
    }

    public function __invoke(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('no-block')) {
            $controller = explode('/', $input->getArgument('route'));
            $app = $this->appContainer;

            /** @var Application $application */
            $application = $app->resolve(Application::class);
            $command = $application->find('create:block');

            $inputs = array(
                'namespace' => $input->getArgument('namespace'),
                'blockname' =>$controller[1]
            );

            if ($input->getOption('admin')) {
                $inputs['--admin'] = 'true';
            }

            //$greetInput = new ArrayInput($inputs);
            $greetInput = $app->resolve(ArrayInput::class, $inputs);
            $command->run($greetInput, $output);
        }
    }
}

<?php

namespace HotRodCli\Processors;

use HotRodCli\AppContainer;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class ProcessRepository
{
    protected $appContainer;

    public function __construct(AppContainer $appContainer)
    {
        $this->appContainer = $appContainer;
    }

    public function __invoke(InputInterface $input, OutputInterface $output)
    {
        $namespace = explode('_', $input->getArgument('namespace'));
        $app = $this->appContainer;

        /** @var Application $application */
        $application = $app->resolve(Application::class);
        $command = $application->find('create:repository');

        $inputs = array(
            'namespace' => $input->getArgument('namespace'),
            'interface-name' => $input->getArgument('name') . 'RepositoryInterface',
            'model-class' => $namespace[0] . '\\' . $namespace[1] . '\\Model\\' . $input->getArgument('name')
        );

        $greetInput = new ArrayInput($inputs);
        $command->run($greetInput, $output);
    }
}

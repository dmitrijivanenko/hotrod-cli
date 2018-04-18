<?php

namespace HotRodCli\Processors;

use HotRodCli\AppContainer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Application;

class ProcessResourceModelFile
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
        $command = $application->find('create:resource-model');

        $inputs = array(
            'namespace' => $input->getArgument('namespace'),
            'name' => $input->getArgument('name'),
            'table-name' => $input->getArgument('table-name'),
            'id-field' => $input->getArgument('id-field')
        );

        $greetInput = new ArrayInput($inputs);
        $command->run($greetInput, $output);
    }
}
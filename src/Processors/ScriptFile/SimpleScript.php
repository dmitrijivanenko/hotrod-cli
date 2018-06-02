<?php

namespace HotRodCli\Processors\ScriptFile;

use HotRodCli\AppContainer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use HotRodCli\Jobs\Js\AddJs;
use HotRodCli\Jobs\Filesystem\CopyFile;

class SimpleScript
{
    protected $container;

    public function __construct(AppContainer $appContainer)
    {
        $this->container = $appContainer;
    }

    public function __invoke(InputInterface $input, OutputInterface $output)
    {
        $namespace = explode('_', $input->getArgument('namespace'));
        $app = $this->container;
        $scope = $input->getOption('admin') ? 'adminhtml' : 'frontend';

        $this->container->resolve(AddJs::class)->handle(
            $app->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/view/' . $scope . '/requirejs-config.js',
            $input->getArgument('script-name'),
            $input->getArgument('namespace') . '/js/' . $input->getArgument('script-name')
        );

        $this->container->resolve(CopyFile::class)->handle(
            $app->get('resource_dir') . '/frontend/simple-js.js',
            $app->get('app_dir') . '/app/code/' . $namespace[0] . '/'
            . $namespace[1] . '/view/' . $scope . '/web/js/' . $input->getArgument('script-name') . '.js'
        );

        $output->writeln('<info>' . '/app/code/' . $namespace[0] . '/'
            . $namespace[1] . '/view/' . $scope . '/web/js/' . $input->getArgument('script-name') . '.js successfully generated</info>');
    }
}

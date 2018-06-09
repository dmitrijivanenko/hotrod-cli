<?php

namespace HotRodCli\Processors\ScriptFile;

use HotRodCli\AppContainer;
use HotRodCli\Jobs\Module\ReplaceText;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use HotRodCli\Jobs\Js\AddJs;
use HotRodCli\Jobs\Filesystem\CopyFile;

class WidgetScript
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
        $scope = $this->getScope($input);

        $app->resolve(AddJs::class)->handle(
            $app->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/view/' . $scope . '/requirejs-config.js',
            $input->getArgument('script-name'),
            $input->getArgument('namespace') . '/js/' . $input->getArgument('script-name')
        );

        $app->resolve(CopyFile::class)->handle($app->get('resource_dir') . '/frontend/widget.js',
            $app->get('app_dir') . '/app/code/' . $namespace[0] . '/'
            . $namespace[1] . '/view/' . $scope . '/web/js/' . $input->getArgument('script-name') . '.js'
        );

        $app->resolve(ReplaceText::class)->handle('{{widget-name}}', $input->getArgument('script-name'),
            $app->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/view/'
        );

        $output->writeln('<info>Widget Script ' . '/app/code/' . $namespace[0] . '/'
            . $namespace[1] . '/view/' . $scope . '/web/js/' . $input->getArgument('script-name') . '.js successfully generated</info>');
    }

    public function getScope(InputInterface $input)
    {
        return $input->getOption('admin') ? 'adminhtml' : 'frontend';
    }
}

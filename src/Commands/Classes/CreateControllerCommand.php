<?php

namespace HotRodCli\Commands\Classes;

use HotRodCli\Commands\BaseCommand;
use HotRodCli\Commands\Xml\CreateLayoutCommand;
use HotRodCli\Jobs\Filesystem\CopyFile;
use HotRodCli\Jobs\Module\IsModuleExists;
use HotRodCli\Jobs\Module\ReplaceText;
use HotRodCli\Jobs\Xml\AddRoute;
use HotRodCli\Processors\ProcessBlockFile;
use HotRodCli\Processors\ProcessLayoutFile;
use HotRodCli\Processors\ProcessRouteFile;
use HotRodCli\Processors\ProcessTemplateFile;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateControllerCommand extends BaseCommand
{
    protected $jobs = [
        CopyFile::class => null,
        IsModuleExists::class => null,
        ReplaceText::class => null,
        AddRoute::class => null,
        CreateLayoutCommand::class => null
    ];

    protected $processors = [
        ProcessBlockFile::class => null,
        ProcessLayoutFile::class => null,
        ProcessRouteFile::class => null,
        ProcessTemplateFile::class => null
    ];

    protected $configs = [
        'arguments' => [
            [
                'name' => 'namespace',
                'mode' => InputArgument::REQUIRED,
                'description' => 'What is the namespace on the module'
            ],
            [
                'name' => 'route',
                'mode' => InputArgument::REQUIRED,
                'description' => 'route pattern is "route_name/controller/action"'
            ]
        ],
        'options' => [
            [
                'name' => 'no-block',
                'shortcut' => null,
                'mode' => InputArgument::OPTIONAL,
                'description' => 'Do you need a block?'
            ],
            [
                'name' => 'no-layout',
                'shortcut' => null,
                'mode' => InputArgument::OPTIONAL,
                'description' => 'Do you need a layout file?'
            ],
            [
                'name' => 'no-routes',
                'shortcut' => null,
                'mode' => InputArgument::OPTIONAL,
                'description' => 'Do you need a routes file?'
            ],
            [
                'name' => 'no-template',
                'shortcut' => null,
                'mode' => InputArgument::OPTIONAL,
                'description' => 'Do you need a template file?'
            ],
            [
                'name' => 'admin',
                'shortcut' => null,
                'mode' => InputArgument::OPTIONAL,
                'description' => 'Is this template for admin part?'
            ]
        ],
        'description' => 'Creates a new controller',
        'name' => 'create:controller',
        'help' => 'creates a new controller in a given namespace with a given route'
    ];

    protected function configure()
    {
        $this->config($this);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setJobs();
        $this->setProcessors();
        $jobs = $this->jobs;

        try {
            $jobs[IsModuleExists::class]->handle($input->getArgument('namespace'), $output);

            $this->processControllerFile($input, $output);

            $this->runProcessors($input, $output);
        } catch (\Throwable $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }

    protected function processControllerFile(InputInterface $input, OutputInterface $output): void
    {
        $jobs = $this->jobs;
        $namespace = explode('_', $input->getArgument('namespace'));
        $controller = explode('/', $input->getArgument('route'));
        $scopeDir = $input->getOption('admin') ? 'Adminhtml/' : '';
        $scopeNamespace = $input->getOption('admin') ? 'Adminhtml\\' : '';
        $app = $this->appContainer;

        $jobs[CopyFile::class]->handle(
            $app->get('resource_dir') . '/classes/Controller.tphp',
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] .
            '/Controller/' . $scopeDir . ucwords($controller[1]) . '/' . ucwords($controller[2]) . '.php'
        );

        $this->replaceTextsSequence(
            [
                '{{controller_namespace}}' => $namespace[0] . '\\' . $namespace[1] . '\\Controller\\' . $scopeNamespace . ucwords($controller[1]),
                '{{className}}' => ucwords($controller[2]),
                '{{route}}' => $input->getArgument('route')
            ],
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Controller/'
        );

        $output->writeln('<info>'
            . $namespace[0] . '\\'
            . $namespace[1] . '\\Controller\\'
            . ucwords($controller[1]) . '\\'
            . ucwords($controller[2]) . ' was successfully created</info>');
    }
}

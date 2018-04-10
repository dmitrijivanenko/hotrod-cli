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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

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
        ProcessRouteFile::class => null
    ];

    protected function configure()
    {
        $this->setName('create:controller')
            ->setDescription('Creates a new controller')
            ->addArgument(
                'namespace',
                InputArgument::REQUIRED,
                'What is the namespace on the module'
            )
            ->addArgument(
                'route',
                InputArgument::REQUIRED,
                'route pattern is "route_name/controller/action"'
            )
            ->addOption(
                'no-block',
                'np',
                InputArgument::OPTIONAL,
                'Do you need a block?'
            )
            ->addOption(
                'no-layout',
                'nl',
                InputArgument::OPTIONAL,
                'Do you need a layout file?'
            )
            ->addOption(
                'no-routes',
                'nr',
                InputArgument::OPTIONAL,
                'Do you need a routes file'
            )
            ->addOption(
                'admin',
                'adm',
                InputArgument::OPTIONAL,
                'Is this template for admin part?'
            )
            ->setHelp('creates a new controller in a given namespace with a given route');
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

        } catch(\Throwable $e) {
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
        $jobs[ReplaceText::class]->handle(
            '{{controller_namespace}}',
            $namespace[0] . '\\' . $namespace[1] . '\\Controller\\' . $scopeNamespace . ucwords($controller[1]),
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Controller/'
        );
        $jobs[ReplaceText::class]->handle(
            '{{className}}',
            ucwords($controller[2]),
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Controller/'
        );
        $jobs[ReplaceText::class]->handle(
            '{{route}}',
            $input->getArgument('route'),
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Controller/'
        );

        $output->writeln('<info>'
            . $namespace[0] . '\\'
            . $namespace[1] . '\\Controller\\'
            . ucwords($controller[1]) . '\\'
            . ucwords($controller[2]) . ' was successfully created</info>');
    }

}

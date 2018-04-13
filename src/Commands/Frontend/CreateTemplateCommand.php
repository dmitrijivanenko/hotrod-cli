<?php

namespace HotRodCli\Commands\Frontend;

use HotRodCli\Commands\BaseCommand;
use HotRodCli\Jobs\Filesystem\CopyFile;
use HotRodCli\Jobs\Module\IsModuleExists;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CreateTemplateCommand extends BaseCommand
{
    protected $jobs = [
        CopyFile::class => null,
        IsModuleExists::class => null
    ];

    protected function configure()
    {
        $this->setName('create:template')
            ->setDescription('Creates a new template')
            ->addArgument(
                'namespace',
                InputArgument::REQUIRED,
                'What is the namespace on the module'
            )
            ->addArgument(
                'template-name',
                InputArgument::REQUIRED,
                'block\'s class name'
            )
            ->addOption(
                'admin',
                'adm',
                InputArgument::OPTIONAL,
                'is this template for admin'
            )
            ->setHelp('creates a new template in a given namespace with a given name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setJobs();
        $namespace = explode('_', $input->getArgument('namespace'));
        $name = $input->getArgument('template-name');
        $scope = $input->getOption('admin') ? 'adminhtml' : 'frontend';

        try {
            $this->jobs[IsModuleExists::class]->handle(
                $input->getArgument('namespace'),
                $output
            );

            $this->jobs[CopyFile::class]->handle(
                $this->appContainer->get('resource_dir') . '/frontend/template.phtml',
                $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] .
                    '/view/' . $scope . '/templates/' . $name . '.phtml'
            );

            $output->writeln('<info>Template ' . $input->getArgument('template-name') . ' was successfully created</info>');
        } catch (\Throwable $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }
}

<?php

namespace HotRodCli\Commands\Classes;

use HotRodCli\Commands\BaseCommand;
use HotRodCli\Jobs\Filesystem\CopyFile;
use HotRodCli\Jobs\Module\IsModuleExists;
use HotRodCli\Jobs\Module\ReplaceText;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CreateResourceModelCommand extends BaseCommand
{
    protected $jobs = [
        IsModuleExists::class => null,
        CopyFile::class => null,
        ReplaceText::class => null
    ];

    protected function configure()
    {
        $this->setName('create:resource-model')
            ->setDescription('Creates a new resource-model')
            ->addArgument(
                'namespace',
                InputArgument::REQUIRED,
                'What is the namespace on the module'
            )
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'What is the name of the new Resource Model'
            )
            ->addArgument(
                'table-name',
                InputArgument::REQUIRED,
                'What is the table name for the new Resource Model'
            )
            ->addArgument(
                'id-field',
                InputArgument::REQUIRED,
                'What is the id-field of the table'
            )
            ->setHelp('creates a new Resource Model in a given namespace');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setJobs();

        try {
            $this->runJobs($input, $output);

            $output->writeln('<info>Model Resource ' . $input->getArgument('name') . ' was successfully created</info>');
        } catch (\Throwable $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }

    protected function runJobs(InputInterface $input, OutputInterface $output)
    {
        $namespace = explode('_', $input->getArgument('namespace'));

        $this->jobs[IsModuleExists::class]->handle($input->getArgument('namespace'), $output);

        $this->jobs[CopyFile::class]->handle(
            $this->appContainer->get('resource_dir') . '/classes/ResourceModel.tphp',
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Model/ResourceModel/' . $input->getArgument('name') . '.php'
        );

        $this->jobs[ReplaceText::class]->handle(
            '{{namespace}}',
            str_replace('_', '\\', $input->getArgument('namespace')),
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Model/'
        );

        $this->jobs[ReplaceText::class]->handle(
            '{{ModelName}}',
            $input->getArgument('name'),
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Model/'
        );

        $this->jobs[ReplaceText::class]->handle(
            '{{table_name}}',
            $input->getArgument('table-name'),
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Model/'
        );

        $this->jobs[ReplaceText::class]->handle(
            '{{id_name}}',
            $input->getArgument('id-field'),
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Model/'
        );
    }
}

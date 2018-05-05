<?php

namespace HotRodCli\Commands\Classes;

use HotRodCli\Commands\BaseCommand;
use HotRodCli\Jobs\Filesystem\CopyFile;
use HotRodCli\Jobs\Module\IsModuleExists;
use HotRodCli\Jobs\Module\ReplaceText;
use HotRodCli\Processors\ProcessCollectionFile;
use HotRodCli\Processors\ProcessRepository;
use HotRodCli\Processors\ProcessResourceModelFile;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CreateModelCommand extends BaseCommand
{
    protected $jobs = [
        IsModuleExists::class => null,
        CopyFile::class => null,
        ReplaceText::class => null
    ];

    protected $processors = [
        ProcessResourceModelFile::class => null,
        ProcessCollectionFile::class => null,
        ProcessRepository::class => null
    ];

    protected function configure()
    {
        $this->setName('create:model')
            ->setDescription('Creates a new model')
            ->addArgument(
                'namespace',
                InputArgument::REQUIRED,
                'What is the namespace on the module'
            )
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'What is the name of the new Model'
            )
            ->addArgument(
                'table-name',
                InputArgument::REQUIRED,
                'What is the table name for the new Model'
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
        $this->setProcessors();
        $jobs = $this->jobs;

        try {
            $jobs[IsModuleExists::class]->handle($input->getArgument('namespace'), $output);

            $this->processModelFile($input, $output);

            $this->runProcessors($input, $output);
        } catch (\Throwable $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }

    protected function processModelFile(InputInterface $input, OutputInterface $output)
    {
        $namespace = explode('_', $input->getArgument('namespace'));

        $this->jobs[CopyFile::class]->handle(
            $this->appContainer->get('resource_dir') . '/classes/Model.tphp',
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Model/' . $input->getArgument('name') . '.php'
        );

        $this->replaceTextsSequence([
            '{{namespace}}' => str_replace('_', '\\', $input->getArgument('namespace')),
            '{{ModelName}}' => $input->getArgument('name'),
            '{{tag}}' => $input->getArgument('table-name'),
            '{{ResourceModel}}' => str_replace('_', '\\', $input->getArgument('namespace')) . '\\Model\\ResourceModel\\' . $input->getArgument('name')
        ], $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Model/');

        $output->writeln('<info>Model ' . $input->getArgument('name') . ' was successfully created</info>');
    }
}

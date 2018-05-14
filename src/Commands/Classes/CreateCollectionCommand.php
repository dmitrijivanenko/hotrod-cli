<?php

namespace HotRodCli\Commands\Classes;

use HotRodCli\Commands\BaseCommand;
use HotRodCli\Jobs\Filesystem\CopyFile;
use HotRodCli\Jobs\Module\IsModuleExists;
use HotRodCli\Jobs\Module\ReplaceText;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CreateCollectionCommand extends BaseCommand
{
    protected $jobs = [
        CopyFile::class => null,
        ReplaceText::class => null,
        IsModuleExists::class => null
    ];

    protected $configs = [
        'arguments' => [
            [
                'name' => 'namespace',
                'mode' => InputArgument::REQUIRED,
                'description' => 'What is the namespace on the module'
            ],
            [
                'name' => 'name',
                'mode' => InputArgument::REQUIRED,
                'description' => 'What is the name of the model'
            ],
            [
                'name' => 'table-name',
                'mode' => InputArgument::REQUIRED,
                'description' => 'What is the table name for the model'
            ],
            [
                'name' => 'id-field',
                'mode' => InputArgument::REQUIRED,
                'description' => 'What is the id-field of the table'
            ]
        ],
        'options' => [],
        'description' => 'Creates a new collection',
        'name' => 'create:collection',
        'help' => 'creates a new Collection in a given namespace'
    ];

    protected function configure()
    {
        $this->config();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setJobs();

        try {
            $this->jobs[IsModuleExists::class]->handle($input->getArgument('namespace'), $output);

            $this->processCollectionFile($input, $output);
        } catch (\Throwable $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }

    protected function processCollectionFile(InputInterface $input, OutputInterface $output)
    {
        $namespace = explode('_', $input->getArgument('namespace'));

        $this->jobs[CopyFile::class]->handle(
            $this->appContainer->get('resource_dir') . '/classes/Collection.tphp',
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Model/ResourceModel/' . $input->getArgument('name') . '/Collection.php'
        );

        $this->replaceTextsSequence([
            '{{namespace}}' => str_replace('_', '\\', $input->getArgument('namespace')),
            '{{name}}' => $input->getArgument('name'),
            '{{table_name}}' => $input->getArgument('table-name'),
            '{{id_name}}' => $input->getArgument('id-field'),
            '{{ResourceModel}}' => str_replace('_', '\\', $input->getArgument('namespace')) . '\\Model\\ResourceModel\\' . $input->getArgument('name'),
            '{{Model}}' => str_replace('_', '\\', $input->getArgument('namespace')) . '\\Model\\' . $input->getArgument('name')
        ], $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Model/');

        $output->writeln('<info>Collection ' . $input->getArgument('name') . ' was successfully created</info>');
    }
}

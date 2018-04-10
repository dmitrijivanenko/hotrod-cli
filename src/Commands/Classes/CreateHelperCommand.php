<?php

namespace HotRodCli\Commands\Classes;

use HotRodCli\Commands\BaseCommand;
use HotRodCli\Jobs\Filesystem\CopyFile;
use HotRodCli\Jobs\Module\IsModuleExists;
use HotRodCli\Jobs\Module\ReplaceText;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CreateHelperCommand extends BaseCommand
{
    protected $jobs = [
        IsModuleExists::class => null,
        CopyFile::class => null,
        ReplaceText::class => null
    ];

    protected function configure()
    {
        $this->setName('create:helper')
            ->setDescription('Creates a new helper')
            ->addArgument(
                'namespace',
                InputArgument::REQUIRED,
                'What is the namespace on the new module'
            )
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'What is the name of the new helper'
            )
            ->setHelp('creates a new helper in a given namespace');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setJobs();
        $namespace = explode('_', $input->getArgument('namespace'));
        $name = $input->getArgument('name');

        try {
            $jobResult[IsModuleExists::class] = $this->jobs[IsModuleExists::class]->handle(
                $input->getArgument('namespace'),
                $output
            );

            $jobResult[CopyFile::class] = $this->jobs[CopyFile::class]->handle(
                $this->appContainer->get('resource_dir') . '/classes/Helper.tphp',
                $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Helper/' . $name . '.php'
            );

            $jobResult[ReplaceText::class . '_namespace'] = $this->jobs[ReplaceText::class]->handle(
                '{{namespace}}',
                str_replace('_', '\\', $input->getArgument('namespace')),
                $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Helper/'
            );

            $jobResult[ReplaceText::class . '_name'] = $this->jobs[ReplaceText::class]->handle(
                '{{className}}',
                $input->getArgument('name'),
                $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Helper/'
            );

            $output->writeln('<info>Helper ' . $input->getArgument('name') . ' was successfully created</info>');
        } catch (\Throwable $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }
}

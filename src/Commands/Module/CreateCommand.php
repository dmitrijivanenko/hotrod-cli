<?php

namespace HotRodCli\Commands\Module;

use HotRodCli\Commands\BaseCommand;
use HotRodCli\Jobs\Filesystem\CopyFiles;
use HotRodCli\Jobs\Module\ReplaceText;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends BaseCommand
{
    protected $jobs = [
        CopyFiles::class => null,
        ReplaceText::class => null
    ];

    protected function configure()
    {
        $this->setName('module:create')
            ->setDescription('Creates a new basic 3rd party module')
            ->addArgument(
                'namespace',
                InputArgument::REQUIRED,
                'What is the namespace on the new module'
            )
            ->setHelp('creates a new basic 3rd party module with given namespace');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setJobs();
        $namespace = explode('_', $input->getArgument('namespace'));

        try {
            $this->jobs[CopyFiles::class]
                ->handle(
                    $this->appContainer->get('resource_dir') . '/module',
                    $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1]
                );

            $this->jobs[ReplaceText::class]
                ->handle(
                    '{{Namespace_Module_xml}}',
                    $input->getArgument('namespace'),
                    $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1]
                );

            $output->writeln('<info>Module ' . $input->getArgument('namespace') . ' was successfully created</info>');
        } catch(\Throwable $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }
}

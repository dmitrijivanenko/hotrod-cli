<?php

namespace HotRodCli\Commands\Classes;

use HotRodCli\Commands\BaseCommand;
use HotRodCli\Jobs\Filesystem\CopyFile;
use HotRodCli\Jobs\Module\IsModuleExists;
use HotRodCli\Jobs\Module\ReplaceText;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateInstallDataCommand extends BaseCommand
{
    protected $jobs = [
        IsModuleExists::class => null,
        CopyFile::class => null,
        ReplaceText::class => null
    ];

    protected function configure()
    {
        $this->setName('create:install-data')
            ->setDescription('Creates an install data script')
            ->addArgument(
                'namespace',
                InputArgument::REQUIRED,
                'What is the namespace of the module'
            )
            ->setHelp('creates an install data script in a given namespace');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setJobs();
        $namespace = explode('_', $input->getArgument('namespace'));

        try {
            $this->jobs[IsModuleExists::class]->handle(
                $input->getArgument('namespace'),
                $output
            );

            $this->jobs[CopyFile::class]->handle(
                $this->appContainer->get('resource_dir') . '/classes/InstallData.tphp',
                $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Setup/InstallData.php'
            );

            $this->jobs[ReplaceText::class]->handle(
                '{{namespace}}',
                str_replace('_', '\\', $input->getArgument('namespace')),
                $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Setup/'
            );

            $output->writeln('<info>Install Data file successfully created</info>');
        } catch (\Throwable $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }
}

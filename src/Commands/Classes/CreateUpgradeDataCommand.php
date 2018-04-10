<?php

namespace HotRodCli\Commands\Classes;

use HotRodCli\Commands\BaseCommand;
use HotRodCli\Jobs\Filesystem\CopyFile;
use HotRodCli\Jobs\Module\IsModuleExists;
use HotRodCli\Jobs\Module\ReplaceText;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUpgradeDataCommand extends BaseCommand
{
    protected $jobs = [
        IsModuleExists::class => null,
        CopyFile::class => null,
        ReplaceText::class => null
    ];

    protected function configure()
    {
        $this->setName('create:upgrade-data')
            ->setDescription('Creates an upgrade data script')
            ->addArgument(
                'namespace',
                InputArgument::REQUIRED,
                'What is the namespace of the module'
            )
            ->setHelp('creates an upgrade data script in a given namespace');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setJobs();
        $namespace = explode('_', $input->getArgument('namespace'));

        try {
            $jobResult[IsModuleExists::class] = $this->jobs[IsModuleExists::class]->handle(
                $input->getArgument('namespace'),
                $output
            );

            $this->jobs[CopyFile::class]->handle(
                $this->appContainer->get('resource_dir') . '/classes/UpgradeData.tphp',
                $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Setup/UpgradeData.php'
            );

            $this->jobs[ReplaceText::class]->handle(
                '{{namespace}}',
                str_replace('_', '\\', $input->getArgument('namespace')),
                $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Setup/'
            );

            $output->writeln('<info>Upgrade Data file successfully created</info>');
        } catch (\Throwable $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }
}
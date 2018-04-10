<?php
/**
 * Created by PhpStorm.
 * User: dmitrijivanenko
 * Date: 4/10/18
 * Time: 5:44 AM
 */

namespace HotRodCli\Commands\Classes;

use HotRodCli\Jobs\Module\IsModuleExists;
use HotRodCli\Commands\BaseCommand;
use HotRodCli\Jobs\Filesystem\CopyFile;
use HotRodCli\Jobs\Module\ReplaceText;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUpgradeSchemaCommand extends BaseCommand
{
    protected $jobs = [
        IsModuleExists::class => null,
        CopyFile::class => null,
        ReplaceText::class => null
    ];

    protected function configure()
    {
        $this->setName('create:upgrade-schema')
            ->setDescription('Creates an upgrade schema script')
            ->addArgument(
                'namespace',
                InputArgument::REQUIRED,
                'What is the namespace of the module'
            )
            ->setHelp('creates an upgrade schema script in a given namespace');
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
                $this->appContainer->get('resource_dir') . '/classes/UpgradeSchema.tphp',
                $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Setup/UpgradeSchema.php'
            );

            $this->jobs[ReplaceText::class]->handle(
                '{{namespace}}',
                str_replace('_', '\\', $input->getArgument('namespace')),
                $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Setup/'
            );

            $output->writeln('<info>Upgrade Schema file successfully created</info>');
        } catch (\Throwable $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }
}

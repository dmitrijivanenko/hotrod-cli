<?php

namespace HotRodCli\Commands\Classes;

use HotRodCli\Commands\BaseCommand;
use HotRodCli\Jobs\Filesystem\CopyFile;
use HotRodCli\Jobs\Module\IsModuleExists;
use HotRodCli\Jobs\Module\ReplaceText;
use HotRodCli\Jobs\Xml\AddPreference;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CreateRepositoryCommand extends BaseCommand
{
    protected $jobs = [
        CopyFile::class => null,
        ReplaceText::class => null,
        IsModuleExists::class => null,
        AddPreference::class => null
    ];

    protected function configure()
    {
        $this->setName('create:repository')
            ->setDescription('Creates a repository class')
            ->addArgument(
                'namespace',
                InputArgument::REQUIRED,
                'What is the namespace of the module'
            )
            ->addArgument(
                'interface-name',
                InputArgument::REQUIRED,
                'What is the interface name'
            )
            ->addArgument(
                'model-class',
                InputArgument::REQUIRED,
                'What is the CRUD model class'
            )
            ->setHelp('creates an upgrade data script in a given namespace');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setJobs();

        try {
            $this->jobs[IsModuleExists::class]->handle($input->getArgument('namespace'), $output);

            $this->processInterfaceFile($input, $output);
            $this->processRepositoryFile($input, $output);
            $this->processDiFile($input, $output);
        } catch (\Throwable $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }

    protected function processInterfaceFile(InputInterface $input, OutputInterface $output)
    {
        $namespace = explode('_', $input->getArgument('namespace'));

        $this->jobs[CopyFile::class]->handle(
            $this->appContainer->get('resource_dir') . '/api/Repository.tphp',
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Api/' . $input->getArgument('interface-name') . '.php'
        );

        $this->jobs[ReplaceText::class]->handle('{{namespace}}', $namespace[0] . '\\' . $namespace[1], $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Api/');

        $this->jobs[ReplaceText::class]->handle('{{name}}', $input->getArgument('interface-name'), $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Api/');

        $this->jobs[ReplaceText::class]->handle('{{Model}}', $input->getArgument('model-class'), $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Api/');

        $output->writeln('<info>Interface ' . $input->getArgument('interface-name') . ' was successfully created</info>');
    }

    protected function processRepositoryFile(InputInterface $input, OutputInterface $output)
    {
        $namespace = explode('_', $input->getArgument('namespace'));
        $modelName = explode('\\', $input->getArgument('model-class'))[3];

        $this->jobs[CopyFile::class]->handle(
            $this->appContainer->get('resource_dir') . '/classes/Repository.tphp',
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Model/' . $modelName . 'Repository.php'
        );

        $this->jobs[ReplaceText::class]->handle('{{namespace}}', $namespace[0] . '\\' . $namespace[1], $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Model/');

        $this->jobs[ReplaceText::class]->handle('{{interface}}', $input->getArgument('interface-name'), $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Model/');

        $this->jobs[ReplaceText::class]->handle('{{Model}}', $input->getArgument('model-class'), $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Model/');

        $this->jobs[ReplaceText::class]->handle('{{modelName}}', $modelName, $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Model/');

        $output->writeln('<info>Repository ' . $modelName . 'Repository was successfully created</info>');
    }

    protected function processDiFile(InputInterface $input, OutputInterface $output)
    {
        $namespace = explode('_', $input->getArgument('namespace'));
        $modelName = explode('\\', $input->getArgument('model-class'))[3];

        $this->jobs[CopyFile::class]->handle(
            $this->appContainer->get('resource_dir') . '/xml/di.xml',
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/etc/di.xml'
        );

        $this->jobs[AddPreference::class]->handle($this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/etc/di.xml', [
            'for' => $namespace[0] . '\\' . $namespace[1] . '\\Api\\' . $input->getArgument('interface-name'),
            'type' => $namespace[0] . '\\' . $namespace[1] . '\\Model\\' . $modelName . 'Repository'
        ]);

        $output->writeln('<info>Preference was successfully added for ' . $namespace[0] . '\\' . $namespace[1] . '\\Model\\' . $modelName . 'Repository' . '</info>');
    }
}

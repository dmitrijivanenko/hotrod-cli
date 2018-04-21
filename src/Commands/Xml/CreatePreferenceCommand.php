<?php

namespace HotRodCli\Commands\Xml;

use HotRodCli\Commands\BaseCommand;
use HotRodCli\Jobs\Filesystem\CopyFile;
use HotRodCli\Jobs\Module\IsModuleExists;
use HotRodCli\Jobs\Xml\AddPreference;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Filesystem\Filesystem;

class CreatePreferenceCommand extends BaseCommand
{
    protected $jobs = [
        IsModuleExists::class => null,
        CopyFile::class => null,
        AddPreference::class => null
    ];

    protected function configure()
    {
        $this->setName('create:preference')
            ->setDescription('Creates an override preference')
            ->addArgument(
                'namespace',
                InputArgument::REQUIRED,
                'What is the namespace on the module'
            )
            ->addArgument(
                'for',
                InputArgument::REQUIRED,
                'What class do you want to override'
            )
            ->addArgument(
                'type',
                InputArgument::REQUIRED,
                'What is your class'
            )
            ->addOption(
                'scope',
                null,
                InputArgument::OPTIONAL,
                'Is this di for admin or frontend part?'
            )
            ->setHelp('creates an override preference in a given namespace');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setJobs();
        $jobs = $this->jobs;
        $namespace = explode('_', $input->getArgument('namespace'));
        $scope = $input->getOption('scope');
        $filename = in_array($scope, ['frontend', 'adminhtml']) ? $scope . '/di.xml' : 'di.xml';
        $filename = $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/etc/' . $filename;

        $jobs[IsModuleExists::class]->handle($input->getArgument('namespace'), $output);

        if (!$this->appContainer->get(Filesystem::class)->exists($filename)) {
            $jobs[CopyFile::class]->handle($this->appContainer->get('resource_dir') . '/xml/di.xml', $filename);
        }

        $jobs[AddPreference::class]->handle($filename, ['for' => $input->getArgument('for'), 'type' => $input->getArgument('type')]);
    }
}

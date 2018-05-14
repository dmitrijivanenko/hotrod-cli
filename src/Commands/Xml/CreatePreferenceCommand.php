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

    protected $configs = [
        'arguments' => [
            [
                'name' => 'namespace',
                'mode' => InputArgument::REQUIRED,
                'description' => 'What is the namespace on the module'
            ],
            [
                'name' => 'for',
                'mode' => InputArgument::REQUIRED,
                'description' => 'What class do you want to override'
            ],
            [
                'name' => 'type',
                'mode' => InputArgument::REQUIRED,
                'description' => 'What is your class'
            ]
        ],
        'options' => [
            [
                'name' => 'scope',
                'shortcut' => null,
                'mode' => InputArgument::OPTIONAL,
                'description' => 'Is this di for admin or frontend part?'
            ]
        ],
        'description' => 'Creates an override preference',
        'name' => 'create:preference',
        'help' => 'creates an override preference in a given namespace'
    ];

    protected function configure()
    {
        $this->config();
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

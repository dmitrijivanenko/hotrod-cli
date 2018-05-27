<?php

namespace HotRodCli\Commands\Frontend;

use HotRodCli\Commands\BaseCommand;
use HotRodCli\Jobs\Filesystem\CopyFile;
use HotRodCli\Jobs\Module\IsModuleExists;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateRequireJsCommand extends BaseCommand
{
    protected $jobs = [
        CopyFile::class => null,
        IsModuleExists::class => null
    ];

    protected $configs = [
        'arguments' => [
            [
                'name' => 'namespace',
                'mode' => InputArgument::REQUIRED,
                'description' => 'What is the namespace on the module'
            ]
        ],
        'options' => [
            [
                'name' => 'admin',
                'shortcut' => null,
                'mode' => InputArgument::OPTIONAL,
                'description' => 'Do You want to generate the requirejs-config.js file in admin scope?'
            ]
        ],
        'description' => 'Initiates a requirejs-config.js',
        'name' => 'create:requirejs-config',
        'help' => 'Initiates a new requirejs-config.js in a given namespace'
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
            $this->processRequireJsFile($input, $output);
        } catch (\Throwable $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }

    protected function processRequireJsFile(InputInterface $input, OutputInterface $output)
    {
        $namespace = explode('_', $input->getArgument('namespace'));
        $scope = $this->getScope($input);

        $this->jobs[CopyFile::class]->handle(
            $this->appContainer->get('resource_dir') . '/frontend/requirejs-config.js',
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] .
            '/view/' . $scope . '/requirejs-config.js'
        );

        $output->writeln('<info>The ' . $namespace[0] . '/' . $namespace[1] . '/' . 'view/' . $scope . '/requirejs-config.js was created</info>');
    }

    protected function getScope(InputInterface $input): string
    {
        return $input->getOption('admin') ? 'adminhtml' : 'frontend';
    }
}

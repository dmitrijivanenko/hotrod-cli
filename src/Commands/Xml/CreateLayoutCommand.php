<?php

namespace HotRodCli\Commands\Xml;

use HotRodCli\Commands\BaseCommand;
use HotRodCli\Jobs\Filesystem\CopyFile;
use HotRodCli\Jobs\Module\ReplaceText;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateLayoutCommand extends BaseCommand
{
    protected $jobs = [
        CopyFile::class => null,
        ReplaceText::class => null
    ];

    protected $configs = [
        'arguments' => [
            [
                'name' => 'namespace',
                'mode' => InputArgument::REQUIRED,
                'description' => 'What is the namespace on the module'
            ],
            [
                'name' => 'layout-name',
                'mode' => InputArgument::REQUIRED,
                'description' => 'name of the layout'
            ],
            [
                'name' => 'layout-file-name',
                'mode' => InputArgument::REQUIRED,
                'description' => 'name of the layout\'s file'
            ],
            [
                'name' => 'block-class',
                'mode' => InputArgument::REQUIRED,
                'description' => 'full class name of the block'
            ],
            [
                'name' => 'template',
                'mode' => InputArgument::REQUIRED,
                'description' => 'template file'
            ]
        ],
        'options' => [
            [
                'name' => 'admin',
                'shortcut' => null,
                'mode' => InputArgument::OPTIONAL,
                'description' => 'Is this layout for admin part?'
            ]
        ],
        'description' => 'Creates a new layout file',
        'name' => 'create:layout',
        'help' => 'creates a new controller in a given namespace with a given route'
    ];

    public function configure()
    {
        $this->config();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setJobs();
        $jobs = $this->jobs;
        $namespace = explode('_', $input->getArgument('namespace'));
        $scope = $input->getOption('admin') ? 'adminhtml' : 'frontend';
        $app = $this->appContainer;

        $jobs[CopyFile::class]->handle(
            $app->get('resource_dir') . '/xml/layout.xml',
            $this->appContainer->get('app_dir')
            . '/app/code/' . $namespace[0] . '/' . $namespace[1]
            . '/view/' . $scope . '/layout/' . $input->getArgument('layout-file-name') . '.xml'
        );

        $jobs[ReplaceText::class]->handle(
            '{{block_class}}',
            $input->getArgument('block-class'),
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/view/'
        );

        $jobs[ReplaceText::class]->handle(
            '{{block_name}}',
            strtolower($input->getArgument('layout-name')),
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/view/'
        );

        $jobs[ReplaceText::class]->handle(
            '{{block_template}}',
            $namespace[0] . '_' . $namespace[1] . '::' . $input->getArgument('template') . '.phtml',
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/view/'
        );

        $output->writeln('<info>'
            . $namespace[0] . '/'
            . $namespace[1] . '/view/frontend/layout/'
            . strtolower($input->getArgument('layout-file-name')) . '.xml was successfully created</info>');
    }
}

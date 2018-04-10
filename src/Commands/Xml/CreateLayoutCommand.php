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

    public function configure()
    {
        $this->setName('create:layout')
            ->setDescription('Creates a new layout file')
            ->addArgument(
                'namespace',
                InputArgument::REQUIRED,
                'What is the namespace on the module'
            )
            ->addArgument(
                'layout-name',
                InputArgument::REQUIRED,
                'name of the layout'
            )
            ->addArgument(
                'layout-file-name',
                InputArgument::REQUIRED,
                'name of the layout\'s file'
            )
            ->addArgument(
                'block-class',
                InputArgument::REQUIRED,
                'full class name of the block'
            )
            ->addArgument(
                'template',
                InputArgument::REQUIRED,
                'template file'
            )
            ->addOption(
                'admin',
                'adm',
                InputArgument::OPTIONAL,
                'Is this layout for admin part?'
            )
            ->setHelp('creates a new controller in a given namespace with a given route');
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
            $namespace[0] . '_' . $namespace[1] . '::' . $input->getArgument('template') . '.phtml' ,
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/view/'
        );

        $output->writeln('<info>'
            . $namespace[0] . '/'
            . $namespace[1] . '/view/frontend/layout/'
            . strtolower($input->getArgument('layout-file-name')) . '.xml was successfully created</info>');
    }
}

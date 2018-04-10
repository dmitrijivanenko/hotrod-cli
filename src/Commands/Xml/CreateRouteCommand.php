<?php

namespace HotRodCli\Commands\Xml;

use HotRodCli\Commands\BaseCommand;
use HotRodCli\Jobs\Filesystem\CopyFile;
use HotRodCli\Jobs\Module\ReplaceText;
use HotRodCli\Jobs\Xml\AddRoute;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Filesystem\Filesystem;

class CreateRouteCommand extends BaseCommand
{
    protected $jobs = [
        CopyFile::class => null,
        ReplaceText::class => null,
        AddRoute::class => null
    ];

    public function configure()
    {
        $this->setName('create:route')
            ->setDescription('Creates a router')
            ->addArgument(
                'namespace',
                InputArgument::REQUIRED,
                'What is the namespace on the module'
            )
            ->addArgument(
                'route-name',
                InputArgument::REQUIRED,
                'route name'
            )
            ->addOption(
                'admin',
                'adm',
                InputArgument::OPTIONAL,
                'Is this route for admin part?'
            )
            ->setHelp('creates a new controller file or adds a route to existing one');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setJobs();
        $jobs = $this->jobs;
        $namespace = explode('_', $input->getArgument('namespace'));
        $routeName = $input->getArgument('route-name');
        $scope = $input->getOption('admin') ? 'adminhtml' : 'frontend';
        $app = $this->appContainer;
        /** @var Filesystem $filesystem */
        $filesystem = $app->resolve(Filesystem::class);

        if ($filesystem->exists($app->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/etc/frontend/routes.xml')) {
            $jobs[AddRoute::class]->handle(
                $this->appContainer->get('app_dir')
                . '/app/code/' . $namespace[0] . '/' . $namespace[1]
                . '/etc/' . $scope . '/routes.xml',
                [
                    'frontName' => strtolower($routeName),
                    'id' => strtolower($routeName),
                    'moduleName' => ucwords($namespace[0]) . '_' . ucwords($namespace[1]),
                ]
            );
        } else {
            $jobs[CopyFile::class]->handle(
                $app->get('resource_dir') . '/xml/routes.xml',
                $this->appContainer->get('app_dir')
                . '/app/code/' . $namespace[0] . '/' . $namespace[1]
                . '/etc/' . $scope . '/routes.xml'
            );

            $jobs[ReplaceText::class]->handle(
                '{{name}}',
                strtolower($routeName),
                $app->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/etc/' . $scope . ''
            );

            $jobs[ReplaceText::class]->handle(
                '{{module_name}}',
                ucwords($namespace[0]) . '_' . ucwords($namespace[1]),
                $app->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/etc/' . $scope . ''
            );
        }
    }
}
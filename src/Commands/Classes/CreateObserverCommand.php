<?php

namespace HotRodCli\Commands\Classes;

use HotRodCli\Commands\BaseCommand;
use HotRodCli\Jobs\Filesystem\CopyFile;
use HotRodCli\Jobs\Module\IsModuleExists;
use HotRodCli\Jobs\Module\ReplaceText;
use HotRodCli\Jobs\Xml\AddEvent;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateObserverCommand extends BaseCommand
{
    protected $jobs = [
        IsModuleExists::class => null,
        CopyFile::class => null,
        ReplaceText::class => null,
        AddEvent::class => null
    ];

    protected function configure()
    {
        $this->setName('create:observer')
            ->setDescription('Creates an observer')
            ->addArgument('namespace', InputArgument::REQUIRED, 'What is the namespace of the module')
            ->addArgument('event', InputArgument::REQUIRED, 'What is the event name')
            ->addArgument('observer', InputArgument::REQUIRED, 'What is the observer name')
            ->setHelp('creates an install schema script in a given namespace');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setJobs();

        try {
            $this->jobs[IsModuleExists::class]->handle($input->getArgument('namespace'), $output);

            $this->processObserverFile($input, $output);

            $this->processEventsFile($input, $output);
        } catch (\Throwable $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }

    protected function processObserverFile(InputInterface $input, OutputInterface $output)
    {
        $jobs = $this->jobs;
        $namespace = explode('_', $input->getArgument('namespace'));
        $app = $this->appContainer;
        $jobs[CopyFile::class]->handle(
            $app->get('resource_dir') . '/classes/Observer.tphp',
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] .
            '/Observer/' . ucwords($input->getArgument('observer')) . '.php'
        );

        $jobs[ReplaceText::class]->handle(
            '{{namespace}}',
            $namespace[0] . '\\' . $namespace[1]  . '\\',
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Observer/'
        );

        $jobs[ReplaceText::class]->handle(
            '{{observer-name}}',
            ucwords($input->getArgument('observer')),
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Observer/'
        );

        $output->writeln('<info>' . $namespace[0] . '\\' . $namespace[1] . '\\Observer\\' . ucwords(ucwords($input->getArgument('observer'))) . ' was successfully created</info>');
    }

    protected function processEventsFile(InputInterface $input, OutputInterface $output)
    {
        $jobs = $this->jobs;
        $namespace = explode('_', $input->getArgument('namespace'));
        $app = $this->appContainer;
        $jobs[CopyFile::class]->handle(
            $app->get('resource_dir') . '/xml/events.xml',
            $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/etc/events.xml'
        );

        $jobs[AddEvent::class]->handle($this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/etc/events.xml', [
            'event-name' => $input->getArgument('event'),
            'observer-name' => strtolower($namespace[0] . '_' . $namespace[1] . '_' . $input->getArgument('observer')),
            'instance' => $namespace[0] . '\\' . $namespace[1] . '\\Observer\\' . $input->getArgument('observer'),
        ]);
    }
}

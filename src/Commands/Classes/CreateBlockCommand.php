<?php

namespace HotRodCli\Commands\Classes;

use HotRodCli\Commands\BaseCommand;
use HotRodCli\Jobs\Filesystem\CopyFile;
use HotRodCli\Jobs\Module\IsModuleExists;
use HotRodCli\Jobs\Module\ReplaceText;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateBlockCommand extends BaseCommand
{
    protected $jobs = [
        IsModuleExists::class => null,
        CopyFile::class => null,
        ReplaceText::class => null
    ];

    protected function configure()
    {
        $this->setName('create:block')
            ->setDescription('Creates a new block')
            ->addArgument(
                'namespace',
                InputArgument::REQUIRED,
                'What is the namespace on the module'
            )
            ->addArgument(
                'blockname',
                InputArgument::REQUIRED,
                'block\'s class name'
            )
            ->addOption(
                'admin',
                null,
                InputArgument::OPTIONAL,
                'Is this block for admin part?'
            )
            ->setHelp('creates a new block in a given namespace with a given name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setJobs();

        try {
            $this->processBlockFile($input, $output);
        } catch (\Throwable $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }

    public function processBlockFile(InputInterface $input, OutputInterface $output)
    {
        $namespace = explode('_', $input->getArgument('namespace'));
        $name = $input->getArgument('blockname');

        $this->jobs[IsModuleExists::class]->handle($input->getArgument('namespace'), $output);
        $this->jobs[CopyFile::class]->handle($this->appContainer->get('resource_dir') . '/classes/Block.tphp', $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Block/' . $this->getScope($input) . ucwords($name) . '.php');
        $this->replaceTextsSequence(['{{namespace}}' => $namespace[0] . '\\' . $namespace[1] . '\\Block' . $this->getScopeNamespace($input), '{{blockName}}' => ucwords($name)], $this->appContainer->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/Block/');

        $output->writeln('<info>' . $namespace[0] . '\\' . $namespace[1] . '\\Block\\' . ucwords($name) . ' was successfully created</info>');
    }

    /**
     * @param InputInterface $input
     * @return string
     */
    protected function getScope(InputInterface $input): string
    {
        return $input->getOption('admin') ? 'Adminhtml/' : '';
    }

    /**
     * @param InputInterface $input
     * @return string
     */
    protected function getScopeNamespace(InputInterface $input): string
    {
        return $input->getOption('admin') ? '\\Adminhtml' : '';
    }
}

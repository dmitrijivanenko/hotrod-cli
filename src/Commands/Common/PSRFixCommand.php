<?php

namespace HotRodCli\Commands\Common;

use HotRodCli\Commands\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class PSRFixCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('psr:fix')
            ->setDescription('Fixes the code style according to the PSR2')
            ->addOption(
                'dir',
                null,
                InputArgument::OPTIONAL,
                'do you want to fix a specific dir?'
            )
            ->setHelp('This command allows you to fix your code style in "/app/code"');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dir = $input->getOption('dir') ? $input->getOption('dir') : '/app/code';
        $output->writeln('<info>Fixing the code to the PSR2</info>');
        exec('./vendor/bin/php-cs-fixer fix ' . $this->appContainer->get('app_dir') . $dir);
    }
}

<?php

namespace HotRodCli\Commands\Common;

use HotRodCli\Commands\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PSRFixCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('psr:fix')
            ->setDescription('Fixes the code style according to the PSR2')
            ->setHelp('This command allows you to fix your code style...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Fixing the code to the PSR2</info>');
        exec('./vendor/bin/php-cs-fixer fix ' . $this->appContainer->get('app_dir') . '/app/code');
    }
}

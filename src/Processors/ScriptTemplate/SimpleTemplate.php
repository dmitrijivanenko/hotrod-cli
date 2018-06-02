<?php

namespace HotRodCli\Processors\ScriptTemplate;

use HotRodCli\AppContainer;
use HotRodCli\Jobs\Js\AddMageInit;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SimpleTemplate
{
    protected $container;

    public function __construct(AppContainer $appContainer)
    {
        $this->container = $appContainer;
    }

    public function __invoke(InputInterface $input, OutputInterface $output)
    {
        $this->container->resolve(AddMageInit::class)->handle(
            $this->container->get('app_dir') . '/app/code/' . $input->getOption('template'),
            [
                'name' =>  $input->getArgument('script-name'),
                'bind' => '*'
            ]
        );

        $output->writeln('<info>mage init was added</info>');
    }
}

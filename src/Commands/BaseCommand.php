<?php

namespace HotRodCli\Commands;

use HotRodCli\Jobs\Module\ReplaceText;
use Symfony\Component\Console\Command\Command;
use HotRodCli\AppContainer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BaseCommand extends Command
{
    protected $appContainer;

    protected $info;

    protected $jobs = [];

    protected $processors = [];

    protected $configs = [
        'arguments' => [],
        'options' => [],
        'description' => '',
        'name' => '',
        'help' => '',
        'info' => ''
    ];

    /** @var  ReplaceText */
    protected $replaceTextJob;

    public function __construct(AppContainer $appContainer)
    {
        $this->appContainer = $appContainer;
        $this->replaceTextJob = $this->appContainer->resolve(ReplaceText::class);

        parent::__construct();
    }

    public function setJobs()
    {
        foreach ($this->jobs as $key => $job) {
            $this->jobs[$key] = $this->appContainer->resolve($key);
        }
    }

    public function setProcessors()
    {
        foreach ($this->processors as $key => $processor) {
            $this->processors[$key] = $this->appContainer->resolve($key);
        }
    }

    protected function config()
    {
        $configs = $this->configs;
        $this->setName($configs['name'])->setDescription($configs['description'])->setHelp($configs['help']);

        $this->setConfigArguments($configs);

        $this->setConfigOptions($configs);

        if (isset($configs['info'])) {
            $this->setInfo($configs['info']);
        }
    }

    public function runProcessors(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->processors as $processor) {
            $processor($input, $output);
        }

        return $this;
    }

    public function replaceTextsSequence(array $sequence, string $destination)
    {
        foreach ($sequence as $needle => $value) {
            $this->replaceTextJob->handle($needle, $value, $destination);
        }

        return $this;
    }

    public function setInfo(string $info)
    {
        $this->info = $info;

        return $this;
    }

    public function getInfo()
    {
        return $this->info;
    }

    protected function setConfigArguments($configs): void
    {
        foreach ($configs['arguments'] as $argument) {
            $this->addArgument(
                $argument['name'],
                $argument['mode'],
                $argument['description']
            );
        }
    }

    protected function setConfigOptions($configs): void
    {
        foreach ($configs['options'] as $option) {
            $this->addOption(
                $option['name'],
                $option['shortcut'],
                $option['mode'],
                $option['description']
            );
        }
    }
}

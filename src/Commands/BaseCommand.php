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

    protected $jobs = [];

    protected $processors = [];

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
}

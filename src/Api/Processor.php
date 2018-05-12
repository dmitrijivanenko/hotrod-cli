<?php

namespace HotRodCli\Api;

use HotRodCli\AppContainer;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Console\Command\Command;
use \Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\OutputInterface;

class Processor
{
    protected $app;

    protected $commands;

    protected $data;

    protected $inputs;

    /** @var OutputInterface */
    protected $output;

    public function __construct(AppContainer $appContainer)
    {
        $this->app = $appContainer;
        $this->commands = $this->app->resolve('commands');
        $this->output = $this->app->resolve(BufferedOutput::class);
    }

    public function __invoke(ServerRequestInterface $request, $args)
    {
        $this->data = $request->getParsedBody();
        /** @var Command $command */
        $command = $this->getCommand($args['command']);
        $this->setArguments();
        /** @var ArrayInput $arrayInput */
        $arrayInput = $this->app->resolve(ArrayInput::class, $this->inputs);
        $result = $command->run($arrayInput, $this->output);

        return json_encode([
            "success" => $result == 0 ? true : false,
            "output" => $this->output->fetch()
        ]);
    }

    public function getCommand($command)
    {
        if (isset($this->commands[$command])) {
            $application = $this->app->resolve(Application::class);

            return $application->find($command);
        }

        return null;
    }

    public function setArguments()
    {
        if ($this->data['arguments']) {
            foreach ($this->data['arguments'] as $key => $value) {
                $this->inputs[$key] = $value;
            }
        }

        return $this;
    }
}

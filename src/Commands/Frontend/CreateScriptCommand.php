<?php

namespace HotRodCli\Commands\Frontend;

use HotRodCli\Commands\BaseCommand;
use HotRodCli\Jobs\Filesystem\CopyFile;
use HotRodCli\Jobs\Js\AddJs;
use HotRodCli\Jobs\Js\AddMageInit;
use HotRodCli\Jobs\Module\IsModuleExists;
use HotRodCli\Processors\ProcessRequireJs;
use HotRodCli\Processors\ScriptFile\SimpleScript;
use HotRodCli\Processors\ScriptFile\WidgetScript;
use HotRodCli\Processors\ScriptTemplate\SimpleTemplate;
use HotRodCli\Processors\ScriptTemplate\WidgetTemplate;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class CreateScriptCommand extends BaseCommand
{
    protected $jobs = [
        CopyFile::class => null,
        IsModuleExists::class => null,
        AddJs::class => null,
        AddMageInit::class => null
    ];

    protected $processors = [
        ProcessRequireJs::class => null
    ];

    protected $configs = [
        'arguments' => [
            [
                'name' => 'namespace',
                'mode' => InputArgument::REQUIRED,
                'description' => 'What is the namespace on the module'
            ],
            [
                'name' => 'script-name',
                'mode' => InputArgument::REQUIRED,
                'description' => 'What is the filename of the script'
            ]
        ],
        'options' => [
            [
                'name' => 'admin',
                'shortcut' => null,
                'mode' => InputArgument::OPTIONAL,
                'description' => 'Do You want to generate the JS in admin scope?'
            ],
            [
                'name' => 'template',
                'shortcut' => null,
                'mode' => InputArgument::OPTIONAL,
                'description' => 'Do You want to add mage-init for this script in a specific template?'
            ],
            [
                'name' => 'type',
                'shortcut' => null,
                'mode' => InputArgument::OPTIONAL,
                'description' => 'type of the script. Available types are: [simple], [widget], [component]'
            ]
        ],
        'description' => 'Creates a new script',
        'name' => 'create:js-script',
        'help' => 'creates a new Java Script in a given namespace',
        'info' => ''
    ];

    protected $scriptFileProcessors = [
        'simple' => SimpleScript::class,
        'widget' => WidgetScript::class
    ];

    protected $scriptTemplateProcessors = [
        'simple' => SimpleTemplate::class,
        'widget' => WidgetTemplate::class
    ];

    protected function configure()
    {
        $this->config();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setJobs();
        $this->setProcessors();

        try {
            $this->jobs[IsModuleExists::class]->handle($input->getArgument('namespace'), $output);

            $this->processRequirejs($input, $output);
            $this->processScriptFile($input, $output);
            $this->processTemplate($input, $output);
        } catch (\Throwable $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }

    protected function processScriptFile(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getOption('type') ?? 'simple';

        $this->appContainer->resolve($this->scriptFileProcessors[$type])($input, $output);
    }

    protected function processRequirejs(InputInterface $input, OutputInterface $output)
    {
        $namespace = explode('_', $input->getArgument('namespace'));
        $app = $this->appContainer;
        $filesystem = $app->resolve(Filesystem::class);
        $scope = $input->getOption('admin') ? 'adminhtml' : 'frontend';

        if (!$filesystem->exists($app->get('app_dir') . '/app/code/' . $namespace[0] . '/' . $namespace[1] . '/view/' . $scope . '/requirejs-config.js')) {
            $this->processors[ProcessRequireJs::class]($input, $output);
        }
    }

    protected function processTemplate(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('template')) {
            $type = $input->getOption('type') ?? 'simple';

            $this->appContainer->resolve($this->scriptTemplateProcessors[$type])($input, $output);
        }
    }
}

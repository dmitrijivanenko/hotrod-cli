<?php

use Slim\App;
use Symfony\Component\Console\Application;
use Symfony\Component\Finder\Finder;
use Dotenv\Dotenv;
use HotRodCli\CommandsBootstrap;
use Symfony\Component\Filesystem\Filesystem;
use HotRodCli\AppContainer;
use Symfony\Component\Console\Output\BufferedOutput;
use \Symfony\Component\Console\Input\ArrayInput;

$api = new App();

$commands = require 'commands.php';

$finder = new Finder();
$finder->files()
    ->ignoreDotFiles(false)
    ->in(__DIR__)
    ->name('.env');

$mode = 'production';

if ($finder->count() === 1) {
    $env = new Dotenv(__DIR__);
    $env->load();
    $mode = getenv('env');
}

$cdAppDir = $mode === 'development' ? '' : '/../../..';

$app = new AppContainer();

$app->bind(AppContainer::class, $app);
$app->bind(Application::class, new Application('Magento Submarine', '0.0.2'));
$app->bind('app_dir', __DIR__ . $cdAppDir);
$app->bind('submarine_dir', __DIR__);
$app->bind('resource_dir', __DIR__ . '/resources');
$app->bind(Filesystem::class, new Filesystem());
$app->bind(Finder::class, new Finder());
$app->bind('commands', $commands);
$app->bind(BufferedOutput::class, new BufferedOutput());

$commandBootstrap = new CommandsBootstrap($commands, $app);

$commandBootstrap->register($app->resolve(Application::class));

require "routes.php";

$api->run();
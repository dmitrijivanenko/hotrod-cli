<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Finder\Finder;
use Dotenv\Dotenv;
use HotRodCli\CommandsBootstrap;
use Symfony\Component\Filesystem\Filesystem;
use HotRodCli\AppContainer;

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
$app->bind(Application::class, new Application('Magento Submarine', '0.0.3'));
$app->bind('app_dir', __DIR__ . $cdAppDir);
$app->bind('submarine_dir', __DIR__);
$app->bind('resource_dir', __DIR__ . '/resources');
$app->bind(Filesystem::class, new Filesystem());
$app->bind(Finder::class, new Finder());

$commandBootstrap = new CommandsBootstrap($commands, $app);

$commandBootstrap->register($app->resolve(Application::class));

($app->resolve(Application::class))->run();
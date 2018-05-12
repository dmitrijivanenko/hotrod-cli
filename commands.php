<?php

use HotRodCli\Commands\Common\PSRFixCommand;
use HotRodCli\Commands\Module\CreateCommand;
use HotRodCli\Commands\Classes\CreateHelperCommand;
use HotRodCli\Commands\Classes\CreateControllerCommand;
use HotRodCli\Commands\Classes\CreateBlockCommand;
use HotRodCli\Commands\Xml\CreateRouteCommand;
use HotRodCli\Commands\Xml\CreateLayoutCommand;
use HotRodCli\Commands\Classes\CreateInstallSchemaCommand;
use HotRodCli\Commands\Classes\CreateUpgradeSchemaCommand;
use HotRodCli\Commands\Classes\CreateInstallDataCommand;
use HotRodCli\Commands\Classes\CreateUpgradeDataCommand;
use HotRodCli\Commands\Frontend\CreateTemplateCommand;
use HotRodCli\Commands\Classes\CreateResourceModelCommand;
use HotRodCli\Commands\Classes\CreateModelCommand;
use HotRodCli\Commands\Classes\CreateCollectionCommand;
use HotRodCli\Commands\Xml\CreatePreferenceCommand;
use HotRodCli\Commands\Classes\CreateObserverCommand;
use HotRodCli\Commands\Classes\CreateRepositoryCommand;

return [
    'psr-fix' => PSRFixCommand::class,
    'module:create' => CreateCommand::class,
    'helper' => CreateHelperCommand::class,
    'controller' => CreateControllerCommand::class,
    'block' => CreateBlockCommand::class,
    'route' => CreateRouteCommand::class,
    'layout' => CreateLayoutCommand::class,
    'install-schema' => CreateInstallSchemaCommand::class,
    'upgrade-schema' => CreateUpgradeSchemaCommand::class,
    'install-data' => CreateInstallDataCommand::class,
    'upgrade-data' => CreateUpgradeDataCommand::class,
    'template' => CreateTemplateCommand::class,
    'resource-model' => CreateResourceModelCommand::class,
    'model' => CreateModelCommand::class,
    'collection' => CreateCollectionCommand::class,
    'preference' => CreatePreferenceCommand::class,
    'observer' => CreateObserverCommand::class,
    'repository' => CreateRepositoryCommand::class
];

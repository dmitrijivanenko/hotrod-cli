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
    'module:create' => CreateCommand::class,
    'create:helper' => CreateHelperCommand::class,
    'create:controller' => CreateControllerCommand::class,
    'create:block' => CreateBlockCommand::class,
    'create:route' => CreateRouteCommand::class,
    'create:layout' => CreateLayoutCommand::class,
    'create:install-schema' => CreateInstallSchemaCommand::class,
    'create:upgrade-schema' => CreateUpgradeSchemaCommand::class,
    'create:install-data' => CreateInstallDataCommand::class,
    'create:upgrade-data' => CreateUpgradeDataCommand::class,
    'create:template' => CreateTemplateCommand::class,
    'create:resource-model' => CreateResourceModelCommand::class,
    'create:model' => CreateModelCommand::class,
    'create:collection' => CreateCollectionCommand::class,
    'create:preference' => CreatePreferenceCommand::class,
    'create:observer' => CreateObserverCommand::class,
    'create:repository' => CreateRepositoryCommand::class,
    'psr:fix' => PSRFixCommand::class,
];

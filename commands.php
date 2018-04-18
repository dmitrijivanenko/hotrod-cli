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

return [
    PSRFixCommand::class,
    CreateCommand::class,
    CreateHelperCommand::class,
    CreateControllerCommand::class,
    CreateBlockCommand::class,
    CreateRouteCommand::class,
    CreateLayoutCommand::class,
    CreateInstallSchemaCommand::class,
    CreateUpgradeSchemaCommand::class,
    CreateInstallDataCommand::class,
    CreateUpgradeDataCommand::class,
    CreateTemplateCommand::class,
    CreateResourceModelCommand::class,
    CreateModelCommand::class,
    CreateCollectionCommand::class
];

<?php

return [
    'module:create' => HotRodCli\Commands\Module\CreateCommand::class,
    'create:helper' => HotRodCli\Commands\Classes\CreateHelperCommand::class,
    'create:controller' => HotRodCli\Commands\Classes\CreateControllerCommand::class,
    'create:block' => HotRodCli\Commands\Classes\CreateBlockCommand::class,
    'create:route' => HotRodCli\Commands\Xml\CreateRouteCommand::class,
    'create:layout' => HotRodCli\Commands\Xml\CreateLayoutCommand::class,
    'create:install-schema' => HotRodCli\Commands\Classes\CreateInstallSchemaCommand::class,
    'create:upgrade-schema' => HotRodCli\Commands\Classes\CreateUpgradeSchemaCommand::class,
    'create:install-data' => HotRodCli\Commands\Classes\CreateInstallDataCommand::class,
    'create:upgrade-data' => HotRodCli\Commands\Classes\CreateUpgradeDataCommand::class,
    'create:template' => HotRodCli\Commands\Frontend\CreateTemplateCommand::class,
    'create:resource-model' => HotRodCli\Commands\Classes\CreateResourceModelCommand::class,
    'create:model' => HotRodCli\Commands\Classes\CreateModelCommand::class,
    'create:collection' => HotRodCli\Commands\Classes\CreateCollectionCommand::class,
    'create:preference' => HotRodCli\Commands\Xml\CreatePreferenceCommand::class,
    'create:observer' => HotRodCli\Commands\Classes\CreateObserverCommand::class,
    'create:repository' => HotRodCli\Commands\Classes\CreateRepositoryCommand::class,
    'create:requirejs-config' => HotRodCli\Commands\Frontend\CreateRequireJsCommand::class,
    'create:js-script' => \HotRodCli\Commands\Frontend\CreateScriptCommand::class,
    'psr:fix' => HotRodCli\Commands\Common\PSRFixCommand::class,
];

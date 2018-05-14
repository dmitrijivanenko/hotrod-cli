<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use \HotRodCli\Api\Processor;
use \HotRodCli\Api\GetCommands;
use \HotRodCli\Api\GetCommand;

/** @var \Slim\App $api */
$api->post('/create/{command}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($app) {
    return $response->getBody()->write($app->resolve(Processor::class)($request, $args));
});

$api->get('/commands', function(ServerRequestInterface $request, ResponseInterface $response) use ($app) {
    return $response->getBody()->write($app->resolve(GetCommands::class)());
});

$api->get('/command/{command}', function(ServerRequestInterface $request, ResponseInterface $response, $args) use ($app) {
    return $response->getBody()->write($app->resolve(GetCommand::class)($args));
});

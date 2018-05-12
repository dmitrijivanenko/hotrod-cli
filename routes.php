<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use \HotRodCli\Api\Processor;

/** @var \Slim\App $api */
$api->post('/create/{command}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($app) {
    return $response->getBody()->write($app->resolve(Processor::class)($request, $args));
});
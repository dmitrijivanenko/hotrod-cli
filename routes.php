<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/** @var \Slim\App $app */
$app->get('/', function (ServerRequestInterface $request, ResponseInterface $response, $args) {
    return $response->getBody()->write("Hello from hotrod-cli");
});
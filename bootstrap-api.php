<?php

use Slim\App;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;

$app = new App();

require "routes.php";

$app->run();
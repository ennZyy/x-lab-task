<?php

use App\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . "/../config/config.php";

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
// Display errors info
$app->addErrorMiddleware(true, true, true);

$database = new Database();

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("Hello, user");
    return $response;
});

// Run app
$app->run();
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
    include_once __DIR__ . "/../template/home.php";
    return $response;
});

$app->get('/login', function (Request $request, Response $response) {
    return $response;
});

$app->post('/login-process', function (Request $request, Response $response) {
    return $response;
});

$app->get('/logout', function (Request $request, Response $response) {
    return $response;
});

$app->get('/register', function (Request $request, Response $response) {
    include_once __DIR__ . "/../template/register.php";
    return $response;
});

$app->post('/register-process', function (Request $request, Response $response) {
    return $response;
});

// Run app
$app->run();
<?php

use App\Database;
use App\Authorization;
use App\Session;
use App\Cookie;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . "/../config/config.php";
$app = AppFactory::create();
$app->addBodyParsingMiddleware();
// Display errors info
$app->addErrorMiddleware(true, true, true);

$session = new Session();
$sessionMiddleware = function (Request $request, Handler $handler) use ($session) {
    $session->start();
    $response = $handler->handle($request);
    $session->save();

    return $response;
};

$cookie = new Cookie();

$app->add($sessionMiddleware);

$database = new Database();
$authorization = new Authorization($database, $session, $cookie);

$app->get('/', function (Request $request, Response $response) use ($authorization, $cookie) {
    if (empty($cookie->getData("username"))) {
        return $response->withHeader("Location", "/login")
            ->withStatus(302);
    } else {
        $name = $cookie->getData("username");
        $validate = $authorization->validateCookie($name);

        if ($validate === true) {
            include_once __DIR__ . "/../template/home.php";
        } else {
            return $response->withHeader("Location", "/login")
                ->withStatus(302);
        }
    }

    return $response;
});

$app->get('/login', function (Request $request, Response $response) use ($authorization, $session, $cookie) {
    if (!empty($cookie->getData("username"))) {
        $name = $cookie->getData("username");
        $validate = $authorization->validateCookie($name);

        if ($validate === true) {
            return $response->withHeader("Location", "/")
                ->withStatus(302);
        } else {
            $cookie->unset("username");
            return $response->withHeader("Location", "/login")
                ->withStatus(302);
        }
    } else {
        $message = $session->getData("message");
        include_once __DIR__ . "/../template/login.php";
    }

    return $response;
});

$app->post('/login-process', function (Request $request, Response $response) use ($authorization, $session) {
    $params = (array) $request->getParsedBody();

    try {
        $authorization->login($params);
    } catch (\App\AuthorizationException $exception) {
        $session->setData("message", $exception->getMessage());

        return $response->withHeader("Location", "/login")
            ->withStatus(302);
    }

    return $response->withHeader("Location", "/")
        ->withStatus(302);
});

$app->get('/logout', function (Request $request, Response $response) use ($session, $cookie) {
    $session->flush("message");
    $cookie->unset("username");
    return $response->withHeader("Location", "/login")
        ->withStatus(302);
});

$app->get('/register', function (Request $request, Response $response) use ($session) {
    $message = !empty($session->getData("message")) ? $session->flush("message") : null;

    include_once __DIR__ . "/../template/register.php";
    return $response;
});

$app->post('/register-process', function (Request $request, Response $response) use ($authorization, $session) {
    $params = (array) $request->getParsedBody();

    try {
        $authorization->register($params);

        $response->getBody()->write(json_encode("ok"));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    } catch (\App\AuthorizationException $exception) {
        $errorResponse = $exception->getMessage();

        $response->getBody()->write(json_encode($errorResponse));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }
});

$app->run();
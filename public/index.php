<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

$app->get('/health', function(Request $request, Response $response): Response{

    $payload = [
        'status' => 'ok',
        'service'=> 'gig-log',
        'time' => date('c'),
    ];

    $response->getBody()->write(json_encode($payload));

    return $response->withHeader('Content-Type', 'application/json');

});

$app->run();
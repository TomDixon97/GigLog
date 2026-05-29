<?php

declare(strict_types=1);

use App\Controller\VenueController;
use App\Entity\Venue;
use App\Repository\VenueRepository;
use App\Repository\ArtistRepository;
use App\Controller\ArtistController;
use App\Entity\Artist;
use DI\Container;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();

$container->set(EntityManager::class, function () {
    return require __DIR__ . '/../config/doctrine.php';
});

$container->set(VenueRepository::class, function (Container $c) {
    return $c->get(EntityManager::class)->getRepository(Venue::class);
});

$container->set(ArtistRepository::class, function (Container $c) {
    return $c->get(EntityManager::class)->getRepository(Artist::class);
});

AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->get('/health', function (Request $request, Response $response): Response {
    $response->getBody()->write(json_encode(['status' => 'ok', 'service' => 'gig-log']));
    return $response->withHeader('Content-Type', 'application/json');
});

//venues routes
$app->get('/venues', [VenueController::class, 'list']);
$app->get('/venues/{id}', [VenueController::class, 'listById']);
$app->post('/venues', [VenueController::class, 'create']);
$app->put('/venues/{id}', [VenueController::class, 'update']);
$app->delete('/venues/{id}', [VenueController::class, 'delete']);

//artist routes

$app->get('/artists', [ArtistController::class, 'list']);
$app->get('/artists/{id}', [ArtistController::class, 'listById']);
$app->post('/artists', [ArtistController::class, 'create']);
$app->put('/artists/{id}', [ArtistController::class, 'update']);
$app->delete('/artists/{id}', [ArtistController::class, 'delete']);

$app->run();
<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\DBAL\DriverManager;

require __DIR__ . '/../vendor/autoload.php';

// Tell Doctrine where our entity classes live and that we use #[attributes]
// for mapping. We'll create src/Entity in Step 2.
$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/../src/Entity'],
    isDevMode: true,
);

// Connection details. These match the `db` service in docker-compose.yml.
// host is 'db' — the service name, reachable on Docker's internal network.
$connectionParams = [
    'driver' => 'pdo_pgsql',
    'host' => 'db',
    'port' => 5432,
    'dbname' => 'giglog',
    'user' => 'giglog',
    'password' => 'secret',
];

$connection = DriverManager::getConnection($connectionParams, $config);

// The EntityManager: the central object that does all database work.
$entityManager = new EntityManager($connection, $config);

return $entityManager;
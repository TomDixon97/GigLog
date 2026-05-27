<?php

declare(strict_types=1);

use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\PhpFile;

require __DIR__ . '/vendor/autoload.php';

// Load the EntityManager built in config/doctrine.php.
$entityManager = require __DIR__ . '/config/doctrine.php';

// Load the migrations config (migrations.php) and hand it the EntityManager.
$config = new PhpFile(__DIR__ . '/migrations.php');

return DependencyFactory::fromEntityManager(
    $config,
    new ExistingEntityManager($entityManager),
);
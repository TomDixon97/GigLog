<?php


declare(strict_types=1);

// Load the EntityManager we configured in config/doctrine.php.
$entityManager = require __DIR__ . '/../config/doctrine.php';

try {
    // Grab the underlying connection and ask Postgres its version.
    // If this returns a version string, PHP can talk to the database.
    $connection = $entityManager->getConnection();
    $version = $connection->executeQuery('SELECT version()')->fetchOne();

    echo "Connected to the database.\n";
    echo "Postgres says: $version\n";
} catch (\Throwable $e) {
    echo "Connection FAILED:\n";
    echo $e->getMessage() . "\n";
}
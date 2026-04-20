<?php

require_once __DIR__ . '/bootstrap.php';

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    if (!in_array('pgsql', PDO::getAvailableDrivers(), true)) {
        die('The PDO PostgreSQL driver is not enabled. Please install or enable the pdo_pgsql extension in PHP.');
    }

    $host = env('DB_HOST', 'localhost');
    $port = env('DB_PORT', '5432');
    $name = env('DB_NAME', 'auth_assignment');
    $user = env('DB_USER', 'postgres');
    $pass = env('DB_PASS', '');

    $dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', $host, $port, $name);

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $exception) {
        die('Database connection failed. Please check your PostgreSQL credentials in the .env file.');
    }

    return $pdo;
}

<?php
// includes/bootstrap.php
declare(strict_types=1);

// Wczytaj .env tylko jeśli nie ma ustawionych zmiennych środ.
// Dzięki temu w Dockerze pierwszeństwo mają wartości z docker-compose.
$envFile = __DIR__ . '/../.env';
$hasExternalEnv = getenv('DB_HOST') || getenv('DB_NAME') || getenv('DB_USER');
if (!$hasExternalEnv && is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        [$k, $v] = array_pad(explode('=', $line, 2), 2, null);
        if ($k !== null && $v !== null) {
            $_ENV[$k] = $v;
            putenv("$k=$v");
        }
    }
}

$config = require __DIR__ . '/../config/config.php';
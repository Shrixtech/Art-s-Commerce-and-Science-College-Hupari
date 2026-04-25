<?php
declare(strict_types=1);

require_once __DIR__ . '/constants.php';

/**
 * Returns shared PDO instance using strict SQL-safe options.
 */
function getPDO(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
        DB_HOST,
        DB_PORT,
        DB_NAME
    );

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode='STRICT_ALL_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'",
    ];

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $exception) {
        $message = sprintf(
            "[%s] Database connection failed: %s%s",
            date('Y-m-d H:i:s'),
            $exception->getMessage(),
            PHP_EOL
        );
        @file_put_contents(LOG_FILE, $message, FILE_APPEND);

        if (APP_ENV === 'development') {
            throw $exception;
        }

        throw new RuntimeException('Database connection error. Please try again later.');
    }

    return $pdo;
}

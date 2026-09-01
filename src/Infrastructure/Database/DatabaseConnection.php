<?php

declare(strict_types=1);

namespace App\Infrastructure\Database;

use PDO;

final class DatabaseConnection
{
    public static function create(): PDO
    {
        $host = 'localhost';
        $port = '5432';
        $database = 'flashcard_app';
        $username = 'postgres';
        $password = 'root';

        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            $host,
            $port,
            $database,
        );

        return new PDO(
            $dsn,
            $username,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ],
        );
    }
}
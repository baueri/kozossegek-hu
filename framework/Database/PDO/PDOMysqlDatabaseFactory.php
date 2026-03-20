<?php

declare(strict_types=1);

namespace Framework\Database\PDO;

use PDO;

final readonly class PDOMysqlDatabaseFactory
{
    public static function create(): PDOMysqlDatabase
    {
        $configuration = config('db');

        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s;port=%s',
            $configuration['host'],
            $configuration['database'],
            $configuration['charset'],
            $configuration['port']
        );

        $pdo = new PDO($dsn, $configuration['user'], $configuration['password'], [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        ]);

        return new PDOMysqlDatabase($pdo);
    }
}

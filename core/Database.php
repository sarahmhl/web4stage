<?php

declare(strict_types=1);


namespace Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        /** @var array{db_dsn:string, db_user:string, db_pass:string} $config */
        $config = Config::all();

        try {
            self::$pdo = new PDO(
                $config['db_dsn'],
                $config['db_user'],
                $config['db_pass'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
                ]
            );
        } catch (PDOException $e) {
            throw new \RuntimeException('Erreur de connexion à la base de données : ' . $e->getMessage());
        }

        return self::$pdo;
    }
}



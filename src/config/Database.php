<?php

namespace App\config;

use Dotenv\Dotenv;
use PDO;

class Database
{
    private static ?PDO $pdo = null;

    public static function getPDO(): PDO
    {
        if (file_exists(__DIR__ . '../../../.env')) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();
        }

        if (self::$pdo === null) {
            // Remplacez les valeurs avec votre configuration SQL dans `/config/Database.php`
            $host = '127.0.0.1';
            $dbname = 'projetb2';
            $port = 8889;
            $user = 'projetb2';
            $password = 'password';

            try {
                self::$pdo = new PDO(
                    "mysql:host=$host;dbname=$dbname;port=$port;charset=utf8",
                    $user,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (\Exception $e) {
                die('Erreur de connexion à la base de données : ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
<?php

namespace App;

use PDOException;

class Database {
    private static ?\PDO $pdo = null;

    private function __construct() {

    }

    /**
     * Renvoi une connexion à la bdd (singleton)
     *
     * @return \PDO une instance de connexion à la BDD (singleton)
     */
    public static function getPDO(): \PDO {
        if(isset(self::$pdo) && !empty(self::$pdo)) {
            return self::$pdo;
        }
        $DB_HOST = $_ENV["DB_HOST"];
        $DB_NAME = $_ENV["DB_NAME"];
        $DB_USER = $_ENV["DB_USER"];
        $DB_PASS = $_ENV["DB_PASS"];
        $DB_PORT = $_ENV["DB_PORT"];
        try {

            $dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;port=$DB_PORT";
            self::$pdo = new \PDO($dsn, $DB_USER, $DB_PASS);

        } catch(PDOException $e) {
            echo $e->getMessage();
        }
        return self::$pdo;
    }
}
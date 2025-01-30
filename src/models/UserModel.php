<?php

namespace App\Models;

use App\config\Database;
use PDO;
use Exception;

class UserModel
{
    protected $table = 'users'; // Nom de la table
    private $pdo = null;
    protected $keyName = 'id'; // Clé primaire par défaut

    public function __construct()
    {
        $this->pdo = Database::getPDO();
    }

    public function createUser(string $firstname, string $lastname, string $email, string $password): int|bool
    {
        $sql = "INSERT INTO $this->table (firstname, lastname, email, password) VALUES (:firstname, :lastname, :email, :password)";
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute([
                ':firstname' => $firstname,
                ':lastname' => $lastname,
                ':email' => $email,
                ':password' => $password,
            ]);
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function findAllUsers(): array|bool
    {
        $sql = "SELECT * FROM $this->table";
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function findUserByID(string $id): array|bool
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(":id", $id);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC); // Correction pour récupérer une seule ligne
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function findUserByIEmail(string $email): array|bool
    {
        $sql = "SELECT * FROM $this->table WHERE email = :email";
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(":email", $email);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC); // Correction pour récupérer une seule ligne
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function updateUser(int $id, array $values): int|bool
    {
        $sql = "UPDATE $this->table SET ";
        $keys = array_keys($values);
        $arrayKeys = [];
        foreach ($keys as $key) {
            $str = $key . "=:" . $key;
            $arrayKeys[] = $str;
        }
        $keysStr = implode(', ', $arrayKeys);
        $key = $this->keyName;
        $sql .= $keysStr . " WHERE $key=:id";
        try {
            $statement = $this->pdo->prepare($sql);

            foreach ($values as $key => $val) {
                $statement->bindParam(":$key", $val);
            }
            $statement->bindParam(":id", $id); // Correction ici
            $statement->execute();
            return $statement->rowCount();
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function deleteUser(int $id): int|bool
    {
        $key = $this->keyName;
        $sql = "DELETE FROM $this->table WHERE $key = :id"; // Ajout de "FROM"
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(":id", $id); // Correction ici
            $statement->execute();
            return $statement->rowCount();
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
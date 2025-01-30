<?php

namespace App\models;

use App\Database;
use PDO;
use Exception;

class Model
{
    protected $table = self::class;
    private $pdo = null;
    protected $keyName = "id";

    public function __construct(string $table = "", string $key = "id")
    {
        $this->table = 'user';
        $this->pdo = Database::getPDO();
        var_dump($this->pdo);
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
            echo($e->getMessage());
            return false;
        }

    }

    public function findAllUser(): array|bool
    {
        $sql = "SELECT * FROM $this->table";
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo($e->getMessage());
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
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo($e->getMessage());
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
            $statement->bindParam(":$key", $id);
            $statement->execute();
            return $statement->rowCount();
        } catch (Exception $e) {
            echo($e->getMessage());
            return false;
        }
    }

    public function deleteUser(int $id): int|bool
    {
        $key = $this->keyName;
        $sql = "DELETE $this->table WHERE $key = :id";
        try {
            $statement = $this->pdo->prepare($sql);

            $statement->bindParam(":$key", $id);
            $statement->execute();
            return $statement->rowCount();
        } catch (Exception $e) {
            echo($e->getMessage());
            return false;
        }
    }

}
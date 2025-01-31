<?php

namespace App\Models;

use App\config\Database;
use PDO;
use Exception;

class UserModel
{
    protected $table = 'users';
    private $pdo = null;
    protected $keyName = 'id';

    public function __construct()
    {
        $this->pdo = Database::getPDO();
    }

    /**
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $password
     * @return int|bool
     */
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

    /**
     * @return array|bool
     */
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

    /**
     * @param string $id
     * @return array|bool
     */
    public function findUserByID(string $id): array|bool
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(":id", $id);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * @param string $email
     * @return array|bool
     */
    public function findUserByIEmail(string $email): array|bool
    {
        $sql = "SELECT * FROM $this->table WHERE email = :email";
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(":email", $email);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * @param int $id
     * @param array $values
     * @return int|bool
     */
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
            $statement->bindParam(":id", $id);
            $statement->execute();
            return $statement->rowCount();
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * @param int $id
     * @return int|bool
     */
    public function deleteUser(int $id): int|bool
    {
        $key = $this->keyName;
        $sql = "DELETE FROM $this->table WHERE $key = :id";
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(":id", $id);
            $statement->execute();
            return $statement->rowCount();
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
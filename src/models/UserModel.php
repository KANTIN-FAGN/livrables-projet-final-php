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
        $sql = "SELECT u.id, u.firstname, u.lastname, p.bio, p.website_link 
            FROM users u
            LEFT JOIN profiles p ON u.id = p.user_id
            WHERE u.id = :id";
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
    public function findUserByEmail(string $email): array|bool
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

    public function findProfileByUserId(int $userId): ?array
    {
        $query = "SELECT * FROM profiles WHERE user_id = :user_id";
        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        return $result ?? null; // Retourne un tableau ou null si rien n'est trouvé.
    }

    public function findSkillsUserByID(int $id): array|bool
    {
        $query = "SELECT skills.name, user_skills.level
              FROM user_skills
              JOIN skills ON user_skills.skill_id = skills.id
              WHERE user_skills.user_id = :user_id";
        try {
            $statement = $this->pdo->prepare($query);
            $statement->bindParam(':user_id', $id, \PDO::PARAM_INT);
            $statement->execute();

            // S'assurer que le mode est PDO::FETCH_ASSOC pour retourner un tableau associatif
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function findPostsUserByID(int $id): array|bool
    {
        $query = "SELECT * FROM projects WHERE user_id = :user_id";
        try {
            $statement = $this->pdo->prepare($query);
            $statement->bindParam(':user_id', $id, \PDO::PARAM_INT);
            $statement->execute();

            // Retourner les résultats sous forme de tableau associatif
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * @param int $id
     * @param array $values
     * @return int|bool
     */

    public function updateUser(int $id, array $values): array|bool
    {
        if (empty($values)) {
            error_log("updateUser : Aucun changement détecté pour ID=$id.");
            return false;
        }

        // **Modifier ou enrichir le payload avant la mise à jour**
        if (isset($values['firstname'])) {
            $values['firstname'] = ucfirst(strtolower($values['firstname'])); // Normaliser la casse du prénom
        }
        if (isset($values['lastname'])) {
            $values['lastname'] = ucfirst(strtolower($values['lastname'])); // Normaliser la casse du nom
        }

        // Construire la requête SQL
        $sql = "UPDATE $this->table SET ";
        $keys = array_keys($values);
        $arrayKeys = [];
        foreach ($keys as $key) {
            $arrayKeys[] = "$key = :$key";
        }
        $keysStr = implode(', ', $arrayKeys);
        $sql .= $keysStr . " WHERE $this->keyName = :id";

        try {
            $statement = $this->pdo->prepare($sql);

            // Lier les valeurs SQL
            foreach ($values as $key => $val) {
                $statement->bindValue(":$key", $val);
            }
            $statement->bindValue(":id", $id, PDO::PARAM_INT);

            // Exécuter la mise à jour
            $statement->execute();

            // Vérifier si la mise à jour a réussi
            $affectedRows = $statement->rowCount();
            error_log("updateUser : $affectedRows lignes modifiées pour ID=$id.");

            // **Toujours renvoyer les nouvelles données de l'utilisateur après mise à jour**
            return $this->findUserByID($id);

        } catch (Exception $e) {
            error_log("Erreur SQL dans updateUser pour ID=$id : " . $e->getMessage());
            throw $e;
        }
    }

    public function updateProfile(int $userId, array $values): array|bool
    {
        if (empty($values)) {
            error_log("updateProfile : Aucun changement détecté pour userId=$userId.");
            return false;
        }

        // Construire la requête SQL dynamique
        $sql = "UPDATE profiles SET ";
        $keys = array_keys($values);
        $arrayKeys = [];
        foreach ($keys as $key) {
            $arrayKeys[] = "$key = :$key";
        }
        $keysStr = implode(', ', $arrayKeys);
        $sql .= $keysStr . " WHERE user_id = :user_id";

        try {
            $statement = $this->pdo->prepare($sql);

            // Lier les valeurs SQL
            foreach ($values as $key => $val) {
                $statement->bindValue(":$key", $val);
            }
            $statement->bindValue(":user_id", $userId, PDO::PARAM_INT);

            // Exécuter la mise à jour
            $statement->execute();

            // Vérifier si la mise à jour a réussi
            $affectedRows = $statement->rowCount();
            error_log("updateProfile : $affectedRows lignes modifiées pour user_id=$userId.");

            // **Toujours renvoyer les nouvelles données du profil après mise à jour**
            return $this->findProfileByUserId($userId);

        } catch (Exception $e) {
            error_log("Erreur SQL dans updateProfile pour user_id=$userId : " . $e->getMessage());
            throw $e;
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
<?php

namespace App\models;

use Config\Database;
use Exception;
use PDO;

class PostModel
{

    protected $table = 'projects'; // Nom de la table des utilisateurs
    private $pdo = null; // Instance de la connexion à la base de données
    protected $keyName = 'id'; // Colonne clé primaire pour la table

    /**
     * Constructeur de la classe UserModel
     * Initialise la connexion à la base de données à l'aide du singleton Database
     */
    public function __construct()
    {
        $this->pdo = Database::getPDO();
    }


    public function createPost(int $user_id, string $title, string $description, string $image_path, string $external_link)
    {
        $sql = "INSERT INTO $this->table (user_id, title, description, image_path, external_link) VALUES (:user_id, :title, :description, :image_path, :external_link)";
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute([
                ':user_id' => $user_id,
                ':title' => $title,
                ':description' => $description,
                ':image_path' => $image_path,
                ':external_link' => $external_link,
            ]);
            // Retourner l'ID du dernier enregistrement
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            error_log("Erreur dans createPost : " . $e->getMessage());
            return false; // Échec
        }
    }


    /**
     * Récupère tous les posts (publications) associés à un utilisateur donné, à partir de son identifiant.
     *
     * @param int $userId L'identifiant unique de l'utilisateur
     * @return array Un tableau contenant les publications de l'utilisateur, ou un tableau vide en cas d'erreur ou d'absence de publications
     */
    public function findPostsUserByID(int $userId): array
    {
        // Requête SQL pour sélectionner toutes les colonnes dans la table "posts" où "user_id" correspond à l'utilisateur donné
        $query = "SELECT * FROM $this->table WHERE user_id = :user_id"; // Suppose que la table s'appelle "posts"

        try {
            // Préparation de la requête SQL avec le PDO (permet de sécuriser la requête en évitant les injections SQL)
            $statement = $this->pdo->prepare($query);

            // Liaison du paramètre ":user_id" avec la valeur de $userId (de type entier)
            $statement->bindParam(':user_id', $userId, PDO::PARAM_INT);

            // Exécution de la requête
            $statement->execute();

            // Récupération de tous les résultats sous forme d'un tableau associatif
            // Si aucun résultat n'est trouvé, un tableau vide sera retourné
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // En cas d'erreur, on journalise un message pour tracer l'erreur
            error_log("Erreur dans findPostsUserByID : " . $e->getMessage());

            // Retourne un tableau vide pour signaler l'absence de données en cas d'échec
            return [];
        }
    }

    public function getPostById(int $id): ?array {
        $sql = "SELECT * FROM projects WHERE id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);

        error_log("Requête SQL : $sql avec ID = $id"); // Journaliser la requête exécutée

        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        $post = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $post ?: null;
    }

    public function updatePost(int $id, array $data): bool {
        $sql = "UPDATE $this->table SET title = :title, description = :description, image_path = :image_path, external_link = :external_link WHERE id = :id";
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->bindValue(':title', $data['title'], PDO::PARAM_STR);
            $statement->bindValue(':description', $data['description'], PDO::PARAM_STR);
            $statement->bindValue(':image_path', $data['image_path'], PDO::PARAM_STR);
            $statement->bindValue(':external_link', $data['external_link'], PDO::PARAM_STR);
            $result = $statement->execute();
            return $result; // Retourne true si réussie, false sinon
        } catch (Exception $e) {
            error_log("Erreur dans updatePost : " . $e->getMessage());
            return false;
        }
    }

    public function findImagePostByID(int $id)
    {
        $sql = "SELECT image_path FROM $this->table WHERE id = :id";
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur dans findImagePostByID : " . $e->getMessage());
        }
    }

    public function deletePost(int $id)
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $statement->execute();
        } catch (Exception $e) {
            error_log("Erreur dans deletePost : " . $e->getMessage());
        }
        return $result;
    }
}
<?php

namespace App\models;

use Config\Database; // Classe de gestion de la base de données
use Exception;
use PDO;

class PostModel
{

    protected $table = 'projects'; // Nom de la table dans la base de données (modifiez-le si besoin)
    private $pdo = null; // Instance de connexion PDO à la base de données
    protected $keyName = 'id'; // Colonne clé primaire de la table

    /**
     * Constructeur de PostModel
     * Initialise la connexion à la base de données avec le singleton Database.
     */
    public function __construct()
    {
        $this->pdo = Database::getPDO(); // Récupère l'instance PDO configurée
    }

    /**
     * Insère un nouveau post dans la base de données.
     *
     * @param int $user_id ID de l'utilisateur créant le post.
     * @param string $title Titre du post.
     * @param string $description Description du post.
     * @param string $image_path Chemin de l'image associée.
     * @param string $external_link Lien externe lié au post.
     * @return int|false Retourne l'ID du post créé en cas de succès, false en cas d'échec.
     */
    public function createPost(int $user_id, string $title, string $description, string $image_path, string $external_link)
    {
        $sql = "INSERT INTO $this->table (user_id, title, description, image_path, external_link) 
                VALUES (:user_id, :title, :description, :image_path, :external_link)";
        try {
            // Préparation de la requête SQL
            $statement = $this->pdo->prepare($sql);

            // Exécution avec les valeurs des paramètres
            $statement->execute([
                ':user_id' => $user_id,
                ':title' => $title,
                ':description' => $description,
                ':image_path' => $image_path,
                ':external_link' => $external_link,
            ]);

            // Retourne l'ID du dernier enregistrement inséré
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            // Journalise l'erreur si la création échoue
            error_log("Erreur dans createPost : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère tous les posts associés à un utilisateur via son ID.
     *
     * @param int $userId ID de l'utilisateur dont les posts sont récupérés.
     * @return array Tableau contenant les posts ou un tableau vide en cas d'erreur.
     */
    public function findPostsUserByID(int $userId): array
    {
        $query = "SELECT * FROM $this->table WHERE user_id = :user_id"; // Requête SQL

        try {
            // Préparation de la requête SQL
            $statement = $this->pdo->prepare($query);

            // Liaison du paramètre `:user_id`
            $statement->bindParam(':user_id', $userId, PDO::PARAM_INT);

            // Exécution de la requête
            $statement->execute();

            // Récupération des résultats sous forme d'un tableau associatif
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Journalise une erreur en cas d'échec
            error_log("Erreur dans findPostsUserByID : " . $e->getMessage());
            return [];
        }
    }

    public function getPosts(): ?array
    {
        $sql = "SELECT * FROM $this->table";

        try {
            // Préparation de la requête SQL
            $stmt = $this->pdo->prepare($sql);

            // Exécution de la requête
            $stmt->execute();

            // Retourne les résultats sous forme d'un tableau associatif
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Journalise une erreur en cas d'échec
            error_log("Erreur dans getPosts : " . $e->getMessage());
            return null; // Retourne null en cas d'échec
        }
    }

    /**
     * Récupère un post via son ID.
     *
     * @param int $id ID du post à récupérer.
     * @return array|null Les données du post sous forme de tableau ou null si aucun post trouvé.
     */
    public function getPostById(int $id): ?array
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";

        try {
            // Préparation de la requête SQL
            $stmt = $this->pdo->prepare($sql);

            // Liaison de l'ID
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            // Exécution de la requête
            $stmt->execute();

            // Retourne le résultat sous forme associative ou null
            $post = $stmt->fetch(PDO::FETCH_ASSOC);
            return $post ?: null;
        } catch (Exception $e) {
            // Journalise une erreur en cas d'échec
            error_log("Erreur dans getPostById : " . $e->getMessage());
            return null;
        }
    }

    /**
     * Met à jour les informations d'un post via son ID.
     *
     * @param int $id ID du post à mettre à jour.
     * @param array $data Données mises à jour (ex. titre, description, etc.).
     * @return bool True si la mise à jour a réussi, sinon false.
     */
    public function updatePost(int $id, array $data): bool
    {
        $sql = "UPDATE $this->table 
                SET title = :title, description = :description, image_path = :image_path, external_link = :external_link 
                WHERE id = :id";
        try {
            // Prépare la requête pour mettre à jour le post
            $statement = $this->pdo->prepare($sql);

            // Liaison des valeurs avec les colonnes
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->bindValue(':title', $data['title'], PDO::PARAM_STR);
            $statement->bindValue(':description', $data['description'], PDO::PARAM_STR);
            $statement->bindValue(':image_path', $data['image_path'], PDO::PARAM_STR);
            $statement->bindValue(':external_link', $data['external_link'], PDO::PARAM_STR);

            // Exécute la requête
            return $statement->execute();
        } catch (Exception $e) {
            // Journalise une erreur en cas d'échec
            error_log("Erreur dans updatePost : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère le chemin de l'image d'un post via son ID.
     *
     * @param int $id ID du post.
     * @return array|false Le chemin de l'image sous forme associative ou false en cas d'erreur.
     */
    public function findImagePostByID(int $id)
    {
        $sql = "SELECT image_path FROM $this->table WHERE id = :id";
        try {
            // Prépare la requête pour récupérer le chemin d'image
            $statement = $this->pdo->prepare($sql);

            // Liaison de l'ID
            $statement->bindValue(':id', $id, PDO::PARAM_INT);

            // Exécute la requête
            $statement->execute();

            // Récupère et retourne le résultat
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Journalise une erreur en cas d'échec
            error_log("Erreur dans findImagePostByID : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime un post via son ID.
     *
     * @param int $id ID du post à supprimer.
     * @return bool True si la suppression réussit, sinon false.
     */
    public function deletePost(int $id)
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        try {
            // Prépare la requête pour supprimer le post
            $statement = $this->pdo->prepare($sql);

            // Liaison de l'ID
            $statement->bindValue(':id', $id, PDO::PARAM_INT);

            // Exécute la requête
            return $statement->execute();
        } catch (Exception $e) {
            // Journalise une erreur en cas d'échec
            error_log("Erreur dans deletePost : " . $e->getMessage());
            return false;
        }
    }
}
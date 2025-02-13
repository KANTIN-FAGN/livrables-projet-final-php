<?php

namespace App\Models;

use config\Database;
use Exception;
use PDO;

class SkillModel
{
    protected $table = 'skills'; // Nom de la table des compétences
    private $pdo = null; // Instance de la connexion à la base de données
    protected $keyName = 'id'; // Nom de la clé primaire dans la table

    /**
     * Constructeur de la classe SkillModel
     * Initialise la connexion PDO à la base de données depuis le singleton `Database`
     */
    public function __construct()
    {
        $this->pdo = Database::getPDO();
    }

    public function createSkill($name) {
        $sql = "INSERT INTO $this->table (name) VALUES (:name)";
        $statement = $this->pdo->prepare($sql);
        $statement->execute(['name' => $name]);
        return $statement->rowCount();
    }

    /**
     * Récupère toutes les compétences disponibles
     *
     * @return array|bool Retourne un tableau contenant toutes les compétences (chaque ligne est un tableau associatif)
     *                    ou false en cas d'échec
     */
    public function getAllSkills(): array|bool
    {
        $sql = "SELECT * FROM $this->table"; // Requête pour récupérer toutes les compétences
        try {
            $statement = $this->pdo->prepare($sql); // Préparation de la requête
            $statement->execute(); // Exécution de la requête
            $data = $statement->fetchAll(PDO::FETCH_ASSOC); // Récupère les résultats en tableau associatif

            return $data; // Retourne les compétences sous forme de tableau associatif
        } catch (Exception $e) {
            // Gestion des erreurs : enregistre le message d'erreur dans le journal
            error_log("Erreur dans getAllSkills : " . $e->getMessage());
            return false; // Retourne false en cas d'échec
        }
    }

    /**
     * Récupère les compétences d'un utilisateur spécifique, avec leurs niveaux associés
     *
     * @param int $userId ID de l'utilisateur dont on souhaite récupérer les compétences
     * @return array Retourne un tableau d'objets où chaque objet contient l'ID de la compétence, son niveau et son nom
     */
    public function getUserSkills(int $userId): array
    {
        // Requête pour récupérer les compétences d'un utilisateur spécifique
        $sql = "SELECT us.skill_id, us.level, s.name AS skill_name
                FROM user_skills us
                JOIN skills s ON us.skill_id = s.id
                WHERE us.user_id = :userId";

        try {
            $statement = $this->pdo->prepare($sql); // Préparation de la requête
            $statement->execute(['userId' => $userId]); // Exécution avec le paramètre lié
            return $statement->fetchAll(PDO::FETCH_OBJ); // Retourne les résultats sous forme d'objets
        } catch (Exception $e) {
            // Gestion des erreurs : journalise l'erreur et retourne un tableau vide pour éviter les blocages
            error_log("Erreur dans getUserSkills pour userId=$userId : " . $e->getMessage());
            return [];
        }
    }

    public function deleteSkill($id) {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $statement = $this->pdo->prepare($sql);
        $statement->execute(['id' => $id]);
        return $statement->rowCount();
    }
}